# Enterprise-Grade Multi-Tenant Backend Platform Database Schema (Advanced Version)

## Overview

This document provides an enhanced database schema for the enterprise-grade multi-tenant backend platform. The schema is optimized for MySQL and follows enterprise best practices with proper indexing, normalization, and tenant isolation.

## Schema Enhancements

This enhanced schema includes:
- Advanced security features with row-level encryption
- Comprehensive audit trails with detailed change tracking
- Improved tenant management with hierarchical structures
- Enhanced indexing strategies for better performance
- Additional constraints and validations
- New tables for advanced functionality
- Improved data integrity with comprehensive foreign key relationships
- Advanced workflow capabilities with parallel processing support
- Enhanced notification system with delivery tracking
- Improved booking engine with resource management

## Core Tables

### Tenants and Tenant Management

```sql
-- Core tenant table with hierarchical structure
CREATE TABLE tenants (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the tenant',
    parent_tenant_id CHAR(36) COMMENT 'Reference to parent tenant for hierarchical structure',
    name VARCHAR(255) NOT NULL COMMENT 'Tenant name',
    slug VARCHAR(100) NOT NULL COMMENT 'URL-friendly tenant identifier',
    domain VARCHAR(255) UNIQUE COMMENT 'Primary domain for the tenant',
    config_json JSON COMMENT 'Tenant configuration as JSON',
    status ENUM('active', 'inactive', 'suspended', 'pending_setup') DEFAULT 'pending_setup' COMMENT 'Tenant status',
    tier ENUM('free', 'basic', 'premium', 'enterprise') DEFAULT 'free' COMMENT 'Service tier',
    timezone VARCHAR(50) DEFAULT 'UTC' COMMENT 'Tenant timezone',
    locale VARCHAR(10) DEFAULT 'en-US' COMMENT 'Tenant locale',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete timestamp',
    INDEX idx_tenant_status (status),
    INDEX idx_tenant_domain (domain),
    INDEX idx_tenant_slug (slug),
    INDEX idx_tenant_tier (tier),
    INDEX idx_tenant_parent (parent_tenant_id),
    FOREIGN KEY (parent_tenant_id) REFERENCES tenants(id)
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tenant_module (tenant_id, module_name),
    INDEX idx_tenant_modules_enabled (is_enabled)
) COMMENT='Tenant module enablement and configuration';

-- Tenant billing information
CREATE TABLE tenant_billing (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the billing record',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    billing_email VARCHAR(255) COMMENT 'Billing contact email',
    company_name VARCHAR(255) COMMENT 'Company name for billing',
    tax_id VARCHAR(100) COMMENT 'Tax identification number',
    address_line1 VARCHAR(255) COMMENT 'Address line 1',
    address_line2 VARCHAR(255) COMMENT 'Address line 2',
    city VARCHAR(100) COMMENT 'City',
    state VARCHAR(100) COMMENT 'State/Province',
    postal_code VARCHAR(20) COMMENT 'Postal code',
    country VARCHAR(2) COMMENT 'Country code (ISO 3166-1 alpha-2)',
    payment_method JSON COMMENT 'Payment method information',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tenant_billing (tenant_id)
) COMMENT='Tenant billing information';

-- Tenant usage statistics
CREATE TABLE tenant_usage (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the usage record',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    metric_name VARCHAR(100) NOT NULL COMMENT 'Name of the metric',
    metric_value BIGINT DEFAULT 0 COMMENT 'Current value of the metric',
    reset_at TIMESTAMP NULL COMMENT 'When the metric was last reset',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tenant_metric (tenant_id, metric_name),
    INDEX idx_tenant_usage_tenant (tenant_id),
    INDEX idx_tenant_usage_metric (metric_name)
) COMMENT='Tenant usage statistics for billing and limits';
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
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete timestamp',
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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
    type ENUM('totp', 'sms', 'email', 'backup_codes', 'webauthn') NOT NULL COMMENT 'Type of MFA method',
    secret_encrypted TEXT COMMENT 'Encrypted MFA secret',
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Whether this is the primary MFA method',
    is_verified BOOLEAN DEFAULT FALSE COMMENT 'Whether MFA method is verified',
    config_json JSON COMMENT 'MFA method configuration',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_mfa_user (user_id),
    INDEX idx_mfa_type (type),
    INDEX idx_mfa_primary (is_primary)
) COMMENT='User multi-factor authentication methods';

-- User password history for security
CREATE TABLE user_password_history (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the password history record',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    password_hash VARCHAR(255) NOT NULL COMMENT 'BCRYPT hashed password',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When password was set',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_password_history_user (user_id),
    INDEX idx_password_history_created (created_at)
) COMMENT='User password history for security';
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id),
    UNIQUE KEY unique_user_role_tenant (user_id, role_id, tenant_id),
    INDEX idx_user_roles_user (user_id),
    INDEX idx_user_roles_role (role_id),
    INDEX idx_user_roles_tenant (tenant_id)
) COMMENT='User role assignments';

-- User groups for easier management
CREATE TABLE user_groups (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the group',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(100) NOT NULL COMMENT 'Group name',
    description TEXT COMMENT 'Group description',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    UNIQUE KEY unique_group_per_tenant (tenant_id, name),
    INDEX idx_groups_tenant (tenant_id)
) COMMENT='User groups for easier management';

-- User to group assignments
CREATE TABLE user_group_members (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the membership',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    group_id CHAR(36) NOT NULL COMMENT 'Reference to user_groups.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    added_by CHAR(36) COMMENT 'Reference to users.id of adding user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES user_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (added_by) REFERENCES users(id),
    UNIQUE KEY unique_user_group_membership (user_id, group_id, tenant_id),
    INDEX idx_group_members_user (user_id),
    INDEX idx_group_members_group (group_id),
    INDEX idx_group_members_tenant (tenant_id)
) COMMENT='User to group assignments';

-- Group to role assignments
CREATE TABLE group_roles (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the assignment',
    group_id CHAR(36) NOT NULL COMMENT 'Reference to user_groups.id',
    role_id CHAR(36) NOT NULL COMMENT 'Reference to roles.id',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    assigned_by CHAR(36) COMMENT 'Reference to users.id of assigning user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (group_id) REFERENCES user_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id),
    UNIQUE KEY unique_group_role_tenant (group_id, role_id, tenant_id),
    INDEX idx_group_roles_group (group_id),
    INDEX idx_group_roles_role (role_id),
    INDEX idx_group_roles_tenant (tenant_id)
) COMMENT='Group to role assignments';
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

-- Security events logging
CREATE TABLE security_events (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the security event',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    user_id CHAR(36) COMMENT 'Reference to users.id if applicable',
    event_type VARCHAR(100) NOT NULL COMMENT 'Type of security event',
    severity ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium' COMMENT 'Event severity',
    ip_address VARCHAR(45) COMMENT 'IP address associated with event',
    user_agent TEXT COMMENT 'User agent string',
    description TEXT COMMENT 'Event description',
    metadata JSON COMMENT 'Additional event data',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_security_events_tenant (tenant_id),
    INDEX idx_security_events_user (user_id),
    INDEX idx_security_events_type (event_type),
    INDEX idx_security_events_severity (severity),
    INDEX idx_security_events_created (created_at)
) COMMENT='Security events logging for monitoring';
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
    version INT DEFAULT 1 COMMENT 'Workflow version',
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
    status ENUM('pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected', 'cancelled') DEFAULT 'pending' COMMENT 'Workflow status',
    context_json JSON COMMENT 'Workflow context data as JSON',
    started_at TIMESTAMP NULL COMMENT 'When workflow was started',
    completed_at TIMESTAMP NULL COMMENT 'When workflow was completed',
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
    step_type ENUM('form', 'document', 'verification', 'background_check', 'admin_approval', 'auto', 'parallel') NOT NULL COMMENT 'Type of step',
    config_json JSON COMMENT 'Step configuration as JSON',
    data_json JSON COMMENT 'Step data as JSON',
    status ENUM('pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'Step status',
    assigned_to CHAR(36) COMMENT 'Reference to users.id for assigned user',
    assigned_group CHAR(36) COMMENT 'Reference to user_groups.id for assigned group',
    attempted_at TIMESTAMP NULL COMMENT 'When step was first attempted',
    completed_at TIMESTAMP NULL COMMENT 'When step was completed',
    due_at TIMESTAMP NULL COMMENT 'When step is due',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (workflow_id) REFERENCES workflows(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (assigned_group) REFERENCES user_groups(id),
    INDEX idx_workflow_steps_workflow (workflow_id),
    INDEX idx_workflow_steps_key (step_key),
    INDEX idx_workflow_steps_status (status),
    INDEX idx_workflow_steps_assigned (assigned_to),
    INDEX idx_workflow_steps_group (assigned_group)
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
    field_type ENUM('text', 'email', 'phone', 'number', 'date', 'select', 'checkbox', 'file', 'textarea', 'radio') NOT NULL COMMENT 'Type of field',
    label VARCHAR(255) NOT NULL COMMENT 'Field label',
    placeholder VARCHAR(255) COMMENT 'Field placeholder text',
    is_required BOOLEAN DEFAULT FALSE COMMENT 'Whether field is required',
    validation_rules JSON COMMENT 'Field validation rules as JSON',
    options_json JSON COMMENT 'Field options as JSON (for select fields)',
    sort_order INT DEFAULT 0 COMMENT 'Field display order',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (step_id) REFERENCES workflow_steps(id) ON DELETE CASCADE,
    INDEX idx_workflow_forms_step (step_id),
    INDEX idx_workflow_forms_key (field_key),
    INDEX idx_workflow_forms_required (is_required)
) COMMENT='Workflow form field definitions';

-- Workflow step dependencies for parallel processing
CREATE TABLE workflow_step_dependencies (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the dependency',
    step_id CHAR(36) NOT NULL COMMENT 'Reference to workflow_steps.id (dependent step)',
    depends_on_step_id CHAR(36) NOT NULL COMMENT 'Reference to workflow_steps.id (step that must be completed first)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (step_id) REFERENCES workflow_steps(id) ON DELETE CASCADE,
    FOREIGN KEY (depends_on_step_id) REFERENCES workflow_steps(id) ON DELETE CASCADE,
    UNIQUE KEY unique_step_dependency (step_id, depends_on_step_id),
    INDEX idx_step_dependencies_step (step_id),
    INDEX idx_step_dependencies_depends (depends_on_step_id)
) COMMENT='Workflow step dependencies for parallel processing';
```

