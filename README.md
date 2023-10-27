# Delivery System

## About

The [delivery system](https://github.com/mjpakzad/delivery_system) created by [Mojtaba Pakzad](https://github.com/mjpakzad) uses Laravel as its framework.

## Installation

### Prerequisites:

- Docker
- Docker composer

### Clone the project
`git clone https://github.com/mjpakzad/delivery-system`

### Go to project directory
`cd delivery-system`

### Copy .env from .env.example
`cp .env.example .env`

### Modify database connection
Go to the database section and change the following variables:
```
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Build containers
```bash
docker compose up -d
```

#### Install vendors
```bash
docker exec -it delivery-system-app composer install
```

#### preparing laravel
This command will generate APP_KEY, run migrations, seed the tables and generate tokens.
```bash
docker exec -it delivery-system-app php artisan delivery:install
```

#### Test the application
```bash
docker exec -it delivery-system-app php artisan test
```
