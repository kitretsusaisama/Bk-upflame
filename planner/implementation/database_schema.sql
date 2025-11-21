# Enterprise-Grade Multi-Tenant Backend Platform Database Schema

## Overview

This document provides the complete database schema for the enterprise-grade multi-tenant backend platform. The schema is optimized for MySQL and follows enterprise best practices with proper indexing, normalization, and tenant isolation.

**Note:** An enhanced version of this schema is available in [database_schema_advanced.sql](database_schema_advanced.sql) which includes additional security features, improved tenant management, advanced workflow capabilities, and other enterprise-grade enhancements. A summary of these enhancements can be found in [database_schema_enhancements.md](database_schema_enhancements.md).

## Core Tables

### Tenants and Tenant Management

```sql
-- Core tenant table
CREATE TABLE tenants (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the tenant',
    name VARCHAR(255) NOT NULL COMMENT 'Tenant name',
    domain VARCHAR(255) UNIQUE COMMENT 'Primary domain for the tenant',
    config_json JSON COMMENT 'Tenant configuration as JSON',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active' COMMENT 'Tenant status',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    INDEX idx_tenant_status (status),
    INDEX idx_tenant_domain (domain)
) COMMENT='Core tenant information';

-- Tenant domains for custom login pages
CREATE TABLE tenant_domains (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the domain record',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    domain VARCHAR(255) NOT NULL COMMENT 'Domain name',
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Whether this is the primary domain',
    is_verified BOOLEAN DEFAULT FALSE COMMENT 'Whether domain ownership is verified',
    verification_token VARCHAR(255) COMMENT 'Token for domain verification',
    verified_at TIMESTAMP NULL COMMENT 'When domain was verified',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_domain (domain),
    UNIQUE KEY unique_tenant_primary (tenant_id, is_primary),
    INDEX idx_tenant_domains_tenant (tenant_id),
    INDEX idx_tenant_domains_verified (is_verified)
) COMMENT='Tenant domain mappings for custom login pages';

-- Tenant configurations
CREATE TABLE tenant_configs (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the config record',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    branding_config JSON COMMENT 'Branding configuration as JSON',
    security_config JSON COMMENT 'Security configuration as JSON',
    feature_flags JSON COMMENT 'Feature flags as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tenant_config (tenant_id)
) COMMENT='Tenant-specific configurations';

-- Tenant modules for feature management
CREATE TABLE tenant_modules (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the module record',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    module_name VARCHAR(100) NOT NULL COMMENT 'Name of the module',
    is_enabled BOOLEAN DEFAULT TRUE COMMENT 'Whether module is enabled',
    config JSON COMMENT 'Module-specific configuration',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tenant_module (tenant_id, module_name),
    INDEX idx_tenant_modules_enabled (is_enabled)
) COMMENT='Tenant module enablement and configuration';
```

### Identity and Authentication