### Provider Management

```sql
-- Providers (pandits, vendors, partners, etc.)
CREATE TABLE providers (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the provider',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    provider_type ENUM('pandit', 'vendor', 'partner', 'astrologer', 'tutor', 'consultant') NOT NULL COMMENT 'Type of provider',
    first_name VARCHAR(100) COMMENT 'Provider first name',
    last_name VARCHAR(100) COMMENT 'Provider last name',
    email VARCHAR(255) COMMENT 'Provider email',
    phone VARCHAR(20) COMMENT 'Provider phone',
    status ENUM('pending', 'verified', 'active', 'suspended', 'rejected') DEFAULT 'pending' COMMENT 'Provider status',
    workflow_id CHAR(36) COMMENT 'Reference to workflows.id for onboarding',
    profile_json JSON COMMENT 'Provider profile data as JSON',
    rating DECIMAL(3,2) DEFAULT 0.00 COMMENT 'Provider rating',
    review_count INT DEFAULT 0 COMMENT 'Number of reviews',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete timestamp',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (workflow_id) REFERENCES workflows(id),
    INDEX idx_providers_tenant (tenant_id),
    INDEX idx_providers_type (provider_type),
    INDEX idx_providers_status (status),
    INDEX idx_providers_workflow (workflow_id),
    INDEX idx_providers_rating (rating)
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    INDEX idx_provider_availability_provider (provider_id),
    INDEX idx_provider_availability_day (day_of_week),
    INDEX idx_provider_availability_available (is_available)
) COMMENT='Provider availability schedule';

-- Provider reviews and ratings
CREATE TABLE provider_reviews (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the review',
    provider_id CHAR(36) NOT NULL COMMENT 'Reference to providers.id',
    user_id CHAR(36) NOT NULL COMMENT 'Reference to users.id',
    booking_id CHAR(36) COMMENT 'Reference to bookings.id',
    rating INT NOT NULL COMMENT 'Rating (1-5 stars)',
    review_text TEXT COMMENT 'Review text',
    is_verified BOOLEAN DEFAULT FALSE COMMENT 'Whether review is verified',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    UNIQUE KEY unique_user_provider_review (user_id, provider_id),
    INDEX idx_provider_reviews_provider (provider_id),
    INDEX idx_provider_reviews_user (user_id),
    INDEX idx_provider_reviews_rating (rating)
) COMMENT='Provider reviews and ratings';
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete timestamp',
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
    status ENUM('processing', 'confirmed', 'completed', 'cancelled', 'rejected', 'no_show') DEFAULT 'processing' COMMENT 'Booking status',
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
    status ENUM('processing', 'confirmed', 'completed', 'cancelled', 'rejected', 'no_show') NOT NULL COMMENT 'Booking status',
    changed_by CHAR(36) COMMENT 'Reference to users.id of user who changed status',
    notes TEXT COMMENT 'Notes about the status change',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id),
    INDEX idx_booking_history_booking (booking_id),
    INDEX idx_booking_history_status (status),
    INDEX idx_booking_history_created (created_at)
) COMMENT='Booking status history for audit trail';

-- Booking payments
CREATE TABLE booking_payments (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the payment',
    booking_id CHAR(36) NOT NULL COMMENT 'Reference to bookings.id',
    amount DECIMAL(10,2) NOT NULL COMMENT 'Payment amount',
    currency VARCHAR(3) DEFAULT 'INR' COMMENT 'Currency code',
    payment_method VARCHAR(50) COMMENT 'Payment method used',
    transaction_id VARCHAR(255) COMMENT 'External transaction ID',
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending' COMMENT 'Payment status',
    processed_at TIMESTAMP NULL COMMENT 'When payment was processed',
    refund_id VARCHAR(255) COMMENT 'Refund transaction ID if applicable',
    metadata JSON COMMENT 'Additional payment data',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_booking_payments_booking (booking_id),
    INDEX idx_booking_payments_status (status),
    INDEX idx_booking_payments_method (payment_method)
) COMMENT='Booking payments';

-- Booking cancellations
CREATE TABLE booking_cancellations (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the cancellation',
    booking_id CHAR(36) NOT NULL COMMENT 'Reference to bookings.id',
    cancelled_by CHAR(36) NOT NULL COMMENT 'Reference to users.id who cancelled',
    reason VARCHAR(255) COMMENT 'Cancellation reason',
    notes TEXT COMMENT 'Additional notes',
    refund_amount DECIMAL(10,2) COMMENT 'Refund amount if applicable',
    refund_status ENUM('not_applicable', 'pending', 'processed', 'failed') DEFAULT 'not_applicable' COMMENT 'Refund status',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (cancelled_by) REFERENCES users(id),
    INDEX idx_booking_cancellations_booking (booking_id),
    INDEX idx_booking_cancellations_user (cancelled_by)
) COMMENT='Booking cancellations';
```

