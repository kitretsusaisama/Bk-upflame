# Enterprise-Grade Multi-Tenant Backend Platform Implementation Plan

## Executive Summary

This document outlines a comprehensive implementation plan for building a production-ready, enterprise-grade backend platform with full multi-tenancy support. The platform follows MNC engineering principles with Domain-Driven Design (DDD), Zero-Trust Security, and a modular monolith architecture using Laravel.

## Technology Stack

- **Backend Framework**: Laravel (Modular Monolith Pattern)
- **Authentication**: Custom OIDC Provider
- **Database**: MySQL with row-level tenant isolation
- **Caching**: Redis (optional for Phase-1)
- **Storage**: S3 / DigitalOcean Spaces
- **CI/CD**: GitHub Actions
- **Monitoring**: Sentry + Grafana
- **Standards**: OAuth2, OIDC, OWASP, GDPR-compliant flows

## Implementation Sequence (Phase-1 Build Order)

1. Foundation (Infrastructure, DB schema, Tenant tables)
2. Identity Module (OIDC/OAuth2 server, Token service)
3. Tenant Security (Role families, Scopes & claims)
4. Access Control Module (RBAC engine, ABAC system)
5. Workflow Engine Module (Core definitions, Execution engine)
6. Provider Module (Base model, Onboarding)
7. Customer/User Module (End user onboarding)
8. Booking Core Module (Optimistic booking)
9. Hardening (Rate limits, Security, CI/CD)

---

## 1. Identity & SSO Module

### Architecture & Design Patterns
- **OAuth2/OIDC Authorization Server** compliant with industry standards
- **JWT-based token management** with secure signing and rotation
- **MFA Implementation** using TOTP (Time-based One-Time Password)
- **Device Trust Sessions** for enhanced security
- **Email/Phone Verification Engine** for user validation

### Database Schema (MySQL)

```sql
-- Identity & SSO Tables
CREATE TABLE tenants (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    domain VARCHAR(255) UNIQUE,
    config_json JSON,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive', 'banned', 'hold', 'pending', 'suspended') DEFAULT 'pending',
    primary_role_id CHAR(36),
    mfa_enabled BOOLEAN DEFAULT FALSE,
    email_verified BOOLEAN DEFAULT FALSE,
    phone_verified BOOLEAN DEFAULT FALSE,
    last_login TIMESTAMP NULL,
    failed_login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_email_per_tenant (tenant_id, email)
);

CREATE TABLE user_sessions (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    device_id VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent TEXT,
    refresh_token_hash VARCHAR(255),
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE oauth_clients (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    redirect_uris JSON,
    scopes JSON,
    secret_hash VARCHAR(255),
    is_confidential BOOLEAN DEFAULT TRUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE oauth_authorization_codes (
    id CHAR(36) PRIMARY KEY,
    client_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    code_hash VARCHAR(255) NOT NULL,
    redirect_uri TEXT,
    scopes JSON,
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES oauth_clients(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE oauth_access_tokens (
    id CHAR(36) PRIMARY KEY,
    client_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    scopes JSON,
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    revoked BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (client_id) REFERENCES oauth_clients(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE mfa_methods (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    type ENUM('totp', 'sms', 'email', 'backup_codes') NOT NULL,
    secret_encrypted TEXT,
    is_primary BOOLEAN DEFAULT FALSE,
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### API Endpoints (/api/v1/auth)

```
POST /api/v1/auth/login
{
  "email": "user@example.com",
  "password": "password123",
  "mfa_code": "123456"  // Optional if MFA enabled
}

Response:
{
  "status": "success",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 900,
    "token_type": "Bearer",
    "refresh_token": "dGhpcyBpcyBhIHJlZnJlc2ggdG9rZW4..."
  }
}

POST /api/v1/auth/token
{
  "grant_type": "refresh_token",
  "refresh_token": "dGhpcyBpcyBhIHJlZnJlc2ggdG9rZW4...",
  "client_id": "client123",
  "client_secret": "secret123"
}

Response:
{
  "status": "success",
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 900,
    "token_type": "Bearer"
  }
}

POST /api/v1/auth/logout
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
{
  "status": "success",
  "data": {
    "message": "Successfully logged out"
  }
}

GET /api/v1/auth/me
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

POST /api/v1/auth/mfa/setup
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "type": "totp"
}

Response:
{
  "status": "success",
  "data": {
    "type": "totp",
    "secret": "JBSWY3DPEHPK3PXP",
    "qr_code_url": "otpauth://totp/Example:user@example.com?secret=JBSWY3DPEHPK3PXP&issuer=Example"
  }
}

