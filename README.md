# API STORE

## Introduction
API STORE is a RESTful API developed in pure PHP that provides an interface for store (magasins) management. The API allows listing, filtering, sorting, adding, editing, and deleting store records in a secure and efficient manner.

## Objective
The main objective of this API is to provide a robust and scalable solution for store management, following development best practices and REST standards.

## Requirements
- Docker
- Docker Compose
- PHP 8.1+
- Composer

## How to run the project

### Using Docker
1. Clone the repository
2. Run the command:
```bash
docker-compose up -d
```
3. The API will be available at `http://localhost:8000`

### Local Access
After starting the Docker environment, you can access:
- API: http://localhost:8000
- Swagger Documentation: http://localhost:8000/api/docs

### Authentication
The API uses Bearer Token authentication. To access protected endpoints, include the header:
```
Authorization: Bearer your_token_here
```

Example token for testing:
```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciJ9
```

### Running Tests
To run automated tests:
```bash
docker-compose exec php vendor/bin/phpunit
```

## Documentation
- [Architecture](docs/architecture.md)
- [Best Practices](docs/best-practices.md)
- [Testing](docs/testing.md)
- [API Documentation](docs/api-documentation.md)
- [Technical Stack](docs/technical-stack.md)
- [Folder Structure](docs/folder-structure.md)
- [Docker](docs/docker.md)
- [Authentication](docs/auth.md)
- [Logs](docs/logging.md)

## License
This project is under the MIT license. 
