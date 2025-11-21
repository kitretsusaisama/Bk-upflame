Multi-Tenant Identity & Operations Platform

🧩 Project Type:

A modular, enterprise-class platform providing:

Centralized Authentication & SSO

Multi-Tenant Capabilities

Multi-Role Access System

Pluggable Workflow Engine

Role-Based & Attribute-Based Authorization

Provider Onboarding (Pandits, Vendors, Partners, etc.)

Unified API Platform for All Apps

Domain-Based Login Experience

Booking & Operations Foundation

This is a core infrastructure product that supports multiple Upflame products in the future.

🎯 Core Vision

To create a unified, production-grade backend architecture that allows Upflame to onboard:

multiple brands

multiple roles

multiple products

multiple tenants/domains

while maintaining centralized identity, consistent security, and plug-and-play expansion.

Think of it as the combined power of:

Auth0 / Okta → SSO + Identity

UrbanCompany Backend → Provider & Service onboarding

Zoho/Freshworks → Modular system architecture

AWS IAM → Hybrid RBAC + ABAC

Notion/HubSpot Workflows → Workflow engine

Blinkit/Zepto speed → Instant booking UX

built into one unified backend.

🧱 Core Modules in the System

The platform contains 8 major modules, each independently scalable and reusable across multiple products.

1️⃣ Identity & SSO Module

OIDC/OAuth2 authorization server

Access/Refresh/ID tokens

MFA (TOTP), device trust

Central verification URL (auth.upflame.in/get/...)

Domain-based login pages

2️⃣ Tenant Management Module

Manage organizations/domains

Tenant isolation (row-level)

Tenant configuration & branding

Tenant-specific login at:
auth.<tenant-domain>.com

3️⃣ User & Role Module

Supports all role families:

Users (customers)

Providers (pandits, vendors, astrologers, partners)

Internal staff (admin, ops, support, finance)

Corporate roles (enterprise clients)

Each user can have multiple roles, even across tenants.

4️⃣ RBAC + ABAC Authorization Module

Fine-grained RBAC

Policy-driven ABAC

Dynamic permission checks

Enterprise-grade IAM model
(Like AWS IAM or Google IAM)

5️⃣ Universal Workflow Engine Module

A generic workflow engine used to orchestrate:

Pandit onboarding

Vendor onboarding

User verification

Employee approvals

Multi-step KYC

Document validation

Role-based onboarding

This workflow engine makes onboarding customizable at a tenant or role level.

6️⃣ Provider Management Module

Handles:

Pandits

Vendors

Partners

Astrologers

Any future service provider role

Fully powered by the workflow engine.

7️⃣ Booking Engine Foundation

The Phase-1 version supports:

Optimistic booking creation (fast like Zepto/Blinkit)

Status tracking

Provider assignment expansion in Phase-2

Multi-service booking patterns

8️⃣ Notification & Communication Module

Email, SMS, push triggers

Verification links

Workflow triggers

Domain-based templates

🧠 Architectural Philosophy

The platform is built following MNC engineering principles:

✔ Domain-Driven Design (DDD)

Clear separation of Identity, Access, Tenant, Workflow, Provider, Booking.

✔ Zero-Trust Security

Every request is authenticated, authorized, and policy-evaluated.

✔ Multi-Tenant by Default

Every table operates with tenant_id.
Every request resolves tenant via domain/header.

✔ Modular + Pluggable

Modules behave like “microservices inside a monolith” (best for Laravel Phase-1).

✔ API-First Design

Backend exposes versioned APIs (/v1/...) to all present & future frontends.

✔ Extensible for Future Microservices

Phase-1: modular monolith (simple, fast)
Phase-2: extract critical modules (identity, workflow, booking) as microservices

✔ Clean & Maintainable

Strict folder structure, repository pattern, stable interfaces.

🌐 User Journeys Supported in Phase-1
🔹 1. Tenant creates account