POST /api/v1/auth/mfa/verify
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "type": "totp",
  "code": "123456"
}

Response:
{
  "status": "success",
  "data": {
    "message": "MFA method verified successfully"
  }
}
```

### Security Implementation

- **User Status Management**:
  - Active: Normal user operations
  - Inactive: Temporarily disabled by user
  - Banned: Blocked by admin for policy violations
  - Hold: Temporarily suspended for verification
  - Pending: Awaiting email/phone verification
  - Suspended: Temporarily suspended by admin

- **Rate Limiting**: Brute force protection with exponential backoff
- **Password Security**: bcrypt/argon2 hashing with strong policies
- **Token Management**: Short-lived JWT with refresh token rotation
- **Session Security**: Secure, HttpOnly, SameSite=Strict cookies

---

## 2. Tenant Management Module

### Architecture & Design Patterns
- **Row-level Isolation** using tenant_id on all business tables
- **Tenant Resolution Hierarchy**: Host header → JWT claim → Query param
- **Domain Mapping Engine** for custom tenant domains
- **Configuration Management** with JSON-based tenant settings

### Database Schema (MySQL)

```sql
-- Tenant Management Tables
CREATE TABLE tenant_domains (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    domain VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    is_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255),
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_domain (domain)
);

CREATE TABLE tenant_configs (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    branding_config JSON,
    security_config JSON,
    feature_flags JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);

CREATE TABLE tenant_modules (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    module_name VARCHAR(100) NOT NULL,
    is_enabled BOOLEAN DEFAULT TRUE,
    config JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);
```

### API Endpoints (/api/v1/tenants)

```
POST /api/v1/tenants (admin only)
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "name": "Acme Corporation",
  "domain": "acme.example.com",
  "admin_email": "admin@acme.example.com",
  "admin_password": "securePassword123"
}

Response:
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

GET /api/v1/tenants/{id}
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

PUT /api/v1/tenants/{id}/domains
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "domain": "auth.acme.example.com",
  "is_primary": false
}

Response:
{
  "status": "success",
  "data": {
    "message": "Domain added successfully"
  }
}
```

---

## 3. User & Role Module

### Architecture & Design Patterns
- **Role Families** approach for extensibility
- **Multi-role Support** allowing users to have multiple roles
- **Cross-tenant Roles** with proper isolation
- **User Profile Management** with status tracking

### Database Schema (MySQL)

```sql
-- User & Role Tables
CREATE TABLE roles (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    role_family ENUM('Provider', 'Internal', 'Customer') NOT NULL,
    is_system BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_role_per_tenant (tenant_id, name)
);

CREATE TABLE permissions (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    resource VARCHAR(100) NOT NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_permission (name)
);

CREATE TABLE role_permissions (
    role_id CHAR(36) NOT NULL,
    permission_id CHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

CREATE TABLE user_roles (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    role_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    assigned_by CHAR(36),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id)
);

CREATE TABLE user_profiles (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(20),
    avatar_url VARCHAR(500),
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);
```

### API Endpoints (/api/v1/users & /api/v1/roles)

```
POST /api/v1/users
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "email": "john.doe@acme.example.com",
  "password": "securePassword123",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+1234567890"
}

Response:
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

GET /api/v1/users/{id}
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

POST /api/v1/users/{id}/roles
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "role_id": "role123"
}

Response:
{
  "status": "success",
  "data": {
    "message": "Role assigned successfully"
  }
}

GET /api/v1/roles
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

POST /api/v1/roles
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "name": "PremiumCustomer",
  "role_family": "Customer",
  "description": "Premium customer with additional privileges",
  "permissions": ["booking.create", "booking.update"]
}

Response:
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

## 4. RBAC + ABAC Authorization Module

### Architecture & Design Patterns
- **Hybrid RBAC + ABAC Model** for fine-grained access control
- **Policy Engine** with JSON-based policy definitions
- **Runtime Policy Evaluation** with attribute-based conditions
- **Enterprise-grade IAM** similar to AWS IAM or Google IAM

### Database Schema (MySQL)

