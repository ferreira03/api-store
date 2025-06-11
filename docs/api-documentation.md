# API Documentation for API STORE

## Overview
This document provides detailed information about the API STORE endpoints, request/response formats, and authentication requirements.

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer your_token_here
```

## Swagger Specification

```yaml
openapi: 3.0.0
info:
  title: API STORE
  version: 1.0.0
  description: RESTful API for store management

servers:
  - url: http://localhost:8000/api/v1
    description: Development server

components:
  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT
      description: JWT token for API authentication

security:
  - bearerAuth: []

paths:
  /stores:
    post:
      security:
        - bearerAuth: []
      summary: Create a new store
      description: Creates a new store with the provided information
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required:
                - name
                - address
              properties:
                name:
                  type: string
                  description: Name of the store
                address:
                  type: string
                  description: Address of the store
      responses:
        '201':
          description: Store created successfully
        '401':
          description: Unauthorized - Invalid or missing token
        '422':
          description: Validation error

    put:
      security:
        - bearerAuth: []
      summary: Update a store
      description: Updates an existing store with the provided information
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                address:
                  type: string
      responses:
        '200':
          description: Store updated successfully
        '401':
          description: Unauthorized - Invalid or missing token
        '404':
          description: Store not found

    delete:
      security:
        - bearerAuth: []
      summary: Delete a store
      description: Deletes an existing store
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        '204':
          description: Store deleted successfully
        '401':
          description: Unauthorized - Invalid or missing token
        '404':
          description: Store not found
```

## Endpoints

### Stores

#### List Stores
```http
GET /stores
```

Query Parameters:
- `page` (integer, optional): Page number (default: 1)
- `limit` (integer, optional): Items per page (default: 10)
- `sort` (string, optional): Field to sort by (default: id)
- `order` (string, optional): Sort order (asc/desc, default: asc)
- `search` (string, optional): Search term

Response:
```json
{
    "status": "success",
    "data": {
        "items": [
            {
                "id": 1,
                "name": "Store Name",
                "address": "Store Address",
                "created_at": "2024-03-20T12:00:00Z",
                "updated_at": "2024-03-20T12:00:00Z"
            }
        ],
        "pagination": {
            "total": 100,
            "per_page": 10,
            "current_page": 1,
            "last_page": 10
        }
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

#### Get Store
```http
GET /stores/{id}
```

Response:
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Store Name",
        "address": "Store Address",
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

#### Create Store
```http
POST /stores
```

Request Body:
```json
{
    "name": "New Store",
    "address": "123 Main St"
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "New Store",
        "address": "123 Main St",
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

#### Update Store
```http
PUT /stores/{id}
```

Request Body:
```json
{
    "name": "Updated Store",
    "address": "456 New St"
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Updated Store",
        "address": "456 New St",
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

#### Delete Store
```http
DELETE /stores/{id}
```

Response:
```json
{
    "status": "success",
    "data": null,
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

## Error Responses

### Validation Error
```json
{
    "status": "error",
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Invalid input data",
        "details": [
            {
                "field": "name",
                "message": "Name is required"
            }
        ]
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

### Not Found Error
```json
{
    "status": "error",
    "error": {
        "code": "NOT_FOUND",
        "message": "Store not found"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

### Unauthorized Error
```json
{
    "status": "error",
    "error": {
        "code": "UNAUTHORIZED",
        "message": "Authentication required"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

## OpenAPI/Swagger Specification
The complete OpenAPI specification is available at:
```
http://localhost:8000/api/docs
```


## Versioning
- Current version: v1
- Version in URL: `/api/v1/...`