### Notification and Communication

```sql
-- Notification templates
CREATE TABLE notification_templates (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the template',
    tenant_id CHAR(36) NOT NULL COMMENT 'Reference to tenants.id',
    name VARCHAR(255) NOT NULL COMMENT 'Template name',
    channel ENUM('email', 'sms', 'push', 'webhook') NOT NULL COMMENT 'Communication channel',
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
    channel ENUM('email', 'sms', 'push', 'webhook') NOT NULL COMMENT 'Communication channel',
    subject VARCHAR(255) COMMENT 'Message subject (for email)',
    body TEXT NOT NULL COMMENT 'Message body',
    status ENUM('pending', 'sent', 'failed', 'delivered', 'opened') DEFAULT 'pending' COMMENT 'Notification status',
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal' COMMENT 'Notification priority',
    scheduled_at TIMESTAMP NULL COMMENT 'Scheduled send time',
    sent_at TIMESTAMP NULL COMMENT 'Actual send time',
    delivered_at TIMESTAMP NULL COMMENT 'Delivery confirmation time',
    opened_at TIMESTAMP NULL COMMENT 'When notification was opened',
    failure_reason TEXT COMMENT 'Reason for failure if failed',
    metadata JSON COMMENT 'Additional notification data as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
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

-- Notification delivery logs
CREATE TABLE notification_delivery_logs (
    id CHAR(36) PRIMARY KEY COMMENT 'UUID for the delivery log',
    notification_id CHAR(36) NOT NULL COMMENT 'Reference to notifications.id',
    delivery_status ENUM('pending', 'sent', 'failed', 'delivered', 'opened') NOT NULL COMMENT 'Delivery status',
    provider_response TEXT COMMENT 'Response from delivery provider',
    delivered_at TIMESTAMP NULL COMMENT 'When notification was delivered',
    opened_at TIMESTAMP NULL COMMENT 'When notification was opened',
    failure_reason TEXT COMMENT 'Reason for failure if failed',
    metadata JSON COMMENT 'Additional delivery data',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE,
    INDEX idx_delivery_logs_notification (notification_id),
    INDEX idx_delivery_logs_status (delivery_status)
) COMMENT='Notification delivery logs';
```