```sql
-- RBAC + ABAC Tables
CREATE TABLE policies (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    target_json JSON,
    rules_json JSON,
    is_enabled BOOLEAN DEFAULT TRUE,
    priority INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE policy_assignments (
    id CHAR(36) PRIMARY KEY,
    policy_id CHAR(36) NOT NULL,
    assignee_type ENUM('user', 'role', 'group') NOT NULL,
    assignee_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (policy_id) REFERENCES policies(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE access_logs (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    resource VARCHAR(255) NOT NULL,
    action VARCHAR(50) NOT NULL,
    decision ENUM('allow', 'deny') NOT NULL,
    policy_id CHAR(36),
    context JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (policy_id) REFERENCES policies(id)
);
```

### API Endpoints (/api/v1/permissions & /api/v1/policies)

```
POST /api/v1/permissions
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "name": "booking.cancel",
  "resource": "booking",
  "action": "cancel",
  "description": "Cancel a booking"
}

Response:
{
  "status": "success",
  "data": {
    "id": "perm123",
    "name": "booking.cancel",
    "resource": "booking",
    "action": "cancel"
  }
}

POST /api/v1/policies
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
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
}

Response:
{
  "status": "success",
  "data": {
    "id": "policy123",
    "name": "PremiumCustomerBookingPolicy",
    "is_enabled": true
  }
}

GET /api/v1/policies
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

## 5. Universal Workflow Engine Module

### Architecture & Design Patterns
- **Generic Workflow Engine** supporting any role family
- **State Machine** with defined transitions
- **Dynamic Form Definitions** for workflow steps
- **Audit Trail** for all workflow actions
- **Extensible Step Types** (Form, Document, Verification, etc.)

### Database Schema (MySQL)

```sql
-- Workflow Engine Tables
CREATE TABLE workflow_definitions (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    role_family ENUM('Provider', 'Internal', 'Customer') NOT NULL,
    steps_json JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE workflows (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    definition_id CHAR(36) NOT NULL,
    entity_type VARCHAR(100) NOT NULL,
    entity_id CHAR(36) NOT NULL,
    current_step_key VARCHAR(100),
    status ENUM('pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected') DEFAULT 'pending',
    context_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (definition_id) REFERENCES workflow_definitions(id)
);

CREATE TABLE workflow_steps (
    id CHAR(36) PRIMARY KEY,
    workflow_id CHAR(36) NOT NULL,
    step_key VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    step_type ENUM('form', 'document', 'verification', 'background_check', 'admin_approval', 'auto') NOT NULL,
    config_json JSON,
    data_json JSON,
    status ENUM('pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected') DEFAULT 'pending',
    assigned_to CHAR(36),  -- user_id for steps requiring assignment
    attempted_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (workflow_id) REFERENCES workflows(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

CREATE TABLE workflow_events (
    id CHAR(36) PRIMARY KEY,
    workflow_id CHAR(36) NOT NULL,
    step_id CHAR(36),
    event_type VARCHAR(100) NOT NULL,
    payload_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (workflow_id) REFERENCES workflows(id) ON DELETE CASCADE,
    FOREIGN KEY (step_id) REFERENCES workflow_steps(id)
);

CREATE TABLE workflow_forms (
    id CHAR(36) PRIMARY KEY,
    step_id CHAR(36) NOT NULL,
    field_key VARCHAR(100) NOT NULL,
    field_type ENUM('text', 'email', 'phone', 'number', 'date', 'select', 'checkbox', 'file') NOT NULL,
    label VARCHAR(255) NOT NULL,
    placeholder VARCHAR(255),
    is_required BOOLEAN DEFAULT FALSE,
    validation_rules JSON,
    options_json JSON,  -- For select fields
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (step_id) REFERENCES workflow_steps(id) ON DELETE CASCADE
);
```

### API Endpoints (/api/v1/workflows)

```
POST /api/v1/workflows
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "definition_id": "workflow_def_123",
  "entity_type": "provider",
  "entity_id": "provider_123"
}

Response:
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

GET /api/v1/workflows/{id}
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

POST /api/v1/workflows/{id}/steps/{stepKey}/submit
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "data": {
    "id_proof": "file_id_123",
    "address_proof": "file_id_456"
  }
}

Response:
{
  "status": "success",
  "data": {
    "message": "Step submitted successfully",
    "next_step_key": "background_check"
  }
}

POST /api/v1/workflows/{id}/actions/approve
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "comments": "All documents verified"
}

