# API STORE

RESTful API for store management.

## Requirements

- PHP 8.2 or higher
- Composer
- Docker and Docker Compose

## Installation

1. Clone the repository:
```bash
git clone [REPOSITORY_URL]
cd api-store
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp .env.example .env
```

4. Start Docker containers:
```bash
docker-compose up -d
```

5. Run database migrations:
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate
```

## Documentation

For detailed information about the project, please refer to the following documentation:

- [API Documentation](docs/api-documentation.md) - Detailed API endpoints and usage
- [Architecture](docs/architecture.md) - System architecture and design decisions
- [Authentication](docs/auth.md) - Authentication and authorization details
- [Best Practices](docs/best-practices.md) - Coding standards and best practices
- [Docker Setup](docs/docker.md) - Docker configuration and usage
- [Folder Structure](docs/folder-structure.md) - Project organization and structure
- [Migration Guide](docs/migration.md) - Database migration procedures
- [Technical Stack](docs/technical-stack.md) - Technologies and frameworks used
- [Testing](docs/testing.md) - Testing strategies and procedures

## API Documentation

The API documentation is available through Swagger UI at:
```
http://localhost:8000/api/docs/
```

The documentation includes:
- List of all available routes
- Input parameters details
- Request and response examples
- HTTP status codes

## API Routes

### Stores

- `GET /api/v1/stores` - List all stores
- `GET /api/v1/stores/{id}` - Get a specific store
- `POST /api/v1/stores` - Create a new store
- `PUT /api/v1/stores/{id}` - Update an existing store
- `DELETE /api/v1/stores/{id}` - Remove a store

## Development

### Project Structure

```
.
├── config/             # Application configurations
├── public/            # Application entry point
│   └── api/
│       └── docs/      # API Documentation (Swagger UI)
├── src/               # Source code
│   ├── Controllers/   # Controllers
│   ├── Models/        # Models
│   └── Services/      # Services
├── tests/             # Automated tests
├── var/               # Temporary files
└── vendor/            # Composer dependencies
```

### Database Management

#### Migrations

To create a new migration:
```bash
docker-compose exec php php bin/console doctrine:migrations:diff
```

To run migrations:
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate
```

#### Fixtures

Fixtures are available to populate the database with test data. They include:
- Sample stores with different configurations
- Test users and permissions
- Other necessary test data

To load fixtures:
```bash
docker-compose exec php php bin/console doctrine:fixtures:load
```

To append fixtures (without clearing the database):
```bash
docker-compose exec php php bin/console doctrine:fixtures:load --append
```

### Testing

For detailed information about testing strategies, procedures, and how to run tests, please refer to the [Testing Documentation](docs/testing.md).

### Useful Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Run tests
docker-compose exec php php bin/phpunit
```

## License

This project is licensed under the MIT license.
