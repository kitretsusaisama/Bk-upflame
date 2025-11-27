# API Reference

> **Complete REST API documentation for mobile and SPA clients**

## Table of Contents

- [Overview](#overview)
- [Authentication](#authentication)
- [Base URL](#base-url)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)
- [Endpoints](#endpoints)
- [Pagination](#pagination)
- [Filtering & Sorting](#filtering--sorting)

## Overview

The platform provides a **RESTful API** for mobile apps and SPAs using Laravel Sanctum for authentication.

### API Characteristics

- **Authentication**: Bearer tokens (Sanctum)
- **Format**: JSON request/response
- **Versioning**: `/api/v1/` prefix
- **HTTPS**: Required in production
- **Rate Limit**: 60 requests/minute per user

## Authentication

### Login (Get Token)

```http
POST /api/v1/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123",
    "device_name": "iPhone 14"
}
```

**Response:**
```json
{
    "token": "1|abc123def456...",
    "user": {
        "id": "01HXYZ...",
        "email": "user@example.com",
        "tenant_id": "01HXYZ..."
    },
    "expires_at": "2025-12-27T16:50:50Z"
}
```

### Using the Token

```http
GET /api/v1/user
Authorization: Bearer 1|abc123def456...
X-Tenant-ID: 01HXYZ...
```

### Logout

```http
POST /api/v1/logout
Authorization: Bearer {token}
```

**Response:** `204 No Content`

## Base URL

**Development:**
```
http://localhost:8000/api/v1
```

**Production:**
```
https://api.yourdomain.com/api/v1
```

## Response Format

### Success Response

```json
{
    "data": {
        "id": "01HXYZ...",
        "name": "Example",
        ...
    },
    "meta": {
        "timestamp": "2025-11-27T16:50:50Z"
    }
}
```

### Collection Response

```json
{
    "data": [
        {"id": "01HXYZ1", ...},
        {"id": "01HXYZ2", ...}
    ],
    "links": {
        "first": "https://api.../v1/users?page=1",
        "last": "https://api.../v1/users?page=10",
        "prev": null,
        "next": "https://api.../v1/users?page=2"
    },
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 150
    }
}
```

## Error Handling

### Error Response Format

```json
{
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

### HTTP Status Codes

| Code | Meaning | Usage |
|------|---------|-------|
| 200 | OK | Successful GET, PUT, PATCH |
| 201 | Created | Successful POST |
| 204 | No Content | Successful DELETE |
| 400 | Bad Request | Invalid request format |
| 401 | Unauthorized | Missing or invalid token |
| 403 | Forbidden | No permission |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

## Rate Limiting

**Default**: 60 requests/minute per user

**Headers:**
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
X-RateLimit-Reset: 1700000000
```

**429 Response:**
```json
{
    "message": "Too many requests. Please try again in 60 seconds."
}
```

## Endpoints

### Authentication

#### POST /api/v1/login
Login and receive access token

**Request:**
```json
{
    "email": "user@example.com",
    "password": "password",
    "device_name": "iPhone 14"
}
```

#### POST /api/v1/logout
Revoke current access token

#### GET /api/v1/user
Get authenticated user details

**Response:**
```json
{
    "data": {
        "id": "01HXYZ...",
        "email": "user@example.com",
        "status": "active",
        "tenant_id": "01HXYZ...",
        "roles": [
            {"id": "01...", "name": "Provider", "priority": 50}
        ]
    }
}
```

### Users

#### GET /api/v1/users
List users (requires `view-user` permission)

**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15, max: 100)
- `status` - Filter by status
- `search` - Search by email/name

**Example:**
```http
GET /api/v1/users?page=1&per_page=20&status=active&search=john
```

#### GET /api/v1/users/{id}
Get single user

#### POST /api/v1/users
Create new user (requires `create-user`)

**Request:**
```json
{
    "email": "newuser@example.com",
    "password": "password123",
    "status": "active",
    "role_ids": ["01HXYZ..."]
}
```

#### PUT /api/v1/users/{id}
Update user (requires `update-user`)

#### DELETE /api/v1/users/{id}
Delete user (requires `delete-user`)

### Roles & Permissions

#### GET /api/v1/roles
List roles

#### GET /api/v1/roles/{id}
Get single role with permissions

**Response:**
```json
{
    "data": {
        "id": "01HXYZ...",
        "name": "Tenant Admin",
        "priority": 10,
        "permissions": [
            {"id": "01...", "name": "view-user"},
            {"id": "01...", "name": "create-user"}
        ]
    }
}
```

#### POST /api/v1/users/{userId}/roles
Assign role to user

**Request:**
```json
{
    "role_id": "01HXYZ..."
}
```

### Bookings

#### GET /api/v1/bookings
List bookings

**Query Parameters:**
- `status` - Filter by status (pending, confirmed, completed, cancelled)
- `provider_id` - Filter by provider
- `from_date` - Filter start date (ISO 8601)
- `to_date` - Filter end date

**Example:**
```http
GET /api/v1/bookings?status=pending&from_date=2025-01-01
```

#### GET /api/v1/bookings/{id}
Get single booking

**Response:**
```json
{
    "data": {
        "id": "01HXYZ...",
        "user_id": "01...",
        "provider_id": "01...",
        "service_id": "01...",
        "status": "confirmed",
        "scheduled_at": "2025-12-01T10:00:00Z",
        "total_amount": 150.00,
        "user": {
            "id": "01...",
            "email": "customer@example.com"
        },
        "provider": {
            "id": "01...",
            "name": "Dr. Smith"
        },
        "service": {
            "id": "01...",
            "name": "Consultation",
            "duration_minutes": 30
        }
    }
}
```

#### POST /api/v1/bookings
Create new booking

**Request:**
```json
{
    "provider_id": "01HXYZ...",
    "service_id": "01HXYZ...",
    "scheduled_at": "2025-12-01T10:00:00Z",
    "notes": "First visit"
}
```

#### PATCH /api/v1/bookings/{id}/confirm
Confirm booking (provider only)

#### PATCH /api/v1/bookings/{id}/complete
Mark booking as completed

#### PATCH /api/v1/bookings/{id}/cancel
Cancel booking

**Request:**
```json
{
    "cancellation_reason": "Schedule conflict"
}
```

### Providers

#### GET /api/v1/providers
List providers

**Query Parameters:**
- `status` - Filter by status
- `specialization` - Filter by specialization

#### GET /api/v1/providers/{id}
Get provider details with services

**Response:**
```json
{
    "data": {
        "id": "01HXYZ...",
        "specialization": "Cardiology",
        "status": "approved",
        "bio": "20 years experience...",
        "services": [
            {"id": "01...", "name": "Consultation", "price": 150.00}
        ],
        "availability": [
            {
                "day_of_week": 1,
                "start_time": "09:00",
                "end_time": "17:00"
            }
        ]
    }
}
```

#### GET /api/v1/providers/{id}/slots
Get available time slots

**Query Parameters:**
- `date` - Date to check (YYYY-MM-DD)
- `service_id` - Service ID

**Response:**
```json
{
    "data": [
        {"time": "09:00", "available": true},
        {"time": "09:30", "available": false},
        {"time": "10:00", "available": true}
    ]
}
```

### Services

#### GET /api/v1/services
List services

#### GET /api/v1/services/{id}
Get single service

### Tenants (Super Admin only)

#### GET /api/v1/admin/tenants
List all tenants

#### GET /api/v1/admin/tenants/{id}
Get tenant details

#### POST /api/v1/admin/tenants
Create new tenant

#### PUT /api/v1/admin/tenants/{id}/suspend
Suspend tenant

#### PUT /api/v1/admin/tenants/{id}/activate
Activate tenant

### Workflows

#### GET /api/v1/workflows
List workflows

#### GET /api/v1/workflows/{id}
Get workflow with steps

#### POST /api/v1/workflows
Initiate new workflow

#### POST /api/v1/workflows/{id}/steps/{stepId}/approve
Approve workflow step

#### POST /api/v1/workflows/{id}/steps/{stepId}/reject
Reject workflow step

**Request:**
```json
{
    "reason": "Missing required documents"
}
```

## Pagination

All collection endpoints support pagination:

```http
GET /api/v1/users?page=2&per_page=20
```

**Response includes:**
```json
{
    "data": [...],
    "links": {
        "first": "...",
        "last": "...",
        "prev": "...",
        "next": "..."
    },
    "meta": {
        "current_page": 2,
        "from": 21,
        "to": 40,
        "per_page": 20,
        "total": 150,
        "last_page": 8
    }
}
```

## Filtering & Sorting

### Filtering

```http
GET /api/v1/bookings?status=pending&provider_id=01HXYZ...
```

### Sorting

```http
GET /api/v1/users?sort=-created_at  # Descending
GET /api/v1/users?sort=email        # Ascending
```

### Search

```http
GET /api/v1/users?search=john
```

## SDK Examples

### JavaScript/TypeScript

```javascript
// Login
const response = await fetch('https://api.../v1/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password',
        device_name: 'Web Browser'
    })
});

const { token } = await response.json();

// Use token
const users = await fetch('https://api.../v1/users', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'X-Tenant-ID': tenantId
    }
});
```

### cURL

```bash
# Login
curl -X POST https://api.../v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password","device_name":"curl"}'

# Get users
curl https://api.../v1/users \
  -H "Authorization: Bearer 1|abc123..." \
  -H "X-Tenant-ID: 01HXYZ..."
```

## Cross-Links

- [Authentication](authentication.md) - Auth implementation details
- [RBAC](rbac.md) - Permission requirements per endpoint
- [Database Schema](database-schema.md) - Data models
- [Routes](routes.md) - Route definitions