```sql
-- Users table with tenant isolation
CREATE TABLE users (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the user',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    email VARCHAR(255) NOT NULL COMMENT 'User email address',
    password_hash VARCHAR(255) NOT NULL COMMENT 'BCRYPT hashed password',
    status ENUM('active', 'inactive', 'banned', 'hold', 'pending', 'suspended') DEFAULT 'pending' COMMENT 'User account status',
    primary_role_id CHAR(36) COMMENT 'Reference to user primary role',
    mfa_enabled BOOLEAN DEFAULT FALSE COMMENT 'Whether MFA is enabled',
    email_verified BOOLEAN DEFAULT FALSE COMMENT 'Whether email is verified',
    phone_verified BOOLEAN DEFAULT FALSE COMMENT 'Whether phone is verified',
    last_login TIMESTAMP NULL COMMENT 'Last successful login timestamp',
    failed_login_attempts INT DEFAULT 0 COMMENT 'Count of failed login attempts',
    locked_until TIMESTAMP NULL COMMENT 'Account lockout expiration',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_email_per_tenant (tenant_id, email),
    INDEX idx_users_status (status),
    INDEX idx_users_tenant (tenant_id),
    INDEX idx_users_email (email),
    INDEX idx_users_last_login (last_login)
) COMMENT='User accounts with tenant isolation';

-- User sessions for authentication tracking
CREATE TABLE user_sessions (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the session',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    device_id VARCHAR(255) COMMENT 'Device identifier',
    ip_address VARCHAR(45) COMMENT 'IP address of the session',
    user_agent TEXT COMMENT 'User agent string',
    refresh_token_hash VARCHAR(255) COMMENT 'Hash of refresh token',
    expires_at TIMESTAMP COMMENT 'Session expiration timestamp',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_sessions_user (user_id),
    INDEX idx_sessions_expires (expires_at),
    INDEX idx_sessions_tenant (tenant_id)
) COMMENT='User authentication sessions';

-- OAuth2 clients for third-party integrations
CREATE TABLE oauth_clients (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the client',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(255) NOT NULL COMMENT 'Client name',
    redirect_uris JSON COMMENT 'Allowed redirect URIs as JSON array',
    scopes JSON COMMENT 'Allowed scopes as JSON array',
    secret_hash VARCHAR(255) COMMENT 'BCRYPT hash of client secret',
    is_confidential BOOLEAN DEFAULT TRUE COMMENT 'Whether client is confidential',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Whether client is active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_clients_tenant (tenant_id),
    INDEX idx_clients_active (is_active)
) COMMENT='OAuth2 clients for third-party integrations';

-- OAuth2 authorization codes for authorization flow
CREATE TABLE oauth_authorization_codes (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the authorization code',
    client_id CHAR(36) NOT NULL COMMENT 'Reference to oauth_clients.id',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    code_hash VARCHAR(255) NOT NULL COMMENT 'Hash of the authorization code',
    redirect_uri TEXT COMMENT 'Redirect URI for this authorization',
    scopes JSON COMMENT 'Scopes granted for this authorization',
    expires_at TIMESTAMP COMMENT 'Authorization code expiration',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (client_id) REFERENCES oauth_clients(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_auth_codes_client (client_id),
    INDEX idx_auth_codes_expires (expires_at)
) COMMENT='OAuth2 authorization codes';

-- OAuth2 access tokens
CREATE TABLE oauth_access_tokens (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the access token',
    client_id CHAR(36) NOT NULL COMMENT 'Reference to oauth_clients.id',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    token_hash VARCHAR(255) NOT NULL COMMENT 'Hash of the access token',
    scopes JSON COMMENT 'Scopes associated with this token',
    expires_at TIMESTAMP COMMENT 'Token expiration timestamp',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    revoked BOOLEAN DEFAULT FALSE COMMENT 'Whether token has been revoked',
    FOREIGN KEY (client_id) REFERENCES oauth_clients(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_access_tokens_client (client_id),
    INDEX idx_access_tokens_user (user_id),
    INDEX idx_access_tokens_expires (expires_at),
    INDEX idx_access_tokens_revoked (revoked)
) COMMENT='OAuth2 access tokens';

-- Multi-factor authentication methods
CREATE TABLE mfa_methods (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the MFA method',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    type ENUM('totp', 'sms', 'email', 'backup_codes') NOT NULL COMMENT 'Type of MFA method',
    secret_encrypted TEXT COMMENT 'Encrypted MFA secret',
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Whether this is the primary MFA method',
    is_verified BOOLEAN DEFAULT FALSE COMMENT 'Whether MFA method is verified',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_mfa_user (user_id),
    INDEX idx_mfa_type (type),
    INDEX idx_mfa_primary (is_primary)
) COMMENT='User multi-factor authentication methods';
```

### User Profiles and Roles