Response:
{
  "status": "success",
  "data": {
    "message": "Workflow approved successfully",
    "status": "approved"
  }
}
```

---

## 6. Provider Management Module

### Architecture & Design Patterns
- **Role Family Implementation** for Provider types
- **Workflow Integration** for onboarding processes
- **Document Management** for KYC requirements
- **Service Catalog Integration** for provider offerings

### Database Schema (MySQL)

```sql
-- Provider Management Tables
CREATE TABLE providers (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    provider_type ENUM('pandit', 'vendor', 'partner', 'astrologer', 'tutor') NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255),
    phone VARCHAR(20),
    status ENUM('pending', 'verified', 'active', 'suspended', 'rejected') DEFAULT 'pending',
    workflow_id CHAR(36),
    profile_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (workflow_id) REFERENCES workflows(id)
);

CREATE TABLE provider_documents (
    id CHAR(36) PRIMARY KEY,
    provider_id CHAR(36) NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    file_id VARCHAR(255) NOT NULL,
    file_url VARCHAR(500),
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verified_by CHAR(36),
    verified_at TIMESTAMP NULL,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id)
);

CREATE TABLE provider_services (
    id CHAR(36) PRIMARY KEY,
    provider_id CHAR(36) NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'INR',
    duration_minutes INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
);

CREATE TABLE provider_availability (
    id CHAR(36) PRIMARY KEY,
    provider_id CHAR(36) NOT NULL,
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
);
```

### API Endpoints (/api/v1/providers)

```
POST /api/v1/providers
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "provider_type": "pandit",
  "first_name": "Raj",
  "last_name": "Sharma",
  "email": "raj.sharma@example.com",
  "phone": "+1234567890"
}

Response:
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

POST /api/v1/providers/onboarding
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "provider_id": "provider_123",
  "workflow_definition_id": "pandit_onboarding_def"
}

Response:
{
  "status": "success",
  "data": {
    "workflow_id": "workflow_123",
    "message": "Onboarding workflow initiated"
  }
}

GET /api/v1/providers/{id}
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

POST /api/v1/providers/{id}/documents
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "document_type": "id_proof",
  "file_id": "file_123"
}

Response:
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

## 7. Booking Engine Foundation

### Architecture & Design Patterns
- **Optimistic Booking Creation** for fast user experience
- **Status Tracking** with clear state transitions
- **Provider Assignment** (Phase-2 expansion)
- **Multi-service Booking Patterns** support

### Database Schema (MySQL)

```sql
-- Booking Engine Tables
CREATE TABLE bookings (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    provider_id CHAR(36),
    service_id CHAR(36),
    booking_reference VARCHAR(100) UNIQUE,
    status ENUM('processing', 'confirmed', 'completed', 'cancelled', 'rejected') DEFAULT 'processing',
    scheduled_at TIMESTAMP NOT NULL,
    duration_minutes INT,
    amount DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'INR',
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (provider_id) REFERENCES providers(id)
);

CREATE TABLE booking_status_history (
    id CHAR(36) PRIMARY KEY,
    booking_id CHAR(36) NOT NULL,
    status ENUM('processing', 'confirmed', 'completed', 'cancelled', 'rejected') NOT NULL,
    changed_by CHAR(36),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id)
);

CREATE TABLE services (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    base_price DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'INR',
    duration_minutes INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);
```

### API Endpoints (/api/v1/bookings)

```
POST /api/v1/bookings
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "provider_id": "provider_123",
  "service_id": "service_123",
  "scheduled_at": "2025-11-25T10:00:00Z",
  "metadata": {
    "special_requests": "Please arrive 15 minutes early"
  }
}

Response:
{
  "status": "success",
  "data": {
    "booking_id": "booking_xyz",
    "booking_reference": "BK-20251125-001",
    "status": "processing",
    "message": "Booking received. Confirmation will be sent."
  }
}

GET /api/v1/bookings/{id}/status
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

PUT /api/v1/bookings/{id}/cancel
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

## 8. Notification & Communication Module

### Architecture & Design Patterns
- **Multi-channel Communication** (Email, SMS, Push)
- **Template-based Messaging** with domain customization
- **Workflow Integration** for automated triggers
- **Delivery Tracking** with status reporting

### Database Schema (MySQL)

```sql
-- Notification & Communication Tables
CREATE TABLE notification_templates (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    channel ENUM('email', 'sms', 'push') NOT NULL,
    subject VARCHAR(255),
    body TEXT NOT NULL,
    variables_json JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    template_id CHAR(36),
    recipient_user_id CHAR(36),
    recipient_email VARCHAR(255),
    recipient_phone VARCHAR(20),
    channel ENUM('email', 'sms', 'push') NOT NULL,
    subject VARCHAR(255),
    body TEXT NOT NULL,
    status ENUM('pending', 'sent', 'failed', 'delivered', 'opened') DEFAULT 'pending',
    priority ENUM('low', 'normal', 'high') DEFAULT 'normal',
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    failure_reason TEXT,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (template_id) REFERENCES notification_templates(id),
    FOREIGN KEY (recipient_user_id) REFERENCES users(id)
);

