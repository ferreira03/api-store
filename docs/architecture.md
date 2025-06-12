# API STORE Architecture

## Overview
The API STORE follows a layered architecture, implementing the Repository pattern and using dependency injection to ensure low coupling and high cohesion.

## Layer Structure

### 1. Controllers (src/Controllers)
- Handle HTTP requests
- Validate input data
- Delegate operations to Services
- Format HTTP responses
- Implement basic error handling

### 2. Services (src/Services)
- Contain business logic
- Orchestrate operations between different repositories
- Implement business rules
- Are injected into controllers

### 3. Repositories (src/Repositories)
- Responsible for data persistence
- Implement CRUD operations
- Abstract data source (SQLite/JSON)
- Follow the Repository pattern

### 4. Domain (src/Domain)
- Contains entities and value objects
- Defines repository interfaces
- Implements domain rules
- Maintains pure business logic

### 5. Database (src/Database)
- Database configuration and connection management
- Migration handling
- Database schema definitions

### 6. Http (src/Http)
- Request/Response handling
- HTTP-related utilities
- PSR-7 implementation

### 7. Middleware (src/Middleware)
- Request/Response processing
- Authentication/Authorization
- CORS handling
- Request validation

### 8. Config (src/Config)
- Application configuration
- Environment variables management
- Service configuration

### 9. Exceptions (src/Exceptions)
- Custom exception classes
- Error handling strategies
- Exception formatting

### 10. Commands (src/Commands)
- CLI command handlers
- Console utilities
- Command-line interface implementation

## Request Flow
1. HTTP request arrives at entrypoint (public/index.php)
2. Router (FastRoute) directs to appropriate controller
3. Middleware processes the request
4. Controller validates data and calls service
5. Service executes business logic and uses repositories
6. Repository persists/retrieves data
7. Response is formatted and returned

## Patterns and Conventions

### PSR-4 Autoload
```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

### PSR-12 Code Style
- Namespaces in PascalCase
- Classes in PascalCase
- Methods in camelCase
- Constants in UPPER_CASE
- 4 spaces for indentation

## Technical Stack

### Core Dependencies
- PHP 8.1+
- FastRoute for routing
- PHP-DI for dependency injection
- GuzzleHttp/PSR7 for HTTP message handling
- Monolog for logging
- PHPUnit for testing
- PHPStan for static analysis
- PHP-CS-Fixer for code style

### Development Tools
- PHPUnit for unit testing
- PHPStan for static analysis
- PHP-CS-Fixer for code style enforcement
- Symfony VarDumper for debugging

## Architecture Diagram
```
[HTTP Request] → [Middleware] → [Router] → [Controller] → [Service] → [Repository] → [Database]
      ↑                                                                                    ↓
      └────────────────── [Response] ← [Service] ← [Repository] ←──────────────────────────┘
```

## Security Architecture

### Authentication Layer
- JWT-based authentication
- Token validation middleware
- Secure password hashing

### Authorization
- Role-based access control
- Resource ownership validation
- API key management
- Permission checking

## Error Handling

### Global Error Handler
- Centralized error management
- Consistent error responses
- Detailed logging
- Stack trace in development

### Error Categories
- Validation errors (400)
- Authentication errors (401)
- Authorization errors (403)
- Resource errors (404)
- Server errors (500)

## Development Workflow

### Code Quality
- PHPStan for static analysis
- PHP-CS-Fixer for code style
- Unit tests with PHPUnit
- Continuous Integration ready

### Environment Management
- .env file for configuration
- Environment-specific settings
- Secure credential management