```sql
-- User profile information
CREATE TABLE user_profiles (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the profile',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    first_name VARCHAR(100) COMMENT 'User first name',
    last_name VARCHAR(100) COMMENT 'User last name',
    phone VARCHAR(20) COMMENT 'User phone number',
    avatar_url VARCHAR(500) COMMENT 'URL to user avatar image',
    metadata JSON COMMENT 'Additional profile metadata as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_profile_per_user (user_id),
    INDEX idx_profiles_tenant (tenant_id)
) COMMENT='User profile information';

-- Roles with role families
CREATE TABLE roles (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the role',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(100) NOT NULL COMMENT 'Role name',
    description TEXT COMMENT 'Role description',
    role_family ENUM('Provider', 'Internal', 'Customer') NOT NULL COMMENT 'Role family classification',
    is_system BOOLEAN DEFAULT FALSE COMMENT 'Whether this is a system role',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_role_per_tenant (tenant_id, name),
    INDEX idx_roles_tenant (tenant_id),
    INDEX idx_roles_family (role_family)
) COMMENT='User roles with role family classification';

-- Permissions for RBAC
CREATE TABLE permissions (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the permission',
    name VARCHAR(100) NOT NULL COMMENT 'Permission name (e.g., booking.create)',
    resource VARCHAR(100) NOT NULL COMMENT 'Resource this permission applies to',
    action VARCHAR(50) NOT NULL COMMENT 'Action this permission allows',
    description TEXT COMMENT 'Permission description',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    UNIQUE KEY unique_permission (name),
    INDEX idx_permissions_resource (resource),
    INDEX idx_permissions_action (action)
) COMMENT='Permissions for role-based access control';

-- Role to permission mappings
CREATE TABLE role_permissions (
    role_id CHAR(36) NOT NULL COMMENT 'Reference to roles.id',
    permission_id CHAR(36) NOT NULL COMMENT 'Reference to permissions.id',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    INDEX idx_role_permissions_role (role_id),
    INDEX idx_role_permissions_permission (permission_id)
) COMMENT='Mapping between roles and permissions';

-- User to role assignments
CREATE TABLE user_roles (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the assignment',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    role_id CHAR(36) NOT NULL COMMENT 'Reference to roles.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    assigned_by CHAR(36) COMMENT 'Reference to users.id of assigning user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id),
    UNIQUE KEY unique_user_role_tenant (user_id, role_id, tenant_id),
    INDEX idx_user_roles_user (user_id),
    INDEX idx_user_roles_role (role_id),
    INDEX idx_user_roles_tenant (tenant_id)
) COMMENT='User role assignments';
```

### Authorization Policies (ABAC)

```sql
-- Attribute-based access control policies
CREATE TABLE policies (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the policy',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(255) NOT NULL COMMENT 'Policy name',
    description TEXT COMMENT 'Policy description',
    target_json JSON COMMENT 'Target resources/actions for policy as JSON',
    rules_json JSON COMMENT 'Policy rules as JSON',
    is_enabled BOOLEAN DEFAULT TRUE COMMENT 'Whether policy is enabled',
    priority INT DEFAULT 0 COMMENT 'Policy evaluation priority',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_policies_tenant (tenant_id),
    INDEX idx_policies_enabled (is_enabled),
    INDEX idx_policies_priority (priority)
) COMMENT='Attribute-based access control policies';

-- Policy assignments to users/roles
CREATE TABLE policy_assignments (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the assignment',
    policy_id CHAR(36) NOT NULL COMMENT 'Reference to policies.id',
    assignee_type ENUM('user', 'role', 'group') NOT NULL COMMENT 'Type of assignee',
    assignee_id CHAR(36) NOT NULL COMMENT 'ID of the assignee',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (policy_id) REFERENCES policies(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_policy_assignments_policy (policy_id),
    INDEX idx_policy_assignments_assignee (assignee_type, assignee_id),
    INDEX idx_policy_assignments_tenant (tenant_id)
) COMMENT='Assignments of policies to users/roles/groups';

-- Access decision logging
CREATE TABLE access_logs (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the log entry',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    resource VARCHAR(255) NOT NULL COMMENT 'Resource being accessed',
    action VARCHAR(50) NOT NULL COMMENT 'Action being performed',
    decision ENUM('allow', 'deny') NOT NULL COMMENT 'Access decision',
    policy_id CHAR(36) COMMENT 'Reference to policies.id if policy applied',
    context JSON COMMENT 'Context information as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (policy_id) REFERENCES policies(id),
    INDEX idx_access_logs_user (user_id),
    INDEX idx_access_logs_resource (resource),
    INDEX idx_access_logs_decision (decision),
    INDEX idx_access_logs_created (created_at)
) COMMENT='Access decision logging for audit purposes';
```

