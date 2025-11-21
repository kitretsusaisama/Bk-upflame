# Enterprise-Grade Multi-Tenant Backend Platform API Documentation

## Overview

This document provides comprehensive API documentation for the enterprise-grade multi-tenant backend platform. All APIs follow RESTful principles and are versioned under `/api/v1/`. The platform implements robust security measures including OAuth2/OIDC authentication, rate limiting, and comprehensive input validation.

## Base URL

```
https://api.upflame.in/v1/
```

## Authentication

Most endpoints require authentication using Bearer tokens obtained through the OAuth2/OIDC flow.

### Headers

```
Authorization: Bearer {access_token}
Content-Type: application/json
X-Tenant-ID: {tenant_id}  // Optional if using domain-based tenant resolution
```

### Response Format

All API responses follow a consistent envelope format:

```json
{
  "status": "success|error",
  "data": {...},
  "meta": {...},
  "error": {
    "code": "error_code",
    "message": "Human readable error message",
    "details": {...}
  }
}
```

## Rate Limiting

APIs implement adaptive rate limiting:
- Per IP: 1000 requests/hour
- Per User: 5000 requests/hour
- Per Tenant: 10000 requests/hour

Exceeding limits returns a 429 status code.

## Error Codes

| Code | HTTP Status | Description |
|------|-------------|-------------|
| VALIDATION_ERROR | 400 | Request validation failed |
| UNAUTHORIZED | 401 | Authentication required |
| FORBIDDEN | 403 | Insufficient permissions |
| NOT_FOUND | 404 | Resource not found |
| TOO_MANY_REQUESTS | 429 | Rate limit exceeded |
| INTERNAL_ERROR | 500 | Internal server error |

---

## 1. Auth Module

### POST /auth/login

User login with optional MFA support.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123",
    "mfa_code": "123456"
  }'
```

**Request Body:**
```json
{
  "email": "string",
  "password": "string",
  "mfa_code": "string"  // Optional if MFA enabled
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 900,
    "token_type": "Bearer",
    "refresh_token": "dGhpcyBpcyBhIHJlZnJlc2ggdG9rZW4..."
  }
}
```

### POST /auth/token

Exchange refresh token for new access token.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "refresh_token",
    "refresh_token": "dGhpcyBpcyBhIHJlZnJlc2ggdG9rZW4...",
    "client_id": "client123",
    "client_secret": "secret123"
  }'
```

**Request Body:**
```json
{
  "grant_type": "refresh_token",
  "refresh_token": "string",
  "client_id": "string",
  "client_secret": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 900,
    "token_type": "Bearer"
  }
}
```

### POST /auth/logout

Logout current user session.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/auth/logout \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "message": "Successfully logged out"
  }
}
```

### GET /auth/me

Get current user information.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/auth/me \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "user123",
    "email": "user@example.com",
    "tenant_id": "tenant123",
    "status": "active",
    "mfa_enabled": true
  }
}
```

### POST /auth/mfa/setup

Setup MFA for current user.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/auth/mfa/setup \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "type": "totp"
  }'
```

**Request Body:**
```json
{
  "type": "totp|sms|email"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "type": "totp",
    "secret": "JBSWY3DPEHPK3PXP",
    "qr_code_url": "otpauth://totp/Example:user@example.com?secret=JBSWY3DPEHPK3PXP&issuer=Example"
  }
}
```

### POST /auth/mfa/verify

Verify MFA setup.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/auth/mfa/verify \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "type": "totp",
    "code": "123456"
  }'
```

**Request Body:**
```json
{
  "type": "totp|sms|email",
  "code": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "message": "MFA method verified successfully"
  }
}
```

---

## 2. Tenants Module

### POST /tenants

Create a new tenant (admin only).

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/tenants \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Acme Corporation",
    "domain": "acme.example.com",
    "admin_email": "admin@acme.example.com",
    "admin_password": "securePassword123"
  }'
```

**Request Body:**
```json
{
  "name": "string",
  "domain": "string",
  "admin_email": "string",
  "admin_password": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "tenant123",
    "name": "Acme Corporation",
    "domain": "acme.example.com",
    "status": "active",
    "created_at": "2025-11-20T10:00:00Z"
  }
}
```

### GET /tenants/{id}

Get tenant information.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/tenants/tenant123 \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "tenant123",
    "name": "Acme Corporation",
    "domain": "acme.example.com",
    "status": "active",
    "domains": [
      {
        "domain": "acme.example.com",
        "is_primary": true,
        "is_verified": true
      }
    ],
    "created_at": "2025-11-20T10:00:00Z"
  }
}
```

### PUT /tenants/{id}/domains

Add or update tenant domain.

**Request:**
```bash
curl -X PUT https://api.upflame.in/v1/tenants/tenant123/domains \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "domain": "auth.acme.example.com",
    "is_primary": false
  }'
```

**Request Body:**
```json
{
  "domain": "string",
  "is_primary": "boolean"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "message": "Domain added successfully"
  }
}
```

---

## 3. Users & Roles Module

### POST /users

Create a new user.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/users \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john.doe@acme.example.com",
    "password": "securePassword123",
    "first_name": "John",
    "last_name": "Doe",
    "phone": "+1234567890"
  }'
```

**Request Body:**
```json
{
  "email": "string",
  "password": "string",
  "first_name": "string",
  "last_name": "string",
  "phone": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "user123",
    "email": "john.doe@acme.example.com",
    "first_name": "John",
    "last_name": "Doe",
    "status": "pending",
    "created_at": "2025-11-20T10:00:00Z"
  }
}
```

### GET /users/{id}

Get user information.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/users/user123 \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "user123",
    "email": "john.doe@acme.example.com",
    "first_name": "John",
    "last_name": "Doe",
    "status": "active",
    "roles": [
      {
        "id": "role123",
        "name": "Customer",
        "role_family": "Customer"
      }
    ],
    "created_at": "2025-11-20T10:00:00Z"
  }
}
```

### POST /users/{id}/roles

Assign role to user.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/users/user123/roles \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "role_id": "role123"
  }'
```

**Request Body:**
```json
{
  "role_id": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "message": "Role assigned successfully"
  }
}
```

### GET /roles

List all roles.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/roles \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "role123",
      "name": "Customer",
      "role_family": "Customer",
      "description": "End customer role"
    },
    {
      "id": "role456",
      "name": "Admin",
      "role_family": "Internal",
      "description": "Administrator role"
    }
  ]
}
```

### POST /roles

Create a new role.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/roles \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "PremiumCustomer",
    "role_family": "Customer",
    "description": "Premium customer with additional privileges",
    "permissions": ["booking.create", "booking.update"]
  }'
```

**Request Body:**
```json
{
  "name": "string",
  "role_family": "Provider|Internal|Customer",
  "description": "string",
  "permissions": ["string"]
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "role789",
    "name": "PremiumCustomer",
    "role_family": "Customer",
    "description": "Premium customer with additional privileges"
  }
}
```

---

## 4. Permissions & Policies Module

### POST /permissions

Create a new permission.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/permissions \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "booking.cancel",
    "resource": "booking",
    "action": "cancel",
    "description": "Cancel a booking"
  }'
```

**Request Body:**
```json
{
  "name": "string",
  "resource": "string",
  "action": "string",
  "description": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "perm123",
    "name": "booking.cancel",
    "resource": "booking",
    "action": "cancel"
  }
}
```

### POST /policies

Create a new policy.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/policies \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "PremiumCustomerBookingPolicy",
    "description": "Allows premium customers to cancel bookings up to 24 hours before",
    "target": {
      "resource": "booking",
      "action": "cancel"
    },
    "rules": {
      "effect": "allow",
      "conditions": [
        {
          "user.role": "PremiumCustomer",
          "booking.scheduled_time": "> now() + 24h"
        }
      ]
    }
  }'
```

**Request Body:**
```json
{
  "name": "string",
  "description": "string",
  "target": {
    "resource": "string",
    "action": "string"
  },
  "rules": {
    "effect": "allow|deny",
    "conditions": [
      {
        "attribute": "condition"
      }
    ]
  }
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "policy123",
    "name": "PremiumCustomerBookingPolicy",
    "is_enabled": true
  }
}
```

### GET /policies

List all policies.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/policies \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": "policy123",
      "name": "PremiumCustomerBookingPolicy",
      "description": "Allows premium customers to cancel bookings up to 24 hours before",
      "is_enabled": true,
      "created_at": "2025-11-20T10:00:00Z"
    }
  ]
}
```

---

## 5. Workflows Module

### POST /workflows

Create a new workflow instance.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/workflows \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "definition_id": "workflow_def_123",
    "entity_type": "provider",
    "entity_id": "provider_123"
  }'
```

**Request Body:**
```json
{
  "definition_id": "string",
  "entity_type": "string",
  "entity_id": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "workflow_123",
    "definition_id": "workflow_def_123",
    "entity_type": "provider",
    "entity_id": "provider_123",
    "status": "pending",
    "current_step_key": "registration"
  }
}
```

### GET /workflows/{id}

Get workflow information.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/workflows/workflow_123 \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "workflow_123",
    "definition_id": "workflow_def_123",
    "entity_type": "provider",
    "entity_id": "provider_123",
    "status": "in_progress",
    "current_step_key": "document_upload",
    "steps": [
      {
        "id": "step_123",
        "step_key": "registration",
        "title": "Basic Registration",
        "status": "completed",
        "completed_at": "2025-11-20T10:00:00Z"
      },
      {
        "id": "step_456",
        "step_key": "document_upload",
        "title": "Document Upload",
        "status": "in_progress",
        "form_fields": [
          {
            "field_key": "id_proof",
            "field_type": "file",
            "label": "ID Proof",
            "is_required": true
          },
          {
            "field_key": "address_proof",
            "field_type": "file",
            "label": "Address Proof",
            "is_required": true
          }
        ]
      }
    ]
  }
}
```

### POST /workflows/{id}/steps/{stepKey}/submit

Submit data for a workflow step.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/workflows/workflow_123/steps/document_upload/submit \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "data": {
      "id_proof": "file_id_123",
      "address_proof": "file_id_456"
    }
  }'
```

**Request Body:**
```json
{
  "data": {
    "field_key": "field_value"
  }
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "message": "Step submitted successfully",
    "next_step_key": "background_check"
  }
}
```

### POST /workflows/{id}/actions/approve

Approve a workflow.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/workflows/workflow_123/actions/approve \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "comments": "All documents verified"
  }'
```

**Request Body:**
```json
{
  "comments": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "message": "Workflow approved successfully",
    "status": "approved"
  }
}
```

---

## 6. Providers Module

### POST /providers

Create a new provider.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/providers \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "provider_type": "pandit",
    "first_name": "Raj",
    "last_name": "Sharma",
    "email": "raj.sharma@example.com",
    "phone": "+1234567890"
  }'
```

**Request Body:**
```json
{
  "provider_type": "pandit|vendor|partner|astrologer|tutor",
  "first_name": "string",
  "last_name": "string",
  "email": "string",
  "phone": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "provider_123",
    "provider_type": "pandit",
    "first_name": "Raj",
    "last_name": "Sharma",
    "status": "pending",
    "created_at": "2025-11-20T10:00:00Z"
  }
}
```

### POST /providers/onboarding

Initiate provider onboarding workflow.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/providers/onboarding \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "provider_id": "provider_123",
    "workflow_definition_id": "pandit_onboarding_def"
  }'
```

**Request Body:**
```json
{
  "provider_id": "string",
  "workflow_definition_id": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "workflow_id": "workflow_123",
    "message": "Onboarding workflow initiated"
  }
}
```

### GET /providers/{id}

Get provider information.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/providers/provider_123 \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "provider_123",
    "provider_type": "pandit",
    "first_name": "Raj",
    "last_name": "Sharma",
    "status": "active",
    "services": [
      {
        "id": "service_123",
        "service_name": "Wedding Ceremony",
        "price": 5000.00,
        "currency": "INR"
      }
    ],
    "availability": [
      {
        "day_of_week": "saturday",
        "start_time": "09:00:00",
        "end_time": "17:00:00"
      }
    ]
  }
}
```

### POST /providers/{id}/documents

Upload provider document.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/providers/provider_123/documents \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "document_type": "id_proof",
    "file_id": "file_123"
  }'
```

**Request Body:**
```json
{
  "document_type": "string",
  "file_id": "string"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "doc_123",
    "document_type": "id_proof",
    "status": "pending"
  }
}
```

---

## 7. Bookings Module

### POST /bookings

Create a new booking.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/bookings \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "provider_id": "provider_123",
    "service_id": "service_123",
    "scheduled_at": "2025-11-25T10:00:00Z",
    "metadata": {
      "special_requests": "Please arrive 15 minutes early"
    }
  }'
```

**Request Body:**
```json
{
  "provider_id": "string",
  "service_id": "string",
  "scheduled_at": "2025-11-25T10:00:00Z",
  "metadata": {
    "key": "value"
  }
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "booking_id": "booking_xyz",
    "booking_reference": "BK-20251125-001",
    "status": "processing",
    "message": "Booking received. Confirmation will be sent."
  }
}
```

### GET /bookings/{id}/status

Get booking status.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/bookings/booking_xyz/status \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "booking_id": "booking_xyz",
    "booking_reference": "BK-20251125-001",
    "status": "confirmed",
    "confirmed_at": "2025-11-20T10:05:00Z",
    "provider": {
      "id": "provider_123",
      "name": "Raj Sharma"
    }
  }
}
```

### PUT /bookings/{id}/cancel

Cancel a booking.

**Request:**
```bash
curl -X PUT https://api.upflame.in/v1/bookings/booking_xyz/cancel \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "booking_id": "booking_xyz",
    "status": "cancelled",
    "message": "Booking has been cancelled successfully"
  }
}
```

---

## 8. Notifications Module

### POST /notifications/send

Send a notification.

**Request:**
```bash
curl -X POST https://api.upflame.in/v1/notifications/send \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "recipient_user_id": "user_123",
    "template_name": "booking_confirmation",
    "channel": "email",
    "variables": {
      "booking_reference": "BK-20251125-001",
      "service_name": "Wedding Ceremony",
      "provider_name": "Raj Sharma",
      "scheduled_time": "2025-11-25T10:00:00Z"
    }
  }'
```

**Request Body:**
```json
{
  "recipient_user_id": "string",
  "template_name": "string",
  "channel": "email|sms|push",
  "variables": {
    "key": "value"
  }
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "notification_id": "notif_123",
    "status": "pending",
    "message": "Notification queued for delivery"
  }
}
```

### GET /notifications/{id}

Get notification information.

**Request:**
```bash
curl -X GET https://api.upflame.in/v1/notifications/notif_123 \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": "notif_123",
    "recipient": "user@example.com",
    "channel": "email",
    "subject": "Booking Confirmation - BK-20251125-001",
    "status": "delivered",
    "sent_at": "2025-11-20T10:05:00Z",
    "delivered_at": "2025-11-20T10:05:05Z"
  }
}
```

### PUT /notifications/preferences

Update notification preferences.

**Request:**
```bash
curl -X PUT https://api.upflame.in/v1/notifications/preferences \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "notification_type": "booking_updates",
    "channel": "sms",
    "is_enabled": false
  }'
```

**Request Body:**
```json
{
  "notification_type": "string",
  "channel": "email|sms|push",
  "is_enabled": "boolean"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "message": "Notification preferences updated successfully"
  }
}
```

---

## Security Headers

All API responses include the following security headers:

```
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
Content-Security-Policy: default-src 'self'
X-XSS-Protection: 1; mode=block
```

## Data Validation

All APIs implement comprehensive input validation:
1. Type checking for all fields
2. Format validation for emails, phones, dates
3. Length limits for all string fields
4. Range validation for numeric fields
5. JSON schema validation for complex objects

## Idempotency

All create operations support idempotency through the `Idempotency-Key` header:

```
Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000
```

## Versioning

APIs are versioned through the URL path:
- Current version: v1
- Future versions: v2, v3, etc.

## Pagination

List endpoints support pagination through query parameters:

```
?page=1&limit=20
```

Response includes pagination metadata:

```json
{
  "status": "success",
  "data": [...],
  "meta": {
    "page": 1,
    "limit": 20,
    "total": 100,
    "total_pages": 5
  }
}
```

This comprehensive API documentation provides all the information needed to integrate with the enterprise-grade multi-tenant backend platform.