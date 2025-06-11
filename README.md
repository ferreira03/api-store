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

## API Documentation

The API documentation is available through Swagger UI at:
```
http://localhost:8000/api/docs
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

### Useful Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Run tests
composer test
```

## License

This project is licensed under the MIT license. 
