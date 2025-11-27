# System Overview

> **Enterprise multi-tenant SaaS platform for provider-customer booking and workflow automation**

## 🔗 Table of Contents

- [What is this Platform?](#what-is-this-platform)
- [Business Context](#business-context)
- [Core Capabilities](#core-capabilities)
- [User Roles](#user-roles)
- [Technology Highlights](#technology-highlights)
- [System Boundaries](#system-boundaries)
- [Cross-Links](#cross-links)

## What is this Platform?

This is a **Laravel 12-based multi-tenant SaaS application** designed to handle complex booking, scheduling, and workflow automation scenarios across multiple independent organizations (tenants). Each tenant operates in complete isolation with their own users, data, and configurations.

The platform implements a sophisticated **domain-driven architecture** where business logic is organized into 9 distinct domains, each responsible for a specific area of functionality.

### Purpose

The platform serves as a **white-label booking and operations management solution** that can be deployed for:

- Healthcare appointment scheduling
- Service provider marketplaces
- Professional services booking
- Multi-location business management
- Any booking/workflow-intensive application

## Business Context

### Problem Statement

Organizations need:
1. **Multi-location management** with centralized control
2. **Flexible role-based access** that adapts to organizational hierarchies
3. **Automated workflows** for approvals and operations
4. **Comprehensive audit trails** for compliance
5. **Scalable architecture** that grows with the business

### Solution

This platform provides:

- **Tenant Isolation** - Each organization operates independently with guaranteed data separation
- **Role Priority System** - Automatic interface adaptation based on user's highest-priority role
- **Dynamic Permissions** - Database-driven RBAC that can be modified without code changes
- **Workflow Engine** - Configurable multi-step approval processes
- **Unified Dashboard** - Single entry point that adapts to user context
- **Extensive Integrations** - Firebase push notifications, SSO, OAuth support

## Core Capabilities

### 1. Multi-Tenancy

**What**: Complete isolation of data and configuration per organization (tenant)

**Key Features**:
- Domain-based tenant identification
- Automatic tenant context resolution via middleware
- Subscription tier management (Free, Basic, Premium, Enterprise)
- Per-tenant feature flags and modules
- Tenant-specific domains and custom branding

**Business Value**: Deploy once, serve thousands of independent organizations

[Read More →](tenancy.md)

### 2. RBAC (Role-Based Access Control)

**What**: Sophisticated permission system with role priority and hierarchy

**Key Features**:
- Priority-based roles (lower number = higher priority)
- Dynamic sidebar menu generation based on permissions
- Permission inheritance through role hierarchy
- Tenant-scoped permissions
- Policy engine for complex authorization rules

**Business Value**: Precise control over who can do what, reducing security risks

[Read More →](rbac.md)

### 3. Booking & Scheduling

**What**: Provider-customer appointment management system

**Key Features**:
- Service catalog management
- Provider availability tracking
- Booking status workflows (pending → confirmed → completed)
- Cancellation handling with reason tracking
- Revenue tracking per provider

**Business Value**: Streamlined scheduling reduces no-shows and increases revenue

[Read More →](domain-modules.md#booking-domain)

### 4. Workflow Automation

**What**: Configurable multi-step approval and automation processes

**Key Features**:
- Workflow definition builder
- Step-by-step execution tracking
- Event-driven triggers
- Form-based data collection
- Status change notifications

**Business Value**: Automated approvals free up staff for higher-value work

[Read More →](domain-modules.md#workflow-domain)

### 5. Dynamic Dashboard

**What**: Role-aware unified dashboard with permission-controlled widgets

**Key Features**:
- Single `/dashboard` route for all users
- Auto-rendered statistics based on highest-priority role
- Widget visibility controlled by permissions
- Cached for performance (5-minute TTL)
- Real-time session tracking

**Business Value**: Users see exactly what they need, nothing more

[Read More →](sidebar-and-dashboard.md)

## User Roles

The platform supports multiple role families, each with distinct capabilities:

### Super Admin
- **Scope**: Platform-wide
- **Capabilities**: Manage all tenants, users, system configuration
- **Dashboard View**: System health, tenant statistics, platform-wide metrics
- **Use Case**: Platform operator/owner

### Tenant Admin
- **Scope**: Single tenant
- **Capabilities**: Manage organization users, providers, settings
- **Dashboard View**: Tenant-specific user counts, bookings, revenue
- **Use Case**: Organization administrator

### Provider
- **Scope**: Own profile within tenant
- **Capabilities**: Manage own schedule, services, view own bookings
- **Dashboard View**: Personal booking stats, revenue, schedule
- **Use Case**: Service provider (doctor, consultant, etc.)

### Ops (Operations)
- **Scope**: Tenant workflows and approvals
- **Capabilities**: Process workflows, manage bookings, run reports
- **Dashboard View**: Pending workflows, approval queue, analytics
- **Use Case**: Operations staff

### Customer / Vendor
- **Scope**: Own account
- **Capabilities**: Create bookings, view history, manage profile
- **Dashboard View**: Own bookings, payment history
- **Use Case**: End customer

## Technology Highlights

### Framework & Language
- **Laravel 12** - Latest LTS with full HTTP/3 support
- **PHP 8.2+** - Modern PHP with enums, attributes, and typed properties

### Data Layer
- **UUID/ULID Primary Keys** - Globally unique, time-sorted identifiers
- **Multi-Database Support** - SQLite (dev), MySQL/PostgreSQL (prod)
- **Eloquent ORM** - Rich models with relationship eager loading

### Authentication & Security
- **Laravel Sanctum** - API token authentication (SPA and mobile)
- **Session-based Auth** - Traditional web authentication
- **Firebase Integration** - Push notifications and SSO
- **MFA Support** - Ready for TOTP/SMS 2FA implementation

### Frontend
- **Blade Templates** - Server-rendered views
- **Alpine.js** - Lightweight reactive components
- **Tailwind CSS** - Utility-first styling

### Performance
- **Redis-ready** - Cache and queue support
- **Database Query Optimization** - Eager loading, indexed queries
- **Permission Caching** - 1-hour cache TTL
- **Widget Data Caching** - 5-minute cache TTL

## System Boundaries

### What This Platform DOES

✅ Multi-tenant management  
✅ Booking/appointment scheduling  
✅ Workflow automation  
✅ RBAC with dynamic permissions  
✅ Notification delivery  
✅ Audit logging  
✅ Session management  
✅ Impersonation with accountability

### What This Platform DOES NOT

❌ Payment processing (integrate with Stripe/PayPal/etc.)  
❌ Video conferencing (integrate with Zoom/Teams/etc.)  
❌ Email sending (uses SMTP/Mailgun/SES)  
❌ SMS sending (integrate with Twilio/etc.)  
❌ Document storage beyond basic file uploads  
❌ Real-time chat (can be extended)

## Extensibility Points

The platform is designed for extension:

1. **New Domains** - Add modules in `app/Domains/`
2. **Custom Workflows** - Define new workflow types
3. **Additional Roles** - Create role families via seeders
4. **API Integrations** - Extend service container
5. **Custom Widgets** - Add dashboard widgets
6. **Event Listeners** - Hook into domain events

## Performance Characteristics

| Metric | Target | Notes |
|--------|--------|-------|
| **Dashboard Load** | <200ms | With caching enabled |
| **API Response** | <100ms | Simple CRUD operations |
| **Concurrent Users** | 1000+ | Per tenant, with proper infrastructure |
| **Database Size** | TB-scale | With proper indexing |
| **Tenant Count** | Unlimited | Horizontal scalability |

## Cross-Links

### Architecture & Design
- [Architecture Deep Dive](architecture.md)
- [System Design Philosophy](system-design-philosophy.md)
- [Folder Structure Analysis](folder-by-folder-analysis.md)

### Core Systems
- [Multi-Tenancy](tenancy.md)
- [RBAC & Permissions](rbac.md)
- [Authentication](authentication.md)
- [Security](security.md)

### Development
- [Database Schema](database-schema.md)
- [Domain Modules](domain-modules.md)
- [API Reference](api-reference.md)
- [Testing](testing.md)

### Operations
- [Deployment Guide](deployment-and-devops.md)
- [Configuration](config-and-env.md)
- [Logging & Audit](logging-and-auditing.md)
