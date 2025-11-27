# Documentation Index

> **Complete navigation guide to all documentation**

## 🚀 Quick Start

**Brand new to the project?** Start here:

1. [README](../README.md) - Project overview and quick start
2. [Overview](overview.md) - System capabilities and business context
3. [Architecture](architecture.md) - Technical design and patterns
4. [FAQ](faq.md) - Common questions answered

## 📚 Documentation by Category

### Core Concepts

Essential reading to understand the platform:

- [Overview](overview.md) - What the platform does and why
- [Architecture](architecture.md) - Technical architecture and DDD structure
- [System Design Philosophy](system-design-philosophy.md) - Design decisions and trade-offs
- [Domain Modules](domain-modules.md) - Deep dive into 9 business domains

### Security & Access Control

Authentication and authorization:

- [Authentication](authentication.md) - Web, API, SSO login flows
- [RBAC & Permissions](rbac.md) - Role-based access control
- [Security](security.md) - Security features, CSRF, XSS, audit logging
- [Impersonation](impersonation.md) - Admin impersonation system

### Multi-Tenancy

Understanding tenant isolation:

- [Tenancy](tenancy.md) - Multi-tenant architecture and data isolation
- [Configuration](config-and-env.md#tenant-configuration) - Tenant settings

### User Interface

Dynamic dashboard and sidebar:

- [Dynamic Sidebar & Dashboard](sidebar-and-dashboard.md) - Role-aware UI
- [Domain Modules: Dashboard](domain-modules.md#dashboard-domain) - Widget management

### Database & Data

Database structure and models:

- [Database Schema](database-schema.md) - Complete DB structure with ERD
- [Models](models.md) - Eloquent models (if created)
- [Domain Modules](domain-modules.md) - Models per domain

### API Development

Building and using the API:

- [API Reference](api-reference.md) - Complete REST API documentation
- [Authentication](authentication.md#api-authentication-sanctum) - API token auth
- [Routes](routes.md#api-routes) - API route organization

### HTTP Layer

Routes and middleware:

- [Routes](routes.md) - Web and API routing
- [Middleware](middleware.md) - HTTP middleware stack
- [Controllers](controllers.md) - Controller organization (if created)

### Configuration & Deployment

Running in production:

- [Configuration & Environment](config-and-env.md) - .env and config files
- [Deployment & DevOps](deployment-and-devops.md) - Production setup
- [Future Extensions](future-extensions.md#scalability-enhancements) - Performance tuning

### Reference Materials

Quick lookups:

- [FAQ](faq.md) - Frequently asked questions
- [Glossary](glossary.md) - Terms and definitions
- [Future Extensions](future-extensions.md) - Roadmap and planned features

## 🗂️ Complete File List

### Root Level
- [`README.md`](../README.md) - Project overview

### Documentation (`/docs/`)

**Architecture & Design (4 files)**
- [`overview.md`](overview.md)
- [`architecture.md`](architecture.md)
- [`system-design-philosophy.md`](system-design-philosophy.md)
- [`domain-modules.md`](domain-modules.md)

**Security & Access (4 files)**
- [`authentication.md`](authentication.md)
- [`rbac.md`](rbac.md)
- [`security.md`](security.md)
- [`impersonation.md`](impersonation.md)

**Multi-Tenancy (1 file)**
- [`tenancy.md`](tenancy.md)

**User Interface (1 file)**
- [`sidebar-and-dashboard.md`](sidebar-and-dashboard.md)

**Database & API (2 files)**
- [`database-schema.md`](database-schema.md)
- [`api-reference.md`](api-reference.md)

**HTTP Layer (2 files)**
- [`routes.md`](routes.md)
- [`middleware.md`](middleware.md)

**Configuration & Ops (2 files)**
- [`config-and-env.md`](config-and-env.md)
- [`deployment-and-devops.md`](deployment-and-devops.md)

**Reference (3 files)**
- [`faq.md`](faq.md)
- [`glossary.md`](glossary.md)
- [`future-extensions.md`](future-extensions.md)

**Meta (2 files)**
- [`_INDEX.md`](_INDEX.md) - This file
- [`_DOCUMENTATION_SUMMARY.md`](_DOCUMENTATION_SUMMARY.md) - Generation progress

**Total: 22 Documentation Files**

## 📊 Documentation Statistics

- **Total Words**: ~30,000+
- **Code Examples**: 80+
- **Mermaid Diagrams**: 10+
- **Tables**: 50+
- **Cross-Links**: 100+

## 🎯 Learning Paths

### For New Developers

1. [README](../README.md)
2. [Overview](overview.md)
3. [Architecture](architecture.md)
4. [Domain Modules](domain-modules.md)
5. [Database Schema](database-schema.md)
6. [FAQ](faq.md)

### For Frontend Developers

1. [Authentication](authentication.md)
2. [API Reference](api-reference.md)
3. [RBAC](rbac.md)
4. [Sidebar & Dashboard](sidebar-and-dashboard.md)

### For DevOps Engineers

1. [Deployment & DevOps](deployment-and-devops.md)
2. [Configuration](config-and-env.md)
3. [Security](security.md)
4. [Future Extensions](future-extensions.md)

###For Backend Developers

1. [Architecture](architecture.md)
2. [Domain Modules](domain-modules.md)
3. [Database Schema](database-schema.md)
4. [Middleware](middleware.md)
5. [Routes](routes.md)

### For Product Managers

1. [Overview](overview.md)
2. [System Design Philosophy](system-design-philosophy.md)
3. [Future Extensions](future-extensions.md)
4. [FAQ](faq.md)

## 🔍 Search Tips

**Looking for specific topics?**

- **"How do I...?"** → Start with [FAQ](faq.md)
- **Technical terms** → Check [Glossary](glossary.md)
- **API endpoints** → See [API Reference](api-reference.md)
- **Database tables** → See [Database Schema](database-schema.md)
- **Security questions** → See [Security](security.md)
- **Performance tuning** → See [Future Extensions](future-extensions.md#scalability-enhancements)

## 📝 Contributing to Documentation

Found an error or want to improve the docs?

1. Edit the relevant `.md` file in `/docs/`
2. Follow existing format (TOC, headings, code blocks)
3. Add cross-links where relevant
4. Run spell check
5. Submit PR

## 📧 Support

**Questions not answered in docs?**

- Open an issue on GitHub
- Contact the development team
- Check [FAQ](faq.md) first

---

**Last Updated**: 2025-11-27  
**Documentation Version**: 1.0  
**Platform Version**: Laravel 12