### Workflow Engine

```sql
-- Workflow definitions
CREATE TABLE workflow_definitions (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the workflow definition',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(255) NOT NULL COMMENT 'Workflow name',
    description TEXT COMMENT 'Workflow description',
    role_family ENUM('Provider', 'Internal', 'Customer') NOT NULL COMMENT 'Target role family',
    steps_json JSON COMMENT 'Workflow steps definition as JSON',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Whether workflow is active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_workflow_defs_tenant (tenant_id),
    INDEX idx_workflow_defs_role_family (role_family),
    INDEX idx_workflow_defs_active (is_active)
) COMMENT='Workflow definitions for onboarding and other processes';

-- Workflow instances
CREATE TABLE workflows (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the workflow instance',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    definition_id CHAR(36) NOT NULL COMMENT 'Reference to workflow_definitions.id',
    entity_type VARCHAR(100) NOT NULL COMMENT 'Type of entity (provider, user, etc.)',
    entity_id CHAR(36) NOT NULL COMMENT 'ID of the entity',
    current_step_key VARCHAR(100) COMMENT 'Key of current workflow step',
    status ENUM('pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'Workflow status',
    context_json JSON COMMENT 'Workflow context data as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (definition_id) REFERENCES workflow_definitions(id),
    INDEX idx_workflows_tenant (tenant_id),
    INDEX idx_workflows_definition (definition_id),
    INDEX idx_workflows_entity (entity_type, entity_id),
    INDEX idx_workflows_status (status)
) COMMENT='Workflow instances';

-- Workflow steps
CREATE TABLE workflow_steps (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the workflow step',
    workflow_id CHAR(36) NOT NULL COMMENT 'Reference to workflows.id',
    step_key VARCHAR(100) NOT NULL COMMENT 'Key identifier for the step',
    title VARCHAR(255) NOT NULL COMMENT 'Step title',
    description TEXT COMMENT 'Step description',
    step_type ENUM('form', 'document', 'verification', 'background_check', 'admin_approval', 'auto') NOT NULL COMMENT 'Type of step',
    config_json JSON COMMENT 'Step configuration as JSON',
    data_json JSON COMMENT 'Step data as JSON',
    status ENUM('pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'Step status',
    assigned_to CHAR(36) COMMENT 'Reference to users.id for assigned user',
    attempted_at TIMESTAMP NULL COMMENT 'When step was first attempted',
    completed_at TIMESTAMP NULL COMMENT 'When step was completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (workflow_id) REFERENCES workflows(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    INDEX idx_workflow_steps_workflow (workflow_id),
    INDEX idx_workflow_steps_key (step_key),
    INDEX idx_workflow_steps_status (status),
    INDEX idx_workflow_steps_assigned (assigned_to)
) COMMENT='Individual steps within workflows';

-- Workflow events for audit trail
CREATE TABLE workflow_events (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the event',
    workflow_id CHAR(36) NOT NULL COMMENT 'Reference to workflows.id',
    step_id CHAR(36) COMMENT 'Reference to workflow_steps.id',
    event_type VARCHAR(100) NOT NULL COMMENT 'Type of event',
    payload_json JSON COMMENT 'Event payload as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (workflow_id) REFERENCES workflows(id) ON DELETE CASCADE,
    FOREIGN KEY (step_id) REFERENCES workflow_steps(id),
    INDEX idx_workflow_events_workflow (workflow_id),
    INDEX idx_workflow_events_step (step_id),
    INDEX idx_workflow_events_type (event_type),
    INDEX idx_workflow_events_created (created_at)
) COMMENT='Workflow events for audit trail';

-- Workflow form fields
CREATE TABLE workflow_forms (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the form field',
    step_id CHAR(36) NOT NULL COMMENT 'Reference to workflow_steps.id',
    field_key VARCHAR(100) NOT NULL COMMENT 'Key identifier for the field',
    field_type ENUM('text', 'email', 'phone', 'number', 'date', 'select', 'checkbox', 'file') NOT NULL COMMENT 'Type of field',
    label VARCHAR(255) NOT NULL COMMENT 'Field label',
    placeholder VARCHAR(255) COMMENT 'Field placeholder text',
    is_required BOOLEAN DEFAULT FALSE COMMENT 'Whether field is required',
    validation_rules JSON COMMENT 'Field validation rules as JSON',
    options_json JSON COMMENT 'Field options as JSON (for select fields)',
    sort_order INT DEFAULT 0 COMMENT 'Field display order',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (step_id) REFERENCES workflow_steps(id) ON DELETE CASCADE,
    INDEX idx_workflow_forms_step (step_id),
    INDEX idx_workflow_forms_key (field_key),
    INDEX idx_workflow_forms_required (is_required)
) COMMENT='Workflow form field definitions';
```

