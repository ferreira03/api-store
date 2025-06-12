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
The complete OpenAPI specification is available at:
```
http://localhost:8000/api/docs
```

## Endpoints

### Stores

#### List Stores
```http
GET /stores
```

Query Parameters:
- `city` (string, optional): Filter by city
- `country` (string, optional): Filter by country
- `is_active` (boolean, optional): Filter by active status
- `sort` (string, optional): Field to sort by
- `direction` (string, optional): Sort direction (asc/desc)

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
                "city": "New York",
                "country": "USA",
                "postal_code": "10001",
                "phone": "+1234567890",
                "email": "store@example.com",
                "is_active": true,
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
        "city": "New York",
        "country": "USA",
        "postal_code": "10001",
        "phone": "+1234567890",
        "email": "store@example.com",
        "is_active": true,
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
    "address": "123 Main St",
    "city": "New York",
    "country": "USA",
    "postal_code": "10001",
    "phone": "+1234567890",
    "email": "store@example.com",
    "is_active": true
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
        "city": "New York",
        "country": "USA",
        "postal_code": "10001",
        "phone": "+1234567890",
        "email": "store@example.com",
        "is_active": true,
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
    "address": "456 New St",
    "city": "Los Angeles",
    "country": "USA",
    "postal_code": "90001",
    "phone": "+1-555-0124",
    "email": "updated@example.com",
    "is_active": true
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
        "city": "Los Angeles",
        "country": "USA",
        "postal_code": "90001",
        "phone": "+1-555-0124",
        "email": "updated@example.com",
        "is_active": true,
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

#### Patch Store
```http
PATCH /stores/{id}
```

Request Body:
```json
{
    "name": "Updated Store Name",
    "is_active": false
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Updated Store Name",
        "address": "456 New St",
        "city": "Los Angeles",
        "country": "USA",
        "postal_code": "90001",
        "phone": "+1-555-0124",
        "email": "updated@example.com",
        "is_active": false,
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

## Versioning
- Current version: v1
- Version in URL: `/api/v1/...`
