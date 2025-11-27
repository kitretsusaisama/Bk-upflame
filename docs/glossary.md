# Glossary

> **Terms and definitions used throughout the platform**

## A

**Access Log**  
Audit trail record of user actions (create, update, delete operations) stored in `access_logs` table.

**API Token**  
Laravel Sanctum personal access token for API authentication. Format: `1|abc123...`

**Audit Trail**  
Complete history of system actions for compliance and debugging.

## B

**Booking**  
Scheduled appointment between a user (customer) and a provider for a specific service.

**Bounded Context**  
In Domain-Driven Design, a logical boundary within which a domain model is defined. Examples: Access, Booking, Workflow.

## C

**CSRF (Cross-Site Request Forgery)**  
Security vulnerability where unauthorized commands are transmitted. Laravel protects with CSRF tokens.

**Cache**  
Temporary storage of frequently accessed data. Platform uses database or Redis for caching.

## D

**Dashboard**  
Main interface after login. Dynamically renders based on user's highest-priority role.

**Domain**  
Business logic area organized by functionality (e.g., `app/Domains/Booking/`).

**DDD (Domain-Driven Design)**  
Architectural approach organizing code by business domains rather than technical layers.

## E

**Eloquent**  
Laravel's ORM (Object-Relational Mapper) for database interactions.

**Event**  
Action that occurred in the system (e.g., `BookingCreated`, `UserLoggedIn`).

## F

**Foreign Key**  
Database constraint linking tables. Example: `tenant_id` in `users` table references `tenants.id`.

## G

**Global Scope**  
Eloquent feature automatically adding WHERE clauses to queries. Used for tenant isolation.

**Guard**  
Laravel authentication driver. Platform uses `web` (session) and `api` (Sanctum) guards.

## I

**Impersonation**  
Ability for admins to temporarily login as another user for support/debugging.

**Index**  
Database structure improving query performance. All `tenant_id` columns are indexed.

## L

 **Listener**  
Class that handles domain events. Example: `SendBookingConfirmation` listens to `BookingCreated`.

## M

**Middleware**  
HTTP request filter. Examples: authentication, tenant identification, permission checks.

**Migration**  
Database schema change file. Located in `database/migrations/`.

**MFA (Multi-Factor Authentication)**  
Additional security layer beyond password (TOTP, SMS). Infrastructure exists but not fully implemented.

## P

**Permission**  
Specific capability like `view-user` or `create-booking`. Stored in `permissions` table.

**Pivot Table**  
Many-to-many relationship table. Examples: `user_roles`, `role_permissions`.

**Policy**  
Laravel authorization class for complex permission logic. Example: `BookingPolicy`.

**Priority (Role)**  
Numeric value determining which role takes precedence. Lower = higher priority.

## Q

**Queue**  
Background job processing system. Platform uses database or Redis for job storage.

## R

**RBAC (Role-Based Access Control)**  
Permission system where users have roles, and roles have permissions.

**Repository**  
Data access layer pattern. Example: `UserRepository` handles all User model queries.

**Role**  
Named collection of permissions. Examples: Super Admin, Tenant Admin, Provider.

**Route Model Binding**  
Laravel feature automatically resolving model instances from route parameters.

## S

**Sanctum**  
Laravel package for API token authentication.

**Scope**  
Menu visibility level: `platform` (Super Admin only), `tenant` (tenant users), or `both`.

**Seeder**  
Database population script. Located in `database/seeders/`.

**Service**  
Business logic class. Examples: `DashboardService`, `ImpersonationService`.

**Session**  
Stateful HTTP connection. Platform uses database-backed sessions.

**Soft Delete**  
Marking records as deleted without physical removal (`deleted_at` timestamp).

**SSO (Single Sign-On)**  
Authentication via external provider (Google, Azure AD, etc.).

## T

**Tenant**  
Independent organization using the platform. Complete data isolation between tenants.

**Tenant Domain**  
Custom domain mapped to a tenant (e.g., `clinic.example.com`).

**Tenant Scoped**  
Model/query automatically filtered by current tenant context.

**TOTP (Time-Based One-Time Password)**  
MFA method using apps like Google Authenticator.

## U

**ULID (Universally Unique Lexicographically Sortable Identifier)**  
26-character ID format used for all primary keys. Time-ordered and globally unique.

**UUID (Universally Unique Identifier)**  
128-bit identifier. Platform uses ULID variant for better performance.

## V

**Validation**  
Input checking via Laravel Form Requests. Example: `CreateUserRequest`.

## W

**Widget**  
Dashboard component displaying specific data. Controlled by `dashboard_widgets` table.

**Workflow**  
Multi-step approval/automation process. Defined in `workflow_definitions` table.

## X

**XSS (Cross-Site Scripting)**  
Security vulnerability where malicious scripts are injected. Laravel's Blade automatically escapes output.

## Cross-Links

- [Architecture](architecture.md) - Architectural terms
- [RBAC](rbac.md) - Permission terminology
- [Tenancy](tenancy.md) - Multi-tenancy concepts
- [Database Schema](database-schema.md) - Database terminology