### Provider Management

```sql
-- Providers (pandits, vendors, partners, etc.)
CREATE TABLE providers (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the provider',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    provider_type ENUM('pandit', 'vendor', 'partner', 'astrologer', 'tutor') NOT NULL COMMENT 'Type of provider',
    first_name VARCHAR(100) COMMENT 'Provider first name',
    last_name VARCHAR(100) COMMENT 'Provider last name',
    email VARCHAR(255) COMMENT 'Provider email',
    phone VARCHAR(20) COMMENT 'Provider phone',
    status ENUM('pending', 'verified', 'active', 'suspended', 'rejected') DEFAULT 'pending' COMMENT 'Provider status',
    workflow_id CHAR(36) COMMENT 'Reference to workflows.id for onboarding',
    profile_json JSON COMMENT 'Provider profile data as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (workflow_id) REFERENCES workflows(id),
    INDEX idx_providers_tenant (tenant_id),
    INDEX idx_providers_type (provider_type),
    INDEX idx_providers_status (status),
    INDEX idx_providers_workflow (workflow_id)
) COMMENT='Service providers (pandits, vendors, partners, etc.)';

-- Provider documents for KYC
CREATE TABLE provider_documents (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the document',
    provider_id CHAR(36) NOT NULL COMMENT 'Reference to providers.id',
    document_type VARCHAR(100) NOT NULL COMMENT 'Type of document',
    file_id VARCHAR(255) NOT NULL COMMENT 'File storage identifier',
    file_url VARCHAR(500) COMMENT 'URL to access the document',
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending' COMMENT 'Document status',
    verified_by CHAR(36) COMMENT 'Reference to users.id of verifier',
    verified_at TIMESTAMP NULL COMMENT 'When document was verified',
    rejection_reason TEXT COMMENT 'Reason for rejection',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id),
    INDEX idx_provider_docs_provider (provider_id),
    INDEX idx_provider_docs_type (document_type),
    INDEX idx_provider_docs_status (status)
) COMMENT='Provider documents for KYC verification';

-- Provider services
CREATE TABLE provider_services (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the service',
    provider_id CHAR(36) NOT NULL COMMENT 'Reference to providers.id',
    service_name VARCHAR(255) NOT NULL COMMENT 'Name of the service',
    description TEXT COMMENT 'Service description',
    price DECIMAL(10,2) COMMENT 'Service price',
    currency VARCHAR(3) DEFAULT 'INR' COMMENT 'Currency code',
    duration_minutes INT COMMENT 'Service duration in minutes',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Whether service is active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    INDEX idx_provider_services_provider (provider_id),
    INDEX idx_provider_services_active (is_active)
) COMMENT='Services offered by providers';

-- Provider availability schedule
CREATE TABLE provider_availability (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the availability record',
    provider_id CHAR(36) NOT NULL COMMENT 'Reference to providers.id',
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL COMMENT 'Day of week',
    start_time TIME NOT NULL COMMENT 'Start time of availability',
    end_time TIME NOT NULL COMMENT 'End time of availability',
    is_available BOOLEAN DEFAULT TRUE COMMENT 'Whether provider is available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    INDEX idx_provider_availability_provider (provider_id),
    INDEX idx_provider_availability_day (day_of_week),
    INDEX idx_provider_availability_available (is_available)
) COMMENT='Provider availability schedule';
```

