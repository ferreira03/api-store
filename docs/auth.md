# Authentication Documentation

## Overview
The API STORE uses Bearer Token authentication to secure its endpoints. This document provides detailed information about the authentication process, token format, and security considerations.

## Authentication Method
The API uses a simple Bearer token authentication. All protected endpoints require a valid Bearer token in the Authorization header.

## Token Format
```
Authorization: Bearer <token>
```

## Protected Endpoints
The following endpoints require authentication:
- POST /stores
- PUT /stores/{id}
- DELETE /stores/{id}
- PATCH /stores/{id}

## Security Considerations
1. The API token is configured through environment variables
2. Tokens should be stored securely
3. Never share tokens in public repositories or logs
4. Use HTTPS for all API requests

## Error Responses

### Invalid Token
```json
{
    "status": "error",
    "error": {
        "code": "UNAUTHORIZED",
        "message": "Invalid token"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

### Missing Token
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

### Invalid Authorization Format
```json
{
    "status": "error",
    "error": {
        "code": "UNAUTHORIZED",
        "message": "Invalid authorization header format"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

## Configuration
The API token is configured through the environment variable `API_TOKEN`. You can set this in your `.env` file:

```
API_TOKEN=your-secure-token-here
```

Note: Make sure to use a strong, unique token in production environments.

## Testing
For testing purposes, you can use the following token:
```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciJ9
```

Note: This token is for development purposes only and should not be used in production.