Brand → Domain → Config → Team roles

🔹 2. Tenant adds login domain

CNAME → Verified → Login page branded

🔹 3. Provider onboarding (multi-step)

Step 1 → Step 2 → Step 3 → Approval

🔹 4. End-user onboarding

Basic profile → optional KYC → consent

🔹 5. Admin operations

Review providers, approve workflows, manage roles

🔹 6. Booking flow (v1)

User books → instant confirmation → backend processes → provider assigned

🧬 Technology Stack (Recommended)
Backend: Laravel (Modular Monolith Pattern)
Auth: Custom OIDC Provider (Passport or custom)
Database: MySQL (tenant-aware)
Caching: Redis (optional Phase-1)
Frontend: Next.js 15 (multi-tenant UI)
Storage: S3 / DigitalOcean Spaces
CI/CD: GitHub Actions
Monitoring: Sentry + Grafana
Standards: OAuth2, OIDC, OWASP, GDPR-compliant flows
🔥 What Makes This Enterprise-Ready?
1. Multi-tenant backbone
2. Multi-domain login flows
3. Centralized SSO infrastructure
4. Pluggable workflow builder
5. Hybrid RBAC + ABAC model
6. Clear extensibility path
7. Zero-trust security
8. API versioning & strict schema
9. Automated CI/CD & migrations
10. Strong separation of Identity & Business Logic

This is the foundation used in real MNC products.

📌 Where Phase-1 Ends

By end of Phase-1, you have:

Multi-tenant infrastructure

SSO server

Custom domain login pages

Role & Permission system

Policy engine (ABAC)

Universal onboarding workflows

Provider module

Booking foundation

Complete API v1

Full documentation

Production deployment

Enterprise-level security

This forms a solid platform upon which entire Upflame ecosystem can grow.

MULTI-TENANT STRATEGY (Phase-1 recommendation)

Tenant model: single DB, row-level isolation using tenant_id column on business tables.

Why: Simple, low-cost, avoids schema migration complexity in phase-1.

Tenant identification: HTTP Host header or JWT claim tid. Support routes: tenant.example.com and example.com with path mapping.

When to migrate: offer “schema per tenant” in Phase-2 for high-security enterprise tenants.

SSO & CUSTOM DOMAIN LOGIN — DESIGN (OIDC style)

Overall idea: A central Authorization Server exposes OIDC endpoints. Tenant frontends redirect to the auth server. The auth server renders login pages which are branded per tenant (template + CSS), served either via host header (preferred) or tenant param.

Components

Auth Server endpoints:

GET /authorize (standard OIDC authorize)

POST /authorize (login submission)

POST /token (token exchange: code → access/refresh tokens)

GET /userinfo (protected resource)

GET /.well-known/openid-configuration (OIDC metadata)

GET /session/verify?token=... (for auth.upflame.in/get/... verification flows)

GET /logout, POST /revoke

Login pages

Routing: register a CNAME for auth.<tenant-domain> pointing to the edge. Edge/Poxy forwards host header to Auth service. Auth service resolves tenant by host header to load branding.

Alternative: https://auth.upflame.in/login?tenant=tenantA but host-based is cleaner.

SSO flow (Authorization code with PKCE for SPAs)

SPA redirects to https://auth.upflame.in/authorize?client_id=xxx&redirect_uri=...&scope=openid email&state=abc&code_challenge=...

Auth server shows tenant-branded login at auth.<tenant> or central domain with tenant context.

On successful login, auth server issues auth code and redirects back.

SPA exchanges code for tokens at /token and stores access token in memory + refresh token in secure httpOnly cookie (or refresh via backend).

auth.upflame.in/get/{token} can act as a verification endpoint for external systems (e.g., email verification or one-time login link). Design it to accept signed short-lived tokens and redirect to tenant app.

Cookie & session rules:

Access token in-memory (Bearer) for APIs.