## Indexing Strategy

All tables include appropriate indexes for common query patterns:
1. Primary keys for unique identification
2. Foreign key indexes for JOIN operations
3. Status and type indexes for filtering
4. Timestamp indexes for time-based queries
5. Unique constraints for data integrity
6. Composite indexes for multi-column queries
7. Full-text indexes for text search capabilities

## Security Considerations

1. All sensitive data is properly encrypted
2. Passwords are hashed using BCRYPT
3. Session tokens are securely generated and stored
4. Access tokens have short expiration times
5. MFA secrets are encrypted at rest
6. All communications should use TLS
7. Row-level security implemented at the application layer
8. Audit trails for all critical operations
9. Secure password policies with history tracking
10. Rate limiting for authentication attempts

## Tenant Isolation

All business tables include a `tenant_id` column with:
1. Foreign key constraints to the tenants table
2. Indexes for efficient querying
3. Row-level security implemented at the application layer
4. Unique constraints that include tenant_id to ensure data isolation
5. Hierarchical tenant structures for complex organizations

## Audit Trail

Critical operations are logged in:
1. access_logs for authorization decisions
2. workflow_events for workflow actions
3. booking_status_history for booking changes
4. security_events for monitoring security incidents
5. notification_delivery_logs for communication tracking
6. All tables include created_at and updated_at timestamps

## Advanced Features

1. **Hierarchical Tenants**: Support for parent-child tenant relationships
2. **Enhanced Workflows**: Parallel processing capabilities with dependencies
3. **Comprehensive Billing**: Usage tracking and billing information
4. **Advanced Notifications**: Delivery tracking and multiple channels
5. **Provider Reviews**: Rating system for service providers
6. **Booking Payments**: Complete payment processing and refund handling
7. **Security Monitoring**: Detailed security event logging
8. **User Groups**: Simplified role management through groups
9. **Enhanced MFA**: Support for WebAuthn and other modern authentication methods
10. **Audit Trails**: Comprehensive logging for compliance and debugging

This enhanced schema provides a robust foundation for an enterprise-grade, multi-tenant backend platform with advanced features for security, scalability, and maintainability.