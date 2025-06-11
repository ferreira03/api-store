# Folder Structure for API STORE

## Overview
This document describes the organization of the API STORE project files and directories, following PSR-4 autoloading standards and best practices.

## Root Directory Structure
```
api-store/
├── src/                    # Source code
├── tests/                  # Test files
├── public/                 # Public entry point
├── config/                 # Configuration files
├── var/                    # Variable files
├── docs/                   # Documentation
├── vendor/                 # Composer dependencies
├── .env                    # Environment variables
├── .env.example           # Example environment variables
├── .gitignore             # Git ignore rules
├── composer.json          # Composer configuration
├── composer.lock          # Composer lock file
├── docker-compose.yml     # Docker compose configuration
├── Dockerfile             # Docker configuration
├── phpunit.xml           # PHPUnit configuration
└── README.md             # Project documentation
```

## Source Code (`src/`)

### Controllers
```
src/
└── Controllers/
    ├── StoreController.php
    └── AuthController.php
```

### Services
```
src/
└── Services/
    ├── StoreService.php
    └── AuthService.php
```

### Repositories
```
src/
└── Repositories/
    ├── StoreRepository.php
    └── interfaces/
        └── StoreRepositoryInterface.php
```

### Domain
```
src/
└── Domain/
    ├── Store.php
    ├── exceptions/
    │   └── StoreException.php
    └── value-objects/
        └── Address.php
```

### Middleware
```
src/
└── Middleware/
    ├── AuthMiddleware.php
    └── CorsMiddleware.php
```

## Tests (`tests/`)

### Unit Tests
```
tests/
└── Unit/
    ├── Services/
    │   └── StoreServiceTest.php
    └── Domain/
        └── StoreTest.php
```

### Integration Tests
```
tests/
└── Integration/
    └── Controllers/
        └── StoreControllerTest.php
```

### End-to-End Tests
```
tests/
└── E2E/
    └── Features/
        └── StoreManagementTest.php
```

### Fixtures
```
tests/
└── Fixtures/
    └── StoreFixture.php
```

## Public Directory (`public/`)

```
public/
├── index.php             # Entry point
├── .htaccess            # Apache configuration
└── assets/              # Static assets
    └── swagger/         # Swagger documentation
```

## Configuration (`config/`)

```
config/
├── container.php        # DI container configuration
├── routes.php          # Route definitions
├── database.php        # Database configuration
└── logging.php         # Logging configuration
```

## Variable Files (`var/`)

```
var/
├── cache/              # Cache files
├── logs/               # Log files
│   └── app.log        # Application log
└── database/          # Database files
    └── store.sqlite   # SQLite database
```

## Documentation (`docs/`)

```
docs/
├── architecture.md     # Architecture documentation
├── best-practices.md   # Best practices
├── testing.md         # Testing documentation
├── api-documentation.md # API documentation
├── technical-stack.md  # Technical stack
└── folder-structure.md # This file
```

## Composer Configuration

### PSR-4 Autoloading
```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    }
}
```

## Docker Configuration

### Docker Compose Services
```yaml
services:
  php:
    build: .
    volumes:
      - ./src:/var/www/html/src
      - ./config:/var/www/html/config
      - ./public:/var/www/html/public
      - ./var:/var/www/html/var
  nginx:
    image: nginx:alpine
    volumes:
      - ./public:/var/www/html
      - ./docker/nginx:/etc/nginx/conf.d
```

## Environment Variables

### Required Variables
```
DB_CONNECTION=sqlite
DB_DATABASE=var/database/store.sqlite
APP_ENV=development
APP_DEBUG=true
APP_SECRET=your-secret-key
```

## Git Configuration

### .gitignore
```
/vendor/
/var/cache/*
/var/logs/*
/var/database/*
.env
.phpunit.result.cache
```

## Development Workflow

### Directory Permissions
```bash
# Set proper permissions
chmod -R 755 public/
chmod -R 777 var/
```

### Cache Directory
```bash
# Create cache directory
mkdir -p var/cache
chmod -R 777 var/cache
```

### Log Directory
```bash
# Create log directory
mkdir -p var/logs
chmod -R 777 var/logs
```

### Database Directory
```bash
# Create database directory
mkdir -p var/database
chmod -R 777 var/database
``` 