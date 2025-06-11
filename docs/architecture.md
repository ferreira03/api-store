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

## Request Flow
1. HTTP request arrives at entrypoint (public/index.php)
2. Router (FastRoute) directs to appropriate controller
3. Controller validates data and calls service
4. Service executes business logic and uses repositories
5. Repository persists/retrieves data
6. Response is formatted and returned

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

## Technical Justifications

### Use of Standalone Components
- **FastRoute**: Light and fast routing
- **PHP-DI**: Flexible dependency injection
- **Symfony Components**: Mature and tested components

### No Full-Stack Framework
- Better code control
- Lower overhead
- Better flow understanding
- More suitable for simple APIs

### Layered Structure
- Clear separation of responsibilities
- Facilitates unit testing
- Allows implementation swapping
- Keeps code organized

## Architecture Diagram
```
[HTTP Request] → [Router] → [Controller] → [Service] → [Repository] → [Data Source]
      ↑                                                                  ↓
      └────────────────── [Response] ← [Service] ← [Repository] ←────────┘
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