Refresh token stored as HttpOnly, Secure, SameSite=Strict cookie scoped to the auth domain.

For cross-domain flows, use Authorization code + PKCE and store refresh tokens on server when possible.

RBAC + ABAC (Design & Tables)

Philosophy: RBAC for coarse-grain permissions; ABAC for conditional, attribute-based decisions. Build policy hooks that evaluate attributes at runtime.

Core tables (Phase-1)
tenants (id, name, domain, config_json, created_at, ...)
users (id, tenant_id, email, password_hash, status, primary_role_id, mfa_enabled, created_at)
roles (id, tenant_id, name, description, is_system)
permissions (id, name, resource, action, description)
role_permission (role_id, permission_id)
user_role (user_id, role_id)  -- allow multi roles
policies (id, tenant_id, name, target_json, rules_json, enabled) -- ABAC

ABAC evaluation

Implement an evaluation layer: input = user attributes, resource attributes, action, environment (time, IP). Policies are JSON rules (e.g., check user.attribute X and resource.owner == user.id).

Example permission names

booking.create, booking.update, booking.cancel

pandit.onboard.read, pandit.onboard.update, pandit.onboard.approve

PANDIT ONBOARDING WORKFLOW (Multi-step engine)

Steps (typical):

Registration — basic profile + contact

Document upload — ID, photo, address proof

Background verification — external vendor check (async)

Bank details — KYC for payouts

Service setup — puja types, pricing, availability

Training/Assessment — internal check, quiz

Final review & approval — admin approval

Workflow engine design (phase-1 minimal):

Tables:

onboarding (id, pandit_id, tenant_id, current_step, status, created_at, updated_at)
onboarding_steps (id, onboarding_id, step_key, data_json, status, attempted_at, completed_at)
onboarding_events (id, onboarding_id, event_type, payload_json, created_at)


State machine: simple state machine (PROCESSING -> STEP_N -> AWAITING_VERIFICATION -> APPROVED/REJECTED). Use code-driven state transitions and store audit logs.

APIs:

POST /pandits/onboarding → create onboarding

GET /pandits/onboarding/{id} → status & steps

POST /pandits/onboarding/{id}/step/{stepKey} → submit step data

POST /pandits/onboarding/{id}/action/approve → admin approve

Webhooks to notify external verifiers and callbacks when verification completes

Behavior:

Each step returns “next step” and allowed actions.

Admin routes to review and override steps.

API DESIGN — Key Endpoints (Phase-1, versioned)

Base path: /api/v1

Auth

POST /api/v1/auth/login (or OIDC /authorize flow)

POST /api/v1/auth/token (token exchange)

POST /api/v1/auth/logout

POST /api/v1/auth/refresh

GET /api/v1/auth/me

Tenant / App

POST /api/v1/tenants (admin)

GET /api/v1/tenants/{id}

Users & RBAC

POST /api/v1/users (create)

GET /api/v1/users/{id}

POST /api/v1/users/{id}/roles

GET /api/v1/roles

POST /api/v1/roles

POST /api/v1/permissions

Pandit onboarding

POST /api/v1/pandits (register)

POST /api/v1/pandits/onboarding

GET /api/v1/pandits/onboarding/{id}

POST /api/v1/pandits/onboarding/{id}/step/{step}

Booking (minimal)

POST /api/v1/bookings → returns booking_id + optimistic status PROCESSING

GET /api/v1/bookings/{id}/status → returns final status

Admin

GET /api/v1/admin/onboardings?status=processing

POST /api/v1/admin/onboardings/{id}/approve

Examples — Booking creation response (optimistic)

POST /api/v1/bookings
{
  "pandit_id": "p_123",
  "service_id": "s_12",
  "user_id": "u_1",
  "scheduled_at": "2025-11-20T10:00:00Z",
  "metadata": {}
}
Response 201
{
  "booking_id":"b_xyz",
  "status":"PROCESSING",
  "message":"Booking received. Confirmation will be sent."
}