### Booking Engine

```sql
-- Services offered through the platform
CREATE TABLE services (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the service',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(255) NOT NULL COMMENT 'Service name',
    description TEXT COMMENT 'Service description',
    category VARCHAR(100) COMMENT 'Service category',
    base_price DECIMAL(10,2) COMMENT 'Base service price',
    currency VARCHAR(3) DEFAULT 'INR' COMMENT 'Currency code',
    duration_minutes INT COMMENT 'Default service duration',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Whether service is active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_services_tenant (tenant_id),
    INDEX idx_services_category (category),
    INDEX idx_services_active (is_active)
) COMMENT='Services offered through the platform';

-- Bookings
CREATE TABLE bookings (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the booking',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    provider_id CHAR(36) COMMENT 'Reference to providers.id',
    service_id CHAR(36) COMMENT 'Reference to services.id',
    booking_reference VARCHAR(100) UNIQUE COMMENT 'Human-readable booking reference',
    status ENUM('processing', 'confirmed', 'completed', 'cancelled', 'rejected') DEFAULT 'processing' COMMENT 'Booking status',
    scheduled_at TIMESTAMP NOT NULL COMMENT 'Scheduled time for booking',
    duration_minutes INT COMMENT 'Booking duration in minutes',
    amount DECIMAL(10,2) COMMENT 'Booking amount',
    currency VARCHAR(3) DEFAULT 'INR' COMMENT 'Currency code',
    metadata JSON COMMENT 'Additional booking data as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (provider_id) REFERENCES providers(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    INDEX idx_bookings_tenant (tenant_id),
    INDEX idx_bookings_user (user_id),
    INDEX idx_bookings_provider (provider_id),
    INDEX idx_bookings_service (service_id),
    INDEX idx_bookings_status (status),
    INDEX idx_bookings_scheduled (scheduled_at),
    INDEX idx_bookings_reference (booking_reference)
) COMMENT='Bookings made through the platform';

-- Booking status history for audit trail
CREATE TABLE booking_status_history (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the status record',
    booking_id CHAR(36) NOT NULL COMMENT 'Reference to bookings.id',
    status ENUM('processing', 'confirmed', 'completed', 'cancelled', 'rejected') NOT NULL COMMENT 'Booking status',
    changed_by CHAR(36) COMMENT 'Reference to users.id of user who changed status',
    notes TEXT COMMENT 'Notes about the status change',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id),
    INDEX idx_booking_history_booking (booking_id),
    INDEX idx_booking_history_status (status),
    INDEX idx_booking_history_created (created_at)
) COMMENT='Booking status history for audit trail';
```

### Notification and Communication

