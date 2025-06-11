# Technical Stack for API STORE

## Core Technologies

### PHP 8.1+
- Modern PHP features
- Type declarations
- Null coalescing operator
- Return type declarations
- Constructor property promotion

### Composer
- Dependency management
- Autoloading (PSR-4)
- Script management
- Version constraints

## Components

### FastRoute
- Lightweight routing
- High performance
- Simple API
- Route grouping
- Route caching

### PHP-DI
- Dependency injection container
- Autowiring
- Interface to implementation mapping
- Factory definitions
- Service configuration

### Symfony Components
- HttpFoundation: Request/Response handling
- Validator: Data validation
- Console: CLI commands
- Serializer: JSON serialization
- Security: Authentication



## Testing

### PHPUnit
- Unit testing
- Integration testing
- Mock objects
- Data providers
- Assertions

### SQLite
- Lightweight database
- File-based storage
- No server required
- Perfect for testing
- ACID compliant

## Development Tools

### PHPStan
- Static analysis tool
- Type checking
- Error detection
- Custom rules
- Level-based analysis

### PHP-CS-Fixer
- Code style fixing
- PSR-12 compliance
- Custom rules
- Automated formatting

## Docker Environment

### PHP Container
- PHP 8.1 FPM
- Extensions:
  - PDO
  - SQLite3
  - JSON
  - OpenSSL
  - Mbstring
  - XML

### Nginx
- Web server
- FastCGI
- Static file serving
- SSL termination

## Installation

### Requirements
```bash
# PHP 8.1+
php -v

# Composer
composer -V

# Docker
docker -v
docker-compose -v
```

### Dependencies
```json
{
    "require": {
        "php": "^8.1",
        "nikic/fast-route": "^1.3",
        "php-di/php-di": "^7.0",
        "symfony/http-foundation": "^6.0",
        "symfony/validator": "^6.0",
        "monolog/monolog": "^3.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "phpstan/phpstan": "^1.10",
        "friendsofphp/php-cs-fixer": "^3.0"
    }
}
```

### Installation Steps
1. Clone repository
2. Install dependencies:
```bash
composer install
```

3. Start Docker environment:
```bash
docker-compose up -d
```

4. Run tests:
```bash
composer test
```

## Development Workflow

### Code Analysis
```bash
# Run PHPStan analysis
composer phpstan

# Run with specific level
composer phpstan:level8
```

### Code Style
```bash
# Fix code style
composer cs-fix
```

### Testing
```bash
# Run all tests
composer test

# Run specific test suite
composer test:unit
composer test:integration
```

### Docker Commands
```bash
# Start environment
docker-compose up -d

# Stop environment
docker-compose down

# View logs
docker-compose logs -f

# Execute command in container
docker-compose exec php composer install
```

## Security

### Headers
- Security headers
- CORS configuration
- Input validation

### Authentication
- Bearer token
- JWT validation
- Token expiration
- Refresh tokens