TOKEN & SESSION MANAGEMENT (secure defaults)

Access tokens: short-lived (e.g., 5–15 minutes) JWT signed by auth server. Contain sub, tid, scp (scopes), roles minimal claim.

Refresh tokens: rotating, stored server-side or as HttpOnly Secure cookie. Implement refresh token rotation to avoid replay.

Revocation: maintain revoked_tokens table or token version in user record (token_version) to invalidate tokens globally.

Session cookie settings: Secure, HttpOnly, SameSite=Strict, path scoped to auth domain.

DOMAIN & DNS RULES (custom login on tenant domain)

Option A (preferred) – CNAME / reverse proxy:

Tenant sets CNAME auth.tenant.com → auth.upflame.in (or edge).

Edge server (Nginx or CDN) forwards Host header to auth service.

Auth loads tenant by host header to render branding.

Option B – Hosted login per tenant:

Central auth.upflame.in/login?tenant=tenantA less elegant, but workable.

TLS: use wildcard or automated certificate issuance (ACME/Let’s Encrypt) via edge to serve tenant subdomains.

SECURITY & COMPLIANCE (Phase-1 checklist)

TLS everywhere (redirect HTTP→HTTPS).

CSP, HSTS, X-Frame-Options, X-Content-Type-Options headers.

Input validation + rate limiting (per IP, per user).

Brute force protection (lockout + exponential backoff).

Password policy & secure hashing (bcrypt/argon2).

MFA: TOTP implementation for sensitive actions.

Audit logs for onboarding & admin actions (immutable).

Data encryption at rest for sensitive fields (bank details, SSNs) or store in vault.

GDPR/compliance: consent recording, data retention policies, export/delete endpoints.

Vulnerability scanning & dependency checks.

Secrets management (Vault / cloud secret manager).



0️⃣ PHASE-1 GOAL (High-Level)

Build a strong, unbreakable foundation that can scale into:

Multiple apps (admin panel, customer panel, partner app, vendor app, etc.)

Multiple role families (pandits, vendors, astrologers, service providers, internal staff)

Multiple tenants/domains

SSO-based access across tenants

Multi-step onboarding for ANY role

Enterprise-compliant RBAC + ABAC + Policy Engine

Plug-and-play architecture for future modules

Secure, versioned, modular API

Not building business logic yet — only the enterprise base infrastructure.

1️⃣ SYSTEM ARCHITECTURE (MNC STANDARD)
                   ┌──────────────────────────┐
                   │   Edge Layer (Nginx/CDN) │
                   │ Domain Routing / CNAME   │
                   └─────────────▲────────────┘
                                 │ Host Header
                                 │
                ┌────────────────┴────────────────┐
                │                                 │
┌───────────────────────────┐       ┌───────────────────────────┐
│      AUTH SERVICE         │       │       API SERVICE         │
│  (SSO / OIDC Provider)    │       │  (Business Logic Layer)    │
│  auth.upflame.in          │       │  api.upflame.in            │
└───────────────────────────┘       └───────────────────────────┘
                │                                 │
                │ Tokens / JWT / Sessions         │
                │                                 │
                └──────────────────┬──────────────┘
                                   │
                       ┌───────────▼────────────┐
                       │        MySQL Cluster   │
                       │  Tenant-aware schema   │
                       └───────────▲────────────┘
                                   │
                        ┌──────────┴─────────┐
                        │   Object Storage    │
                        │  (S3/KYC Docs)      │
                        └──────────▲─────────┘
                                   │
                        ┌──────────┴────────┐
                        │     Monitoring     │
                        │  Sentry / Grafana  │
                        └────────────────────┘


Why this is MNC-grade:

Clear separation of concerns

Auth is independent (scales like Auth0/Okta)

API is stateless

Tenant-awareness is centralized

Domain routing done at edge

Fully compatible with multi-app + multi-tenant architecture