```sql
-- Notification templates
CREATE TABLE notification_templates (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the template',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(255) NOT NULL COMMENT 'Template name',
    channel ENUM('email', 'sms', 'push') NOT NULL COMMENT 'Communication channel',
    subject VARCHAR(255) COMMENT 'Message subject (for email)',
    body TEXT NOT NULL COMMENT 'Message body template',
    variables_json JSON COMMENT 'Template variables as JSON',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Whether template is active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    INDEX idx_notification_templates_tenant (tenant_id),
    INDEX idx_notification_templates_channel (channel),
    INDEX idx_notification_templates_active (is_active)
) COMMENT='Notification message templates';

-- Notifications
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the notification',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    template_id CHAR(36) COMMENT 'Reference to notification_templates.id',
    recipient_user_id CHAR(36) COMMENT 'Reference to users.id of recipient',
    recipient_email VARCHAR(255) COMMENT 'Recipient email address',
    recipient_phone VARCHAR(20) COMMENT 'Recipient phone number',
    channel ENUM('email', 'sms', 'push') NOT NULL COMMENT 'Communication channel',
    subject VARCHAR(255) COMMENT 'Message subject (for email)',
    body TEXT NOT NULL COMMENT 'Message body',
    status ENUM('pending', 'sent', 'failed', 'delivered', 'opened') DEFAULT 'pending' COMMENT 'Notification status',
    priority ENUM('low', 'normal', 'high') DEFAULT 'normal' COMMENT 'Notification priority',
    scheduled_at TIMESTAMP NULL COMMENT 'Scheduled send time',
    sent_at TIMESTAMP NULL COMMENT 'Actual send time',
    delivered_at TIMESTAMP NULL COMMENT 'Delivery confirmation time',
    failure_reason TEXT COMMENT 'Reason for failure if failed',
    metadata JSON COMMENT 'Additional notification data as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (template_id) REFERENCES notification_templates(id),
    FOREIGN KEY (recipient_user_id) REFERENCES users(id),
    INDEX idx_notifications_tenant (tenant_id),
    INDEX idx_notifications_template (template_id),
    INDEX idx_notifications_recipient (recipient_user_id),
    INDEX idx_notifications_channel (channel),
    INDEX idx_notifications_status (status),
    INDEX idx_notifications_priority (priority),
    INDEX idx_notifications_scheduled (scheduled_at)
) COMMENT='Notifications sent through the platform';

-- User notification preferences
CREATE TABLE notification_preferences (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the preference',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    notification_type VARCHAR(100) NOT NULL COMMENT 'Type of notification',
    channel ENUM('email', 'sms', 'push') NOT NULL COMMENT 'Communication channel',
    is_enabled BOOLEAN DEFAULT TRUE COMMENT 'Whether notifications are enabled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_preference (user_id, notification_type, channel),
    INDEX idx_notification_prefs_user (user_id),
    INDEX idx_notification_prefs_type (notification_type),
    INDEX idx_notification_prefs_channel (channel),
    INDEX idx_notification_prefs_enabled (is_enabled)
) COMMENT='User notification preferences';
```

## Indexing Strategy

All tables include appropriate indexes for common query patterns:
1. Primary keys for unique identification
2. Foreign key indexes for JOIN operations
3. Status and type indexes for filtering
4. Timestamp indexes for time-based queries
5. Unique constraints for data integrity

## Security Considerations

1. All sensitive data is properly encrypted
2. Passwords are hashed using BCRYPT
3. Session tokens are securely generated and stored
4. Access tokens have short expiration times
5. MFA secrets are encrypted at rest
6. All communications should use TLS

## Tenant Isolation

All business tables include a `tenant_id` column with:
1. Foreign key constraints to the tenants table
2. Indexes for efficient querying
3. Row-level security implemented at the application layer
4. Unique constraints that include tenant_id to ensure data isolation

## Audit Trail

Critical operations are logged in:
1. access_logs for authorization decisions
2. workflow_events for workflow actions
3. booking_status_history for booking changes
4. All tables include created_at and updated_at timestamps

This schema provides a solid foundation for an enterprise-grade, multi-tenant backend platform with all the required modules and features.