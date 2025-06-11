# Authentication Documentation

## Overview
The API STORE uses Bearer Token authentication to secure its endpoints. This document provides detailed information about the authentication process, token format, and security considerations.

## Authentication Method
The API uses JWT (JSON Web Token) for authentication. All protected endpoints require a valid Bearer token in the Authorization header.

## Token Format
```
Authorization: Bearer <token>
```

## Token Structure
The JWT token consists of three parts:
1. Header: Contains the token type and algorithm
2. Payload: Contains the claims (user information)
3. Signature: Verifies the token's authenticity

Example token:
```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciJ9
```

## Protected Endpoints
The following endpoints require authentication:
- POST /stores
- PUT /stores/{id}
- DELETE /stores/{id}

## Security Considerations
1. Tokens expire after 24 hours
2. Tokens should be stored securely
3. Never share tokens in public repositories or logs
4. Use HTTPS for all API requests

## Error Responses

### Invalid Token
```json
{
    "status": "error",
    "error": {
        "code": "INVALID_TOKEN",
        "message": "Invalid or expired token"
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
        "message": "Authentication token is required"
    },
    "meta": {
        "timestamp": "2024-03-20T12:00:00Z",
        "request_id": "abc123"
    }
}
```

## Testing
For testing purposes, you can use the following token:
```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciJ9
```

Note: This token is for development purposes only and should not be used in production. 