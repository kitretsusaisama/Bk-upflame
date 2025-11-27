# Enterprise Multi-Tenant SaaS Platform

> **Robust Laravel 12 multi-tenant enterprise platform with domain-driven architecture, role-based access control, and comprehensive audit capabilities**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 🎯 Overview

This is an enterprise-grade multi-tenant SaaS application built with Laravel 12, featuring comprehensive booking/scheduling capabilities, workflow automation, and advanced access control. The platform is designed for scalability, security, and maintainability with a clean domain-driven architecture.

### Key Features

- **🏢 Multi-Tenancy** - Complete tenant isolation with domain-based identification
- **🔐 Advanced RBAC** - Priority-based role system with granular permissions
- **📊 Dynamic Dashboards** - Role-aware unified dashboard with configurable widgets
- **🔄 Workflow Engine** - Extensible approval and automation workflows
- **📅 Booking System** - Provider/customer scheduling with status tracking
- **👤 Impersonation** - Secure user impersonation with full audit trails
- **🔔 Notifications** - Multi-channel notification system
- **🛡️ Security** - Session management, MFA support, audit logging

## 📁 Architecture

The application follows a **domain-driven design** pattern with clear separation of concerns:

```
app/
├── Domains/           # Business logic organized by domain
│   ├── Access/       # RBAC, roles, permissions, policies
│   ├── Booking/      # Booking/appointment management
│   ├── Dashboard/    # Dashboard widgets and layouts
│   ├── Identity/     # Users, tenants, authentication
│   ├── Notification/ # Notification system
│   ├── Provider/     # Service provider management
│   ├── SSO/          # Single sign-on integration
│   ├── Tenant/       # Tenant administration
│   └── Workflow/     # Workflow definitions and automation
├── Services/         # Cross-domain application services
├── Http/             # HTTP layer (controllers, middleware, requests)
├── Support/          # Shared utilities and helpers
└── Providers/        # Service providers
```

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Database (MySQL/PostgreSQL recommended)

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd Bk-upflame

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Build assets
npm run build

# Start the development server
php artisan serve
```

### Default Credentials

After seeding, you can log in with:

- **Super Admin**: `superadmin@example.com` / (check UserSeeder)
- **Tenant Admin**: `tenant@example.com` / (check UserSeeder)

## 📚 Documentation

Comprehensive technical documentation is available in the `/docs` directory:

### Essential Reading

- [**Overview**](docs/overview.md) - System overview and business context
- [**Architecture**](docs/architecture.md) - Technical architecture and design patterns
- [**Setup Guide**](docs/deployment-and-devops.md) - Installation and deployment
- [**API Reference**](docs/api-reference.md) -  API endpoints and usage

### Core Systems

- [Multi-Tenancy](docs/tenancy.md) - Tenant isolation and management
- [RBAC & Permissions](docs/rbac.md) - Role-based access control
- [Authentication](docs/authentication.md) - Auth flows (Web, API, SSO)
- [Security](docs/security.md) - Security features and best practices
- [Dashboard System](docs/sidebar-and-dashboard.md) - Dynamic UI system

### Development

- [Database Schema](docs/database-schema.md) - Complete DB structure
- [Domain Modules](docs/domain-modules.md) - Business domain breakdown
- [Services & Support](docs/services-and-support.md) - Service layer architecture
- [Middleware](docs/middleware.md) - HTTP middleware stack
- [Testing](docs/testing.md) - Testing strategy and examples

### Reference

- [Configuration](docs/config-and-env.md) - Environment variables and config
- [Coding Standards](docs/coding-standards.md) - Development guidelines
- [Glossary](docs/glossary.md) - Terms and definitions
- [FAQ](docs/faq.md) - Frequently asked questions

## 🏗️ Technology Stack

| Category | Technologies |
|----------|-------------|
| **Framework** | Laravel 12 |
| **Language** | PHP 8.2+ |
| **Database** | SQLite (dev), MySQL/PostgreSQL (prod) |
| **Authentication** | Laravel Sanctum, Laravel Session |
| **Permissions** | Spatie Laravel Permission (customized) |
| **Frontend** | Blade, Alpine.js, Tailwind CSS |
| **Queue** | Database (dev), Redis (prod recommended) |
| **Cache** | Database (dev), Redis (prod recommended) |
| **Session** | Database-backed sessions |
| **Storage** | Local (dev), S3 (prod) |

## 🔒 Security Features

- **Tenant Isolation** - Strict data scoping per tenant
- **Session Security** - Regeneration, timeout, device tracking
- **CSRF Protection** - Built-in Laravel CSRF tokens
- **XSS Prevention** - Blade templating auto-escaping
- **MFA Support** - Multi-factor authentication ready
- **Audit Logging** - Comprehensive activity tracking
- **Impersonation Logging** - Full accountability for admin actions
- **Permission Caching** - Performance-optimized RBAC

## 📊 Database

The platform uses **UUID (ULID)** for primary keys across all models, providing:
- Globally unique identifiers
- Time-ordered IDs
- Better index performance than random UUIDs

Key database features:
- 53 migrations covering all domains
- Comprehensive foreign key relationships
- JSON columns for flexible metadata
- Optimized indexes for tenant-scoped queries

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 📈 Performance

- **Permission Caching** - 1-hour TTL (configurable)
- **Widget Data Caching** - 5-minute TTL
- **Sidebar Caching** - 10-minute TTL per user
- **Database Indexes** - Optimized for tenant-scoped queries
- **Eager Loading** - Prevents N+1 query problems
- **Queue Workers** - Async processing for heavy operations

## 🛠️ Development Scripts

```bash
# Start development environment (server + queue + logs + vite)
composer dev

# Run migrations
php artisan migrate

# Refresh database with fresh data
php artisan migrate:fresh --seed

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🤝 Contributing

1. Follow the [Coding Standards](docs/coding-standards.md)
2. Write tests for new features
3. Update documentation as needed
4. Submit pull requests with clear descriptions

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For detailed troubleshooting, see:
- [FAQ](docs/faq.md)
- [Troubleshooting sections in domain docs](docs/domain-modules.md)

## 🗺️ Roadmap

See [Future Extensions](docs/future-extensions.md) for planned features including:
- Microservices compatibility
- Event-driven architecture
- Multi-region tenant partitioning
- Real-time features with WebSockets
- Plugin marketplace system
- Advanced analytics and reporting

---

**Built with ❤️ using Laravel**