2️⃣ DOMAIN-DRIVEN DESIGN (DDD STRUCTURE)
Strategic Domains:

Identity Domain

Access Control Domain

Tenant Domain

Workflow Domain

Provider Domain

User Domain

Booking Domain

Notification Domain

Bounded Contexts:
Identity       -> Auth, SSO, MFA, Sessions
AccessControl  -> RBAC, ABAC, Policies
Tenant         -> Domains, Configs
Workflow       -> Step Engine, Approvals, Reviews
Provider       -> Pandits, Vendors, Astrologers, Partners
User           -> Customer Accounts
Booking        -> Service Listings & Bookings
Notifications  -> Emails, SMS, Alerts


This ensures MNC-scale modularity.

3️⃣ TENANCY MODEL (ENTERPRISE STANDARD)
✓ Multi-tenant, row-level isolation (Phase-1)
✓ Future compatible with schema-per-tenant (Phase-2)
Tenant resolution hierarchy:

Host header

X-Tenant-ID API header

Fallback query param (last fallback)

Tenant Models:
tenants
tenant_domains
tenant_configs
tenant_modules


Each tenant can have multiple domains:

auth.tenant.com
app.tenant.com
partner.tenant.com

4️⃣ AUTH / SSO SERVICE (ENTERPRISE-GRADE)
Protocols supported:

OAuth2

OIDC

PKCE

JWT (Access Token)

Refresh Token Rotation

MFA/TOTP

Device-based trust sessions

2-step email verification

Brute-force rate-limit

Zero-trust security model

Endpoints (OIDC-compliant)
/authorize
/token
/userinfo
/session
/logout
/introspect
/revoke
/.well-known/openid-configuration
/get/<verification-purpose>

Features:

Custom login page rendering based on host

Supports auth.<tenant-domain>

Dynamic branding injection

Domain lock validation to prevent tenant takeover

Rotating signing keys (JWKS)

5️⃣ RBAC + ABAC + POLICY ENGINE (Hybrid Access System)
RBAC — Roles & Permissions

Tenant-specific roles

Global system roles

Permissions abstracted by resource + action

Fine-grain mapping: resource.action = "booking.create"

ABAC — Attribute Based

Attributes include:

user.city

user.verification_level

resource.owner_id

resource.tenant_id

environment.ip

environment.time

Policy Engine

Structured policies:

{
  "effect": "allow",
  "resource": "booking",
  "action": "update",
  "conditions": {
      "user.city": "== resource.city",
      "user.verification_level": ">= 2"
  }
}


Evaluated via policy compiler + runtime evaluator.

6️⃣ UNIVERSAL WORKFLOW ENGINE (MNC CLASS)

Your biggest advantage.

Use a workflow engine that ANY role can use:

Pandit onboarding

Vendor onboarding

Partner registration

Customer onboarding

Employee onboarding

KYC verification

Service listing creation

Core Entities:
workflow_definitions     -> steps, rules
workflows                -> instance per entity
workflow_steps           -> actual step states
workflow_events          -> audit logs
workflow_forms           -> dynamic UI fields
workflow_actions         -> approve/reject/pushback

Step types supported:

Form input

Document upload

Video submission

Background check

Verification by admin

Auto-step (system-run)

Conditional step

Step transitions:
pending → in_progress → submitted → verified → approved/rejected

7️⃣ ROLE-FAMILY ARCHITECTURE (EXTREMELY IMPORTANT)

Do NOT treat Pandits as a special case.

Use role families:

RoleFamily::Provider
    - Pandit
    - Astrologer
    - Vendor
    - Partner
    - Tutor
RoleFamily::Internal
    - Admin
    - Ops
    - Finance
    - Support
RoleFamily::Customer
    - User
    - CorporateClient


Each family has:

Its own workflow definition

Its own permission matrix

Its own SSO application-level scopes

This makes the system future-proof.

8️⃣ API LAYER — MNC STYLE
URL Versioning:

https://api.upflame.in/v1/

Modules:
/auth
/tenants
/users
/roles
/permissions
/policies
/workflows
/providers
/bookings
/admin

Standards:

✔ OpenAPI 3.1
✔ Idempotency keys for create APIs
✔ Rate limiting per IP, per user, per tenant
✔ Input validation with JOI-like schema
✔ Response envelope format:

{
  "status": "success",
  "data": {...},
  "meta": {...}
}

9️⃣ BACKEND CODE STRUCTURE (ENTERPRISE)
Domain-Driven Modules:
app/
 ├── Domains/
 │    ├── Identity/
 │    ├── Access/
 │    ├── Tenant/
 │    ├── Workflow/
 │    ├── Provider/
 │    ├── Booking/
 │    └── Notification/
 ├── Infrastructure/
 │    ├── DB/
 │    ├── Cache/
 │    ├── Queue/
 │    ├── Security/
 │    ├── SSO/
 │    └── PolicyEngine/
 ├── Application/
 │    ├── Commands/
 │    ├── Queries/
 │    ├── Services/
 ├── Interfaces/
 │    ├── Http/
 │    ├── CLI/
 │    └── Consumers/
 └── Support/

Patterns Used:

Repository pattern

CQRS (Commands & Queries split)

Service layer

Event-driven architecture (non-critical events)

Policy middleware

Multi-layer validation

🔟 PHASE-1 DELIVERY PLAN (Engineering Execution)

The correct MNC standard is Infrastructure First → Identity → Access → Workflow → Business Modules.

📌 PHASE-1 BUILD ORDER (Mandatory)
STEP 1 — Foundation

12-Factor environment setup

DB schema bootstrapping

Tenant tables + resolver

Domain mapping engine

Config loader

Logging/monitoring baseline

STEP 2 — Identity

OAuth2/OIDC server

Token service

MFA TOTP

Device trust sessions

Email/phone verification engine

STEP 3 — Tenant Security

Role families

Scopes & claims

Token enrichment

Tenant boundary protection

STEP 4 — Access Control

RBAC engine

Permission matrix

ABAC system (Phase-1 skeleton)

Policy evaluator

Admin endpoints

STEP 5 — Workflow Engine

Core workflow definitions

Execution engine

Step transitions

Workflow APIs

Audit logs

Generic multi-role onboarding

STEP 6 — Provider Module

Provider base model

Provider onboarding using workflow engine

Role assignment

Document/KYC API

STEP 7 — Customer/User Module

End user onboarding

Basic profile

Authentication

STEP 8 — Booking Core

Booking creation (optimistic)

Booking status

Minimal analytics

STEP 9 — Hardening

Rate limits

API security

Input sanitization

TLS everywhere

CI/CD

Staging environment

OpenAPI documentation

⭐ FINAL OUTPUT: THIS SYSTEM WILL SUPPORT
Multi-app

(Provider app, User app, Admin app, Partner app, etc.)

Multi-role

(Any number of role families, plug-in roles, custom roles)

Multi-domain

(auth.tenant.com, app.tenant.com, partner.tenant.com)

Multi-tenant

(with tenant-level isolation and custom workflows)

SSO

(Enterprise login compatible with any future Upflame product)

Universal Workflow Engine

(For onboarding, KYC, approvals, etc.)

RBAC + ABAC Hybrid

(Google/IAM style authorization)

This is MNC-grade.
This is enterprise-ready.
This is scalable to lakhs of users.
👉 If you want next, I can generate:
🔹 Full database schema (enterprise optimized)
🔹 Full Laravel folder structure & code stubs
🔹 SSO sequence diagrams (authorization code + PKCE)
🔹 Role & Permission matrix for every role family
🔹 Workflow engine data model + algorithms
🔹 API v1 OpenAPI documentation

Just tell me:
“Generate the Phase-1 Schema & Folder Structure.”