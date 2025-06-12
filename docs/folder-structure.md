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
├── bin/                    # Binary files and scripts
├── docker/                 # Docker related files
├── .github/               # GitHub related files
├── docker-compose.yml     # Docker compose configuration
├── Dockerfile             # Docker configuration
├── phpunit.xml           # PHPUnit configuration
├── phpstan.neon          # PHPStan configuration
├── .php-cs-fixer.php     # PHP CS Fixer configuration
├── .php-cs-fixer.cache   # PHP CS Fixer cache
├── .phpunit.result.cache # PHPUnit result cache
├── composer.json         # Composer configuration
├── composer.lock         # Composer lock file
├── .gitignore           # Git ignore rules
└── README.md            # Project documentation
```

## Source Code (`src/`)

### Controllers
```
src/
└── Controllers/
    └── StoreController.php
```

### Http
```
src/
└── Http/
    └── Request/
        └── StoreRequest.php
```

### Services
```
src/
└── Services/
    └── StoreService.php
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
    └── exceptions/
        └── StoreException.php
```

### Database
```
src/
└── Database/
    └── Database.php
```

### Config
```
src/
└── Config/
    └── Config.php
```

### Commands
```
src/
└── Commands/
    └── CreateStoreCommand.php
```

### Middleware
```
src/
└── Middleware/
    └── AuthMiddleware.php
```

### Exceptions
```
src/
└── Exceptions/
    └── StoreException.php
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

## Public Directory (`public/`)

```
public/
├── index.php             # Entry point
└── .htaccess            # Apache configuration
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
└── logs/               # Log files
    └── app.log        # Application log
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
