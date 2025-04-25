package main

import (
	"crypto/sha256"
	"database/sql"
	"encoding/hex"
	"fmt"
	"log"
	"net/http"
	"strings"
	"time"

	"github.com/gin-contrib/cors"
	"github.com/gin-gonic/gin"
	_ "github.com/go-sql-driver/mysql"
	"github.com/getsentry/sentry-go"
    sentrygin "github.com/getsentry/sentry-go/gin"
)

// Option represents a simple value-label pair.

type Option struct {
	Value string `json:"value"`
	Label string `json:"label"`
}

// CouchOptions holds indoor and outdoor configurations.
type CouchOptions struct {
	Indoor struct {
		Styles []Option `json:"styles"`
		Fabrics []Option `json:"fabrics"`
		Legs    []Option `json:"legs"`
		Seaters []Option `json:"seaters"`
	} `json:"1"`
	Outdoor struct {
		Styles []Option `json:"styles"`
		Fabrics []Option `json:"fabrics"`
		Legs    []Option `json:"legs"`
		Seaters []Option `json:"seaters"`
	} `json:"2"`
}

// ResponseData is the JSON response structure.
type ResponseData struct {
	CouchOptions   CouchOptions `json:"couchOptions"`
	CountryOptions []Option     `json:"countryOptions"`
	CouchTypesOptions []Option  `json:"couchTypesOptions"`
}

var db *sql.DB

// initDB initializes the MySQL connection.
func initDB() {
	 var err error
	// // Update DSN with your actual MySQL credentials and database name.
	dsn := "envov2backend:envov2backend@tcp(mysql:3306)/backend_db?parseTime=true"
	db, err = sql.Open("mysql", dsn)
	if err != nil {
		log.Fatalf("Error opening database: %v", err)
	}
	if err = db.Ping(); err != nil {
		log.Fatalf("Error pinging database: %v", err)
	}
	log.Println("Connected to MySQL successfully.")
}

// hashToken computes the SHA256 hash of the token.
func hashToken(token string) string {
	h := sha256.New()
	h.Write([]byte(token))
	return hex.EncodeToString(h.Sum(nil))
}

// isValidToken checks whether the provided token (after hashing) exists in the DB.
func isValidToken(token string) bool {
	log.Printf("Raw token: %s", token)
	parts := strings.Split(token, "|")
    if len(parts) != 2 {
        log.Println("Invalid token format")
        return false
    }
	plainToken := parts[1]
	hashedToken := hashToken(plainToken)
	log.Printf("Computed hash: %s", hashedToken)
	var count int
	err := db.QueryRow("SELECT COUNT(*) FROM personal_access_tokens WHERE token = ?", hashedToken).Scan(&count)
	if err != nil {
		log.Printf("Error checking token: %v", err)
		return false
	}
	return count > 0
}

// tokenAuthMiddleware is a Gin middleware that validates the Bearer token.
func tokenAuthMiddleware(c *gin.Context) {
	authHeader := c.GetHeader("Authorization")
	log.Printf("Authorization header: %s", authHeader)
	if authHeader == "" {
		c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Missing Authorization header"})
		return
	}
	token := strings.TrimPrefix(authHeader, "Bearer ")
	if !isValidToken(token) {
		c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Invalid token"})
		return
	}
	c.Next()
}

func fetchOptions(table string, couchTypeID int) ([]Option, error) {
	var query string
	//var isSeaters bool

	switch table {
	case "styles":
		query = "SELECT id, name FROM styles WHERE couch_type_id = ?"
	case "fabrics":
		query = "SELECT id, material FROM fabrics WHERE couch_type_id = ?"
	case "legs":
		query = "SELECT id, description FROM legs WHERE couch_type_id = ?"
	case "seaters":
		query = "SELECT id, seat_count FROM seaters WHERE couch_type_id = ?"
		//isSeaters = true
	default:
		return nil, fmt.Errorf("unknown table: %s", table)
	}

	rows, err := db.Query(query, couchTypeID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var results []Option
	for rows.Next() {
		//if isSeaters {
			// var seatCount int
			// if err := rows.Scan(&seatCount, &seatCount); err != nil {
			// 	return nil, err
			// }
			// seatStr := fmt.Sprintf("%d", seatCount)
			// results = append(results, Option{Value: seatStr, Label: seatStr})
		//} else {
			var val, lab string
			if err := rows.Scan(&val, &lab); err != nil {
				return nil, err
			}
			// For consistency, make the value lowercase.
			results = append(results, Option{Value: strings.ToLower(val), Label: lab})
		//}
	}
	return results, rows.Err()
}

// fetchCouchOptions fetches indoor and outdoor couch options.
func fetchCouchOptions() (CouchOptions, error) {
	var options CouchOptions
	log.Printf("fetchCouchOptions")
	// Assuming couch_type_id 1 = indoor, 2 = outdoor.
	indoorFabrics, err := fetchOptions("fabrics", 1)
	if err != nil {
		return options, err
	}
	indoorLegs, err := fetchOptions("legs", 1)
	if err != nil {
		return options, err
	}
	indoorSeaters, err := fetchOptions("seaters", 1)
	if err != nil {
		return options, err
	}
	indoorStyles, err := fetchOptions("styles", 1)
	if err != nil {
		return options, err
	}
	options.Indoor.Fabrics = indoorFabrics
	options.Indoor.Legs = indoorLegs
	options.Indoor.Seaters = indoorSeaters
	options.Indoor.Styles = indoorStyles

	outdoorFabrics, err := fetchOptions("fabrics", 2)
	if err != nil {
		return options, err
	}
	outdoorLegs, err := fetchOptions("legs", 2)
	if err != nil {
		return options, err
	}
	outdoorSeaters, err := fetchOptions("seaters", 2)
	if err != nil {
		return options, err
	}
	outdoorStyles, err := fetchOptions("styles", 2)
	if err != nil {
		return options, err
	}
	options.Outdoor.Fabrics = outdoorFabrics
	options.Outdoor.Legs = outdoorLegs
	options.Outdoor.Seaters = outdoorSeaters
	options.Outdoor.Styles = outdoorStyles

	return options, nil
}

// fetchCountryOptions queries the DB for country options.
func fetchCountryOptions() ([]Option, error) {
	rows, err := db.Query("SELECT country, label FROM countries")
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var results []Option
	for rows.Next() {
		var opt Option
		if err := rows.Scan(&opt.Value, &opt.Label); err != nil {
			return nil, err
		}
		results = append(results, opt)
	}
	return results, rows.Err()
}

func fetchCouchTypes() ([]Option, error) {
	rows, err := db.Query("SELECT id, name FROM couch_types")
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var results []Option
	for rows.Next() {
		var opt Option
		if err := rows.Scan(&opt.Value, &opt.Label); err != nil {
			return nil, err
		}
		results = append(results, opt)
	}
	return results, rows.Err()
}

// optionsHandler returns a JSON response containing couch and country options.
func optionsHandler(c *gin.Context) {
	couchOpts, err := fetchCouchOptions()
	if err != nil {
		sentry.CaptureException(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch couch options"})
		return
	}
	countryOpts, err := fetchCountryOptions()
	if err != nil {
		sentry.CaptureException(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch country options"})
		return
	}
	couchTypesOpts, err := fetchCouchTypes()
	if err != nil {
		sentry.CaptureException(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch couch types options"})
		return
	}
	response := ResponseData{
		CouchOptions:   couchOpts,
		CountryOptions: countryOpts,
		CouchTypesOptions: couchTypesOpts,
	}
	c.JSON(http.StatusOK, response)
}

// helloHandler is a simple health-check endpoint.
func helloHandler(c *gin.Context) {
	log.Println("Hello, World! Docker Go environment is working!")
	c.String(http.StatusOK, "Hello, World! Docker Go environment is working!")
}

func initSentry() {
	// Initialize Sentry with your DSN
    err := sentry.Init(sentry.ClientOptions{
        Dsn: "https://75884247b0e6b30d1a03d8acd263468a@o4508749376323584.ingest.de.sentry.io/4509005148520528", // Replace with your actual DSN
        TracesSampleRate: 1.0,  // Adjust this rate as needed
    })
    if err != nil {
        log.Fatalf("sentry.Init: %s", err)
    }
    // Flush buffered events before the program terminates.
    defer sentry.Flush(2 * time.Second)
}

func main() {
	initSentry()

	initDB()

	// Create a Gin router with default middleware (logger and recovery)
	router := gin.Default()
	router.Use(sentrygin.New(sentrygin.Options{}))

	// Configure CORS middleware options
	router.Use(cors.New(cors.Config{
		AllowOrigins:     []string{"https://frontend"}, // Adjust to your frontend's domain
		AllowMethods:     []string{"GET", "POST", "PUT", "DELETE", "OPTIONS"},
		AllowHeaders:     []string{"Origin", "Content-Type", "Accept", "Authorization"},
		ExposeHeaders:    []string{"Content-Length"},
		AllowCredentials: true,
		MaxAge:           12 * time.Hour,
	  }))


	// Create an authorized group for routes that need token authentication.
	authorized := router.Group("/", tokenAuthMiddleware)
	{
		authorized.GET("/options", optionsHandler)
	}

	// Public endpoint for testing.
	router.GET("/", helloHandler)

	// For example, an endpoint that triggers an error.
	router.GET("/panic", func(c *gin.Context) {
		panic("Something went wrong!")
	})

	log.Println("Product API running on :9000")
	router.Run(":9000")
}
