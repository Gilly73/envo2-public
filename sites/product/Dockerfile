# Build stage
FROM golang:1.24.1 AS builder
WORKDIR /app

# Disable cgo to force static linking
#Go builds a static binary. This ensures that your binary doesn't depend on the glibc version in the final image
#The binary is built and output as main in the /app directory
ENV CGO_ENABLED=0

# Cache Copy module files and download dependencies
COPY go.mod go.sum ./
RUN go mod download

# Copy the source code and build the binary
COPY . .

#Build the binary
RUN go build -o main .

# Final Stage
#FROM debian:bullseye-slim
#RUN apt-get update && apt-get install -y ca-certificates && rm -rf /var/lib/apt/lists/*
#WORKDIR /root/
#COPY --from=builder /app/main .
#EXPOSE 9000
#CMD ["./main"]

# Final Stage
#We use FROM scratch (a completely empty image) because our binary is fully static and doesn't require any libraries
FROM debian:bullseye-slim
#RUN apt-get update && apt-get install -y ca-certificates && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y ca-certificates netcat-openbsd && rm -rf /var/lib/apt/lists/*
WORKDIR /root/
COPY --from=builder /app/main .
EXPOSE 9000
CMD ["./main"]