CREATE TABLE notification_preferences (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    tenant_id CHAR(36) NOT NULL,
    notification_type VARCHAR(100) NOT NULL,
    channel ENUM('email', 'sms', 'push') NOT NULL,
    is_enabled BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_preference (user_id, notification_type, channel)
);
```

### API Endpoints (/api/v1/notifications)

```
POST /api/v1/notifications/send
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "recipient_user_id": "user_123",
  "template_name": "booking_confirmation",
  "channel": "email",
  "variables": {
    "booking_reference": "BK-20251125-001",
    "service_name": "Wedding Ceremony",
    "provider_name": "Raj Sharma",
    "scheduled_time": "2025-11-25T10:00:00Z"
  }
}

Response:
{
  "status": "success",
  "data": {
    "notification_id": "notif_123",
    "status": "pending",
    "message": "Notification queued for delivery"
  }
}

GET /api/v1/notifications/{id}
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

Response:
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

PUT /api/v1/notifications/preferences
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
{
  "notification_type": "booking_updates",
  "channel": "sms",
  "is_enabled": false
}

Response:
{
  "status": "success",
  "data": {
    "message": "Notification preferences updated successfully"
  }
}
```

---

## Essential Backend Features

### Complete CMS Functionality
- Content management for tenant-specific pages
- Template engine for customizable UI elements
- Media library for asset management
- Version control for content changes

### Advanced Tracking and Telemetry Systems
- User behavior analytics
- System performance monitoring
- Error tracking and reporting
- Business metrics dashboard

### Device Verification Mechanisms
- Device fingerprinting
- Trusted device management
- Session continuity across devices
- Device-based access controls

### Comprehensive Audit Logging
- Immutable audit trails for all critical operations
- User activity tracking
- System configuration changes
- Security event logging

### Rate Limiting and Security Hardening
- Adaptive rate limiting per IP, user, and tenant
- Input validation and sanitization
- SQL injection prevention
- Cross-site scripting (XSS) protection
- Cross-site request forgery (CSRF) protection

### Deployment Strategies and CI/CD Pipeline Setup
- GitHub Actions for automated testing and deployment
- Environment-specific configurations
- Database migration management
- Rollback procedures

---

## Security Compliance Checklist

### OWASP Compliance
- [ ] Input validation on all endpoints
- [ ] Output encoding to prevent XSS
- [ ] Secure session management
- [ ] Authentication and access control
- [ ] Cryptographic controls
- [ ] Error handling and logging
- [ ] Data protection
- [ ] Communication security
- [ ] HTTP security headers
- [ ] File and resources handling

### GDPR Compliance
- [ ] Data minimization principles
- [ ] Purpose limitation
- [ ] Data subject rights implementation
- [ ] Privacy by design
- [ ] Data protection impact assessments
- [ ] Data breach notification procedures
- [ ] Consent management
- [ ] Data retention policies
- [ ] International data transfers

---

## Monitoring and Observability Setup

### Sentry Integration
- Error tracking for backend exceptions
- Performance monitoring for API endpoints
- Release tracking and deployment correlation
- User feedback collection

### Grafana Integration
- System metrics dashboards
- Database performance monitoring
- API response time tracking
- Tenant resource utilization
- Custom business metrics

---

## Final Implementation Notes

This implementation plan provides a comprehensive foundation for building an enterprise-grade, multi-tenant backend platform. The modular monolith approach using Laravel ensures maintainability while following DDD principles for clear separation of concerns.

Key architectural decisions:
1. **Zero-trust security model** implemented throughout all modules
2. **Row-level tenant isolation** for data security
3. **Extensible role family architecture** for future growth
4. **Universal workflow engine** for onboarding any role type
5. **Hybrid RBAC + ABAC authorization** for fine-grained access control
6. **OIDC/OAuth2 compliant identity management** for SSO capabilities

The platform is designed to support:
- Multiple apps (admin panel, customer panel, partner app, etc.)
- Multiple role families with customizable workflows
- Multiple tenants with domain-based isolation
- SSO-based access across all tenants
- Scalability to handle lakhs of users

This foundation prepares the system for future microservice extraction of critical modules (identity, workflow, booking) in Phase-2 while providing immediate business value in Phase-1.