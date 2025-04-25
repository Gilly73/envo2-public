# Envo2 Public

Envo2 Public is a proof-of-concept multi-container environment that orchestrates PHP and Go applications using Docker Compose. Implements a couch-selling e-commerce application when running

## Table of Contents

1. [Features](#features)
2. [Architecture](#architecture)
3. [Directory Structure](#directory-structure)

## Features

- **Reverse Proxy**: Nginx for routing and SSL termination.
- **Multiple Services**:
  - `php-fpm-backend` (PHP)
  - `php-fpm-frontend` (PHP)
  - `php-fpm-product` (Go)
- **Database**: MySQL 8.0 with initial schemas and users.
- **Caching**: Redis for caching.
- **Startup Ordering**: A `wait-for-mysql.sh` script ensures services start after MySQL.
- **Environment Variables**: Frontend supports `.env.local` for configuration.
- **Frontend Tech**: React, Tailwind CSS, and Inertia.js for the frontend UI.
- **Product Tech**: Go for the product requests.
- **Backend Tech**: Laravel for the backend processes.

## Architecture

All services run in Docker and communicate over a network named `app-network`.

| Service          | Description                                   | Ports      |
| ---------------- | --------------------------------------------- | ---------- |
| nginx            | Reverse proxy (HTTP/HTTPS)                    | 80, 443    |
| php-fpm-backend  | PHP backend application (via PHP-FPM)         | 9001       |
| php-fpm-frontend | PHP frontend application (via PHP-FPM & Vite) | 9002, 5173 |
| php-fpm-product  | Go product application (waits for MySQL)      | Internal   |
| mysql            | MySQL 8.0 database                            | 3306       |
| redis            | Redis cache                                   | 6379       |

For full service definitions, see [docker-compose.yml](./docker-compose.yml).

## Directory Structure

```
.
├── docker-compose.yml
├── mysql/
│   └── init.sql
├── nginx/
│   ├── certs/
│   └── conf.d/
│       └── default.conf
├── redis/
│   └── redis.conf
├── scripts/
│   └── wait-for-mysql.sh
└── sites/
    ├── backend/
    ├── frontend/
    └── product/
```

