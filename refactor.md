Enterprise-Grade Multi-Tenant Identity, Access, and Operations Platform

This project aims to build a centralized backend platform that supports multiple tenants (companies), multiple user roles, dynamic permissions, custom SSO, OTP login, advanced workflows, and operations modules — all from a single codebase.

It is designed to operate at enterprise scale with high security, strong isolation, and modular expansion capability.

---

⭐ CORE PURPOSE OF THE PLATFORM

To create a powerful, flexible, and secure identity and operations backend that multiple businesses/tenants can use — each with their own users, roles, permissions, workflows, bookings, providers, notifications, and authentication rules.

It is not a single-company backend.
It is a multi-organization SaaS backend.

Tenants (customers) can:

Sign up or be created by super-admin

Configure their own login settings (email/password, OTP, custom SSO)

Define their own roles & permissions

Control access to all modules (Provider, Booking, Workflow, Notifications)

Manage their own data fully isolated from other tenants

---

⭐ WHAT THE SYSTEM WILL PROVIDE

1 — Multi-Tenant Identity Platform

A complete identity layer with:

Email + Password login

OTP-based login

Custom SSO (enterprise login)

MFA-ready architecture

Token-based API authentication

Session-based Web authentication

User profiles

User sessions tracking

Strong audit logging

2 — Dynamic RBAC System

Every tenant can create:

Custom Roles

Custom Permissions

Custom Policies

Assign roles to users

Assign permissions dynamically

An advanced Access Evaluation Engine caches permissions and enforces rule-based access control.

3 — Enterprise Tenant Management

Each tenant (business) has:

Own users

Own domains

Own SSO configuration

Own modules (booking, workflow, provider)

Own settings (OTP, email, flags)

Row-level isolation on all tables

Configurable onboarding flow

4 — SSO Integration (Custom Architecture)

Not tied to Okta/Microsoft —
Supports any enterprise identity provider through adapters.

Core features:

Custom Authorization Code flow

Pluggable SSO adapter interface

External user → internal user mapping

Group → role mapping

Configurable per tenant

This allows corporate customers to integrate their internal login systems easily.

5 — Booking + Provider + Workflow Modules

Each tenant can:

Manage providers (doctors, experts, vendors)

Manage bookings + history

Define workflows and workflow steps

Handle workflow forms & events

Use notifications and templates

These modules remain tenant-scoped, role-protected, and permission-controlled.

6 — API + Web From One Backend

The platform serves:

API (Mobile, SPA, Integrations)

JSON responses

Token-based auth

Versioned endpoints

Web (Dashboards)

Blade views

Session auth

Multi-role dashboards:

SuperAdmin

TenantAdmin

Provider

Ops

Customer

Both share the same domain logic.

---

⭐ BIG PICTURE — WHAT WE ARE BUILDING

Imagine a system like:

Okta-level Identity + SSO

Auth0-level RBAC

Shopify-level multi-tenant backend

Calendly-like booking engine

Notion-like workflow engine

All combined into one unified platform —
customized for your operations modules.

---

⭐ TECHNICAL TARGET

Backend

Laravel 12

Multi-tenant architecture

Domain-driven modular structure

Service layer + Actions + Contracts

Strong caching (Redis)

Queue-based OTP & notifications

Authentication

Email/password

OTP

Custom SSO adapters

Session for web

Sanctum tokens for API

MFA-ready

Audited & rate-limited

Authorization

Dynamic roles

Tenant-based permissions

Policy rule engine

Resource-level access

Security

Tenant isolation

Token rotation

Request-level audit logs

Permission caching

Safe migrations

Anti-abuse protections

Scaling

Horizontal scale-ready

Redis caching

Queues

Modular domains

Proper indexing and migrations

---

⭐ WHAT THE FINAL SYSTEM ENABLES

When finished, the platform will allow:

✔ Support for thousands of businesses (tenants)

Each tenant gets:

Their own users

Their own roles

Their own SSO

Their own domains

Their own modules

Their own workflows

✔ Secure cross-tenant isolation

✔ Fully customizable identity flows

(signup, login, sso, otp, verification)

✔ Full control over access

(per tenant, per role, per permission, dynamic mapping)

✔ Scalable architecture

that can support millions of requests/month.

✔ All business modules built on top of a strong identity core

Providers

Bookings

Workflow engine

Notifications

Everything consistently protected with permissions & policies.

---

⭐ FINAL SUMMARY (short version)

We are building a Multi-Tenant Enterprise Backend Platform that unifies Authentication, Authorization, SSO, RBAC, Provider Management, Bookings, Workflow, Notifications, and Tenant Administration — designed with enterprise security, scalability, and modularity from day one.

This will function as a full SaaS backend where each company (tenant) gets:

its own login settings

its own identities and roles

its own modules and data

its own SSO provider

full isolation and high security

And your Laravel codebase becomes a modular, scalable, enterprise-ready identity + business operations engine.

Laravel 12, MySQL with API + WEB BOTH, Also Here Just list structure, Folder/files what is contain file/func. 

# Enterprise Multi-Tenant Platform - Complete Structure

## 📁 Project Root Structure

```
project-root/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env
├── artisan
├── composer.json
└── package.json
```

---

## 📁 `/app` - Application Core

```
app/
├── Console/
│   ├── Commands/
│   │   ├── Tenant/
│   │   │   ├── CreateTenantCommand.php          # Create new tenant
│   │   │   ├── SuspendTenantCommand.php         # Suspend tenant
│   │   │   └── MigrateTenantCommand.php         # Run tenant migrations
│   │   ├── User/
│   │   │   ├── CreateSuperAdminCommand.php      # Bootstrap super admin
│   │   │   └── ResetUserPasswordCommand.php     # Password reset CLI
│   │   ├── Cache/
│   │   │   ├── WarmPermissionCacheCommand.php   # Warm RBAC cache
│   │   │   └── ClearTenantCacheCommand.php      # Clear tenant cache
│   │   └── System/
│   │       ├── GenerateApiKeysCommand.php       # Generate API keys
│   │       └── AuditCleanupCommand.php          # Clean old audit logs
│   └── Kernel.php                                # Console kernel
│
├── Domain/                                        # Domain-Driven Design modules
│   ├── Shared/                                   # Shared kernel
│   │   ├── ValueObjects/
│   │   │   ├── Email.php                        # Email value object
│   │   │   ├── PhoneNumber.php                  # Phone value object
│   │   │   ├── UUID.php                         # UUID wrapper
│   │   │   └── Money.php                        # Money value object
│   │   ├── Traits/
│   │   │   ├── HasUuid.php                      # UUID trait
│   │   │   ├── TenantScoped.php                 # Tenant scoping
│   │   │   └── Auditable.php                    # Audit trail
│   │   ├── Contracts/
│   │   │   ├── Repository.php                   # Base repository
│   │   │   ├── Service.php                      # Base service
│   │   │   └── CacheableInterface.php           # Cache interface
│   │   └── Exceptions/
│   │       ├── DomainException.php              # Base domain exception
│   │       ├── ValidationException.php          # Validation errors
│   │       └── BusinessRuleException.php        # Business rule violations
│   │
│   ├── Tenant/                                   # Tenant Management Domain
│   │   ├── Models/
│   │   │   ├── Tenant.php                       # Tenant model
│   │   │   ├── TenantDomain.php                 # Custom domains
│   │   │   ├── TenantSettings.php               # Tenant configuration
│   │   │   └── TenantSubscription.php           # Subscription tracking
│   │   ├── Services/
│   │   │   ├── TenantService.php                # Tenant CRUD operations
│   │   │   ├── TenantProvisionService.php       # Provision tenant
│   │   │   ├── TenantIsolationService.php       # Enforce isolation
│   │   │   └── TenantOnboardingService.php      # Onboarding flow
│   │   ├── Actions/
│   │   │   ├── CreateTenantAction.php           # Create tenant action
│   │   │   ├── UpdateTenantSettingsAction.php   # Update settings
│   │   │   ├── SuspendTenantAction.php          # Suspend action
│   │   │   └── DeleteTenantAction.php           # Delete tenant
│   │   ├── Repositories/
│   │   │   ├── TenantRepository.php             # Tenant repo
│   │   │   └── TenantSettingsRepository.php     # Settings repo
│   │   ├── Events/
│   │   │   ├── TenantCreated.php                # Tenant created event
│   │   │   ├── TenantSuspended.php              # Tenant suspended
│   │   │   └── TenantDeleted.php                # Tenant deleted
│   │   ├── Listeners/
│   │   │   ├── ProvisionTenantResources.php     # Create resources
│   │   │   ├── SendTenantWelcomeEmail.php       # Welcome email
│   │   │   └── CleanupTenantData.php            # Cleanup on delete
│   │   └── Policies/
│   │       └── TenantPolicy.php                 # Tenant access policy
│   │
│   ├── Identity/                                 # Identity & Auth Domain
│   │   ├── Models/
│   │   │   ├── User.php                         # User model
│   │   │   ├── UserProfile.php                  # Extended profile
│   │   │   ├── UserSession.php                  # Session tracking
│   │   │   ├── UserDevice.php                   # Device fingerprinting
│   │   │   ├── PasswordReset.php                # Password reset tokens
│   │   │   ├── OtpCode.php                      # OTP codes
│   │   │   ├── EmailVerification.php            # Email verification
│   │   │   └── LoginAttempt.php                 # Login attempt tracking
│   │   ├── Services/
│   │   │   ├── AuthenticationService.php        # Core auth logic
│   │   │   ├── RegistrationService.php          # User registration
│   │   │   ├── PasswordService.php              # Password operations
│   │   │   ├── OtpService.php                   # OTP generation/verify
│   │   │   ├── EmailVerificationService.php     # Email verification
│   │   │   ├── SessionService.php               # Session management
│   │   │   ├── TokenService.php                 # API token management
│   │   │   └── MfaService.php                   # MFA operations
│   │   ├── Actions/
│   │   │   ├── RegisterUserAction.php           # Register user
│   │   │   ├── LoginUserAction.php              # Login action
│   │   │   ├── LoginWithOtpAction.php           # OTP login
│   │   │   ├── LogoutUserAction.php             # Logout
│   │   │   ├── VerifyEmailAction.php            # Verify email
│   │   │   ├── ResetPasswordAction.php          # Password reset
│   │   │   ├── GenerateOtpAction.php            # Generate OTP
│   │   │   ├── VerifyOtpAction.php              # Verify OTP
│   │   │   └── EnableMfaAction.php              # Enable MFA
│   │   ├── Repositories/
│   │   │   ├── UserRepository.php               # User repo
│   │   │   ├── OtpRepository.php                # OTP repo
│   │   │   └── SessionRepository.php            # Session repo
│   │   ├── Events/
│   │   │   ├── UserRegistered.php               # User registered
│   │   │   ├── UserLoggedIn.php                 # Login event
│   │   │   ├── UserLoggedOut.php                # Logout event
│   │   │   ├── PasswordResetRequested.php       # Password reset
│   │   │   ├── OtpGenerated.php                 # OTP sent
│   │   │   └── MfaEnabled.php                   # MFA enabled
│   │   ├── Listeners/
│   │   │   ├── SendVerificationEmail.php        # Send verification
│   │   │   ├── SendOtpEmail.php                 # Send OTP
│   │   │   ├── LogLoginAttempt.php              # Log attempt
│   │   │   ├── CreateUserProfile.php            # Create profile
│   │   │   └── NotifyAdminNewUser.php           # Notify admin
│   │   ├── Policies/
│   │   │   └── UserPolicy.php                   # User policy
│   │   └── Enums/
│   │       ├── UserStatus.php                   # Active/Suspended/etc
│   │       ├── LoginMethod.php                  # Email/OTP/SSO
│   │       └── MfaMethod.php                    # SMS/Email/App
│   │
│   ├── SSO/                                      # SSO Integration Domain
│   │   ├── Models/
│   │   │   ├── SsoProvider.php                  # SSO provider config
│   │   │   ├── SsoConnection.php                # User SSO link
│   │   │   ├── SsoSession.php                   # SSO session
│   │   │   └── SsoGroupMapping.php              # Group to role mapping
│   │   ├── Services/
│   │   │   ├── SsoService.php                   # Core SSO orchestration
│   │   │   ├── SsoProviderFactory.php           # Provider factory
│   │   │   ├── SsoCallbackService.php           # Handle callbacks
│   │   │   ├── SsoUserMappingService.php        # Map external to internal
│   │   │   └── SsoGroupSyncService.php          # Sync groups to roles
│   │   ├── Adapters/                             # SSO Provider Adapters
│   │   │   ├── SsoAdapterInterface.php          # Adapter contract
│   │   │   ├── GenericOAuthAdapter.php          # Generic OAuth2
│   │   │   ├── SamlAdapter.php                  # SAML 2.0
│   │   │   ├── OpenIdConnectAdapter.php         # OIDC
│   │   │   ├── AzureAdAdapter.php               # Azure AD
│   │   │   └── CustomAdapter.php                # Custom enterprise
│   │   ├── Actions/
│   │   │   ├── InitiateSsoLoginAction.php       # Start SSO flow
│   │   │   ├── HandleSsoCallbackAction.php      # Process callback
│   │   │   ├── LinkSsoAccountAction.php         # Link SSO to user
│   │   │   ├── UnlinkSsoAccountAction.php       # Unlink SSO
│   │   │   └── SyncSsoGroupsAction.php          # Sync groups
│   │   ├── Repositories/
│   │   │   ├── SsoProviderRepository.php        # Provider repo
│   │   │   └── SsoConnectionRepository.php      # Connection repo
│   │   ├── Events/
│   │   │   ├── SsoLoginInitiated.php            # SSO login start
│   │   │   ├── SsoLoginCompleted.php            # SSO login success
│   │   │   ├── SsoAccountLinked.php             # Account linked
│   │   │   └── SsoGroupsSynced.php              # Groups synced
│   │   └── Exceptions/
│   │       ├── SsoException.php                 # Base SSO exception
│   │       ├── InvalidProviderException.php     # Invalid provider
│   │       └── SsoCallbackException.php         # Callback error
│   │
│   ├── Authorization/                            # RBAC & Permissions Domain
│   │   ├── Models/
│   │   │   ├── Role.php                         # Role model
│   │   │   ├── Permission.php                   # Permission model
│   │   │   ├── Policy.php                       # Policy rules
│   │   │   ├── RoleUser.php                     # Role assignments
│   │   │   ├── PermissionRole.php               # Permission assignments
│   │   │   └── ResourcePermission.php           # Resource-level perms
│   │   ├── Services/
│   │   │   ├── RoleService.php                  # Role CRUD
│   │   │   ├── PermissionService.php            # Permission CRUD
│   │   │   ├── PolicyService.php                # Policy management
│   │   │   ├── AccessEvaluationService.php      # Evaluate access
│   │   │   ├── PermissionCacheService.php       # Cache permissions
│   │   │   └── RoleAssignmentService.php        # Assign roles
│   │   ├── Actions/
│   │   │   ├── CreateRoleAction.php             # Create role
│   │   │   ├── AssignRoleAction.php             # Assign to user
│   │   │   ├── RevokeRoleAction.php             # Revoke role
│   │   │   ├── CreatePermissionAction.php       # Create permission
│   │   │   ├── AttachPermissionAction.php       # Attach to role
│   │   │   ├── CreatePolicyAction.php           # Create policy
│   │   │   └── EvaluateAccessAction.php         # Check access
│   │   ├── Repositories/
│   │   │   ├── RoleRepository.php               # Role repo
│   │   │   ├── PermissionRepository.php         # Permission repo
│   │   │   └── PolicyRepository.php             # Policy repo
│   │   ├── Contracts/
│   │   │   ├── AccessControlInterface.php       # AC interface
│   │   │   └── PolicyEngineInterface.php        # Policy engine
│   │   ├── Events/
│   │   │   ├── RoleCreated.php                  # Role created
│   │   │   ├── RoleAssigned.php                 # Role assigned
│   │   │   ├── PermissionCreated.php            # Permission created
│   │   │   └── AccessDenied.php                 # Access denied event
│   │   ├── Middleware/
│   │   │   ├── CheckPermission.php              # Permission check
│   │   │   ├── CheckRole.php                    # Role check
│   │   │   └── CheckPolicy.php                  # Policy check
│   │   └── Enums/
│   │       ├── PermissionType.php               # Read/Write/Delete/etc
│   │       └── ResourceType.php                 # Provider/Booking/etc
│   │
│   ├── Provider/                                 # Provider Management Domain
│   │   ├── Models/
│   │   │   ├── Provider.php                     # Provider model
│   │   │   ├── ProviderProfile.php              # Extended profile
│   │   │   ├── ProviderAvailability.php         # Availability slots
│   │   │   ├── ProviderSpecialty.php            # Specialties
│   │   │   ├── ProviderDocument.php             # Documents/certs
│   │   │   └── ProviderRating.php               # Ratings
│   │   ├── Services/
│   │   │   ├── ProviderService.php              # Provider CRUD
│   │   │   ├── ProviderAvailabilityService.php  # Manage availability
│   │   │   ├── ProviderSearchService.php        # Search providers
│   │   │   └── ProviderRatingService.php        # Rating logic
│   │   ├── Actions/
│   │   │   ├── CreateProviderAction.php         # Create provider
│   │   │   ├── UpdateProviderAction.php         # Update provider
│   │   │   ├── SetAvailabilityAction.php        # Set availability
│   │   │   ├── RateProviderAction.php           # Rate provider
│   │   │   └── ArchiveProviderAction.php        # Archive provider
│   │   ├── Repositories/
│   │   │   └── ProviderRepository.php           # Provider repo
│   │   ├── Events/
│   │   │   ├── ProviderCreated.php              # Provider created
│   │   │   ├── ProviderUpdated.php              # Provider updated
│   │   │   └── ProviderRated.php                # Rating added
│   │   └── Policies/
│   │       └── ProviderPolicy.php               # Provider access
│   │
│   ├── Booking/                                  # Booking Management Domain
│   │   ├── Models/
│   │   │   ├── Booking.php                      # Booking model
│   │   │   ├── BookingSlot.php                  # Time slots
│   │   │   ├── BookingHistory.php               # Status history
│   │   │   ├── BookingCancellation.php          # Cancellation record
│   │   │   └── BookingPayment.php               # Payment tracking
│   │   ├── Services/
│   │   │   ├── BookingService.php               # Booking CRUD
│   │   │   ├── SlotAvailabilityService.php      # Check availability
│   │   │   ├── BookingConfirmationService.php   # Confirm booking
│   │   │   ├── BookingCancellationService.php   # Cancel booking
│   │   │   └── BookingReminderService.php       # Send reminders
│   │   ├── Actions/
│   │   │   ├── CreateBookingAction.php          # Create booking
│   │   │   ├── ConfirmBookingAction.php         # Confirm
│   │   │   ├── CancelBookingAction.php          # Cancel
│   │   │   ├── RescheduleBookingAction.php      # Reschedule
│   │   │   └── CompleteBookingAction.php        # Mark complete
│   │   ├── Repositories/
│   │   │   └── BookingRepository.php            # Booking repo
│   │   ├── Events/
│   │   │   ├── BookingCreated.php               # Booking created
│   │   │   ├── BookingConfirmed.php             # Confirmed
│   │   │   ├── BookingCancelled.php             # Cancelled
│   │   │   ├── BookingCompleted.php             # Completed
│   │   │   └── BookingReminder.php              # Reminder event
│   │   ├── Listeners/
│   │   │   ├── SendBookingConfirmation.php      # Send confirmation
│   │   │   ├── NotifyProviderNewBooking.php     # Notify provider
│   │   │   └── UpdateProviderAvailability.php   # Update availability
│   │   ├── Policies/
│   │   │   └── BookingPolicy.php                # Booking access
│   │   └── Enums/
│   │       ├── BookingStatus.php                # Pending/Confirmed/etc
│   │       └── CancellationReason.php           # Cancellation reasons
│   │
│   ├── Workflow/                                 # Workflow Engine Domain
│   │   ├── Models/
│   │   │   ├── Workflow.php                     # Workflow definition
│   │   │   ├── WorkflowStep.php                 # Step in workflow
│   │   │   ├── WorkflowInstance.php             # Running instance
│   │   │   ├── WorkflowStepInstance.php         # Step execution
│   │   │   ├── WorkflowForm.php                 # Form definition
│   │   │   ├── WorkflowFormSubmission.php       # Form submission
│   │   │   ├── WorkflowTransition.php           # State transitions
│   │   │   └── WorkflowVariable.php             # Workflow variables
│   │   ├── Services/
│   │   │   ├── WorkflowService.php              # Workflow CRUD
│   │   │   ├── WorkflowExecutionService.php     # Execute workflow
│   │   │   ├── WorkflowStepService.php          # Step management
│   │   │   ├── WorkflowFormService.php          # Form handling
│   │   │   └── WorkflowTransitionService.php    # Transition logic
│   │   ├── Actions/
│   │   │   ├── CreateWorkflowAction.php         # Create workflow
│   │   │   ├── StartWorkflowAction.php          # Start instance
│   │   │   ├── ExecuteStepAction.php            # Execute step
│   │   │   ├── CompleteStepAction.php           # Complete step
│   │   │   ├── SubmitFormAction.php             # Submit form
│   │   │   └── TransitionWorkflowAction.php     # Transition state
│   │   ├── Repositories/
│   │   │   ├── WorkflowRepository.php           # Workflow repo
│   │   │   └── WorkflowInstanceRepository.php   # Instance repo
│   │   ├── Events/
│   │   │   ├── WorkflowStarted.php              # Workflow started
│   │   │   ├── StepCompleted.php                # Step completed
│   │   │   ├── WorkflowCompleted.php            # Workflow done
│   │   │   └── FormSubmitted.php                # Form submitted
│   │   ├── Policies/
│   │   │   └── WorkflowPolicy.php               # Workflow access
│   │   └── Enums/
│   │       ├── WorkflowStatus.php               # Draft/Active/Archived
│   │       └── StepType.php                     # Manual/Auto/Form/etc
│   │
│   └── Notification/                             # Notification Domain
│       ├── Models/
│       │   ├── Notification.php                 # Notification model
│       │   ├── NotificationTemplate.php         # Email/SMS templates
│       │   ├── NotificationLog.php              # Delivery log
│       │   └── NotificationPreference.php       # User preferences
│       ├── Services/
│       │   ├── NotificationService.php          # Send notifications
│       │   ├── EmailService.php                 # Email sending
│       │   ├── SmsService.php                   # SMS sending
│       │   ├── PushService.php                  # Push notifications
│       │   └── TemplateService.php              # Template rendering
│       ├── Actions/
│       │   ├── SendNotificationAction.php       # Send notification
│       │   ├── SendBulkNotificationAction.php   # Bulk send
│       │   └── CreateTemplateAction.php         # Create template
│       ├── Repositories/
│       │   └── NotificationRepository.php       # Notification repo
│       ├── Events/
│       │   ├── NotificationSent.php             # Sent event
│       │   └── NotificationFailed.php           # Failed event
│       └── Enums/
│           ├── NotificationType.php             # Email/SMS/Push
│           └── NotificationChannel.php          # Delivery channel
│
├── Exceptions/
│   ├── Handler.php                               # Global exception handler
│   ├── TenantNotFoundException.php               # Tenant not found
│   ├── UnauthorizedException.php                 # Not authorized
│   ├── InvalidCredentialsException.php           # Bad credentials
│   ├── RateLimitExceededException.php            # Rate limit hit
│   └── ResourceNotFoundException.php             # Resource not found
│
├── Http/
│   ├── Controllers/
│   │   ├── Api/                                  # API Controllers
│   │   │   ├── V1/                              # Version 1
│   │   │   │   ├── Auth/
│   │   │   │   │   ├── LoginController.php      # POST /login
│   │   │   │   │   ├── RegisterController.php   # POST /register
│   │   │   │   │   ├── OtpController.php        # POST /otp/generate, /verify
│   │   │   │   │   ├── PasswordController.php   # POST /password/forgot, /reset
│   │   │   │   │   ├── LogoutController.php     # POST /logout
│   │   │   │   │   └── MfaController.php        # POST /mfa/enable, /verify
│   │   │   │   ├── Sso/
│   │   │   │   │   ├── SsoController.php        # GET /sso/login/{provider}
│   │   │   │   │   └── SsoCallbackController.php# GET /sso/callback/{provider}
│   │   │   │   ├── User/
│   │   │   │   │   ├── UserController.php       # CRUD /users
│   │   │   │   │   ├── ProfileController.php    # GET/PUT /profile
│   │   │   │   │   └── SessionController.php    # GET /sessions
│   │   │   │   ├── Tenant/
│   │   │   │   │   ├── TenantController.php     # CRUD /tenants
│   │   │   │   │   └── TenantSettingsController.php # PUT /settings
│   │   │   │   ├── Role/
│   │   │   │   │   ├── RoleController.php       # CRUD /roles
│   │   │   │   │   └── PermissionController.php # CRUD /permissions
│   │   │   │   ├── Provider/
│   │   │   │   │   ├── ProviderController.php   # CRUD /providers
│   │   │   │   │   └── AvailabilityController.php# GET/POST /availability
│   │   │   │   ├── Booking/
│   │   │   │   │   └── BookingController.php    # CRUD /bookings
│   │   │   │   ├── Workflow/
│   │   │   │   │   ├── WorkflowController.php   # CRUD /workflows
│   │   │   │   │   └── WorkflowInstanceController.php # Start/execute
│   │   │   │   └── Notification/
│   │   │   │       └── NotificationController.php# Send notifications
│   │   │   └── V2/                              # Future version
│   │   │
│   │   └── Web/                                  # Web Controllers
│   │       ├── Auth/
│   │       │   ├── LoginController.php          # Web login
│   │       │   ├── RegisterController.php       # Web registration
│   │       │   ├── OtpController.php            # Web OTP
│   │       │   └── ForgotPasswordController.php # Web password reset
│   │       ├── Dashboard/
│   │       │   ├── SuperAdminController.php     # Super admin dashboard
│   │       │   ├── TenantAdminController.php    # Tenant admin dashboard
│   │       │   ├── ProviderController.php       # Provider dashboard
│   │       │   ├── OpsController.php            # Ops dashboard
│   │       │   └── CustomerController.php       # Customer dashboard
│   │       ├── Tenant/
│   │       │   └── TenantController.php         # Tenant management UI
│   │       ├── User/
│   │       │   └── UserController.php           # User management UI
│   │       ├── Role/
│   │       │   └── RoleController.php           # Role management UI
│   │       ├── Provider/
│   │       │   └── ProviderController.php       # Provider management UI
│   │       ├── Booking/
│   │       │   └── BookingController.php        # Booking management UI
│   │       └── Workflow/
│   │           └── WorkflowController.php       # Workflow management UI
│   │
│   ├── Middleware/
│   │   ├── Authenticate.php                      # Auth check
│   │   ├── VerifyCsrfToken.php                  # CSRF protection
│   │   ├── TenantIdentification.php             # Identify tenant
│   │   ├── EnforceTenantScope.php               # Enforce isolation
│   │   ├── CheckRole.php                        # Role middleware
│   │   ├── CheckPermission.php                  # Permission middleware
│   │   ├── RateLimiter.php                      # Rate limiting
│   │   ├── AuditLogger.php                      # Audit logging
│   │   ├── ApiVersioning.php                    # API version check
│   │   └── EnsureTenantActive.php               # Check tenant status
│   │
│   ├── Requests/                                 # Form Requests
│   │   ├── Api/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginRequest.php             # Login validation
│   │   │   │   ├── RegisterRequest.php          # Register validation
│   │   │   │   └── OtpRequest.php               # OTP validation
│   │   │   ├── Tenant/
│   │   │   │   ├── CreateTenantRequest.php      # Create validation
│   │   │   │   └── UpdateTenantRequest.php      # Update validation
│   │   │   ├── User/
│   │   │   │   ├── CreateUserRequest.php        # User create
│   │   │   │   └── UpdateUserRequest.php        # User update
│   │   │   ├── Provider/
│   │   │   │   └── CreateProviderRequest.php    # Provider create
│   │   │   ├── Booking/
│   │   │   │   └── CreateBookingRequest.php     # Booking create
│   │   │   └── Workflow/
│   │   │       └── CreateWorkflowRequest.php    # Workflow create
│   │   └── Web/
│   │       └── [Similar structure for web]
│   │
│   ├── Resources/                                # API Resources
│   │   ├── UserResource.php                     # User JSON resource
│   │   ├── TenantResource.php                   # Tenant JSON resource
│   │   ├── RoleResource.php                     # Role JSON resource
│   │   ├── ProviderResource.php                 # Provider JSON resource
│   │   ├── BookingResource.php                  # Booking JSON resource
│   │   ├── WorkflowResource.php                 # Workflow JSON resource
│   │   └── NotificationResource.php             # Notification resource
│   │
│   └── Kernel.php                                # HTTP kernel
│
├── Jobs/                                          # Queue Jobs
│   ├── Auth/
│   │   ├── SendOtpJob.php                       # Send OTP email/SMS
│   │   ├── SendVerificationEmailJob.php         # Send verification
│   │   └── SendPasswordResetJob.php             # Send reset link
│   ├── Tenant/
│   │   ├── ProvisionTenantJob.php               # Provision tenant
│   │   └── CleanupTenantDataJob.php             # Cleanup job
│   ├── Notification/
│   │   ├── SendEmailJob.php                     # Send email
│   │   ├── SendSmsJob.php                       # Send SMS
│   │   └── SendBulkNotificationJob.php          # Bulk send
│   ├── Booking/
│   │   └── SendBookingReminderJob.php           # Booking reminders
│   ├── Workflow/
│   │   ├── ExecuteWorkflowStepJob.php           # Execute workflow step
│   │   └── ProcessWorkflowTransitionJob.php     # Process transition
│   └── System/
│       ├── CleanupExpiredSessionsJob.php        # Clean sessions
│       ├── CleanupExpiredOtpsJob.php            # Clean OTP codes
│       └── GenerateReportsJob.php               # Generate reports
│
├── Mail/                                          # Mailable Classes
│   ├── Auth/
│   │   ├── OtpMail.php                          # OTP email
│   │   ├── VerificationMail.php                 # Email verification
│   │   ├── PasswordResetMail.php                # Password reset
│   │   └── WelcomeMail.php                      # Welcome email
│   ├── Tenant/
│   │   └── TenantWelcomeMail.php                # Tenant welcome
│   ├── Booking/
│   │   ├── BookingConfirmationMail.php          # Booking confirmation
│   │   ├── BookingReminderMail.php              # Booking reminder
│   │   └── BookingCancellationMail.php          # Cancellation notice
│   └── Notification/
│       └── GenericNotificationMail.php          # Template-based email
│
├── Models/                                        # Legacy models (if needed)
│   └── [Empty - using Domain models]
│
├── Notifications/                                 # Laravel Notifications
│   ├── Auth/
│   │   ├── OtpNotification.php                  # OTP notification
│   │   └── PasswordResetNotification.php        # Reset notification
│   ├── Booking/
│   │   └── BookingReminderNotification.php      # Booking reminder
│   └── Workflow/
│       └── WorkflowStepNotification.php         # Workflow notification
│
├── Observers/                                     # Model Observers
│   ├── UserObserver.php                         # User lifecycle
│   ├── TenantObserver.php                       # Tenant lifecycle
│   ├── BookingObserver.php                      # Booking lifecycle
│   └── WorkflowInstanceObserver.php             # Workflow lifecycle
│
├── Policies/                                      # Authorization Policies
│   ├── TenantPolicy.php                         # Tenant access
│   ├── UserPolicy.php                           # User access
│   ├── RolePolicy.php                           # Role access
│   ├── ProviderPolicy.php                       # Provider access
│   ├── BookingPolicy.php                        # Booking access
│   └── WorkflowPolicy.php                       # Workflow access
│
├── Providers/                                     # Service Providers
│   ├── AppServiceProvider.php                   # App bindings
│   ├── AuthServiceProvider.php                  # Auth setup
│   ├── EventServiceProvider.php                 # Event listeners
│   ├── RouteServiceProvider.php                 # Route setup
│   ├── TenantServiceProvider.php                # Tenant bindings
│   ├── DomainServiceProvider.php                # Domain bindings
│   └── RepositoryServiceProvider.php            # Repository bindings
│
└── Services/                                      # Global Services
    ├── Cache/
    │   ├── CacheService.php                     # Cache wrapper
    │   └── TenantCacheService.php               # Tenant-specific cache
    ├── Audit/
    │   └── AuditService.php                     # Audit logging
    ├── RateLimiting/
    │   └── RateLimiter.php                      # Rate limit service
    └── Encryption/
        └── EncryptionService.php                # Encryption helper

```

---

## 📁 `/bootstrap` - Bootstrap Files

```
bootstrap/
├── app.php                                       # Application bootstrap
├── cache/                                        # Bootstrap cache
│   ├── packages.php                             # Package cache
│   └── services.php                             # Service cache
└── providers.php                                 # Provider cache

```

---

## 📁 `/config` - Configuration Files

```
config/
├── app.php                                       # App config
├── auth.php                                      # Auth config
├── cache.php                                     # Cache drivers
├── database.php                                  # Database connections
├── filesystems.php                               # File storage
├── logging.php                                   # Logging config
├── mail.php                                      # Email config
├── queue.php                                     # Queue config
├── sanctum.php                                   # API tokens
├── services.php                                  # Third-party services
├── session.php                                   # Session config
├── tenant.php                                    # Tenant configuration
│   # Contains:
│   # - tenant_identification_strategy
│   # - default_tenant_limits
│   # - tenant_isolation_rules
│   # - domain_mapping_settings
├── sso.php                                       # SSO configuration
│   # Contains:
│   # - supported_providers
│   # - oauth_settings
│   # - saml_settings
│   # - group_mapping_rules
├── rbac.php                                      # RBAC configuration
│   # Contains:
│   # - permission_cache_ttl
│   # - default_roles
│   # - system_permissions
│   # - policy_engine_rules
├── otp.php                                       # OTP configuration
│   # Contains:
│   # - otp_length
│   # - otp_expiry
│   # - otp_channel (email/sms)
│   # - rate_limits
└── modules.php                                   # Module configuration
    # Contains:
    # - enabled_modules (Provider, Booking, Workflow)
    # - module_permissions
    # - module_settings

```

---

## 📁 `/database` - Database Files

```
database/
├── factories/                                    # Model Factories
│   ├── UserFactory.php                          # User factory
│   ├── TenantFactory.php                        # Tenant factory
│   ├── RoleFactory.php                          # Role factory
│   ├── ProviderFactory.php                      # Provider factory
│   ├── BookingFactory.php                       # Booking factory
│   └── WorkflowFactory.php                      # Workflow factory
│
├── migrations/                                   # Database Migrations
│   ├── 2024_01_01_000001_create_tenants_table.php
│   │   # Columns: id, uuid, name, slug, domain, status, settings, 
│   │   #          subscription_tier, expires_at, created_at, updated_at
│   ├── 2024_01_01_000002_create_tenant_domains_table.php
│   │   # Columns: id, tenant_id, domain, is_primary, verified_at, created_at
│   ├── 2024_01_01_000003_create_tenant_settings_table.php
│   │   # Columns: id, tenant_id, key, value, type, created_at, updated_at
│   ├── 2024_01_01_000004_create_users_table.php
│   │   # Columns: id, tenant_id, uuid, email, phone, password, 
│   │   #          email_verified_at, phone_verified_at, status,
│   │   #          last_login_at, created_at, updated_at
│   ├── 2024_01_01_000005_create_user_profiles_table.php
│   │   # Columns: id, user_id, first_name, last_name, avatar, 
│   │   #          date_of_birth, timezone, locale, metadata
│   ├── 2024_01_01_000006_create_password_resets_table.php
│   │   # Columns: email, token, created_at
│   ├── 2024_01_01_000007_create_otp_codes_table.php
│   │   # Columns: id, tenant_id, user_id, code, type, 
│   │   #          expires_at, verified_at, created_at
│   ├── 2024_01_01_000008_create_email_verifications_table.php
│   │   # Columns: id, user_id, token, expires_at, verified_at
│   ├── 2024_01_01_000009_create_user_sessions_table.php
│   │   # Columns: id, user_id, session_id, ip_address, 
│   │   #          user_agent, last_activity, created_at
│   ├── 2024_01_01_000010_create_user_devices_table.php
│   │   # Columns: id, user_id, device_id, device_type, 
│   │   #          fingerprint, last_used_at, created_at
│   ├── 2024_01_01_000011_create_login_attempts_table.php
│   │   # Columns: id, tenant_id, email, ip_address, 
│   │   #          success, reason, created_at
│   ├── 2024_01_01_000012_create_personal_access_tokens_table.php
│   │   # Columns: id, tokenable_type, tokenable_id, name, 
│   │   #          token, abilities, last_used_at, expires_at
│   ├── 2024_01_02_000001_create_sso_providers_table.php
│   │   # Columns: id, tenant_id, name, type, config, 
│   │   #          is_enabled, created_at, updated_at
│   ├── 2024_01_02_000002_create_sso_connections_table.php
│   │   # Columns: id, user_id, provider_id, external_id, 
│   │   #          external_email, metadata, created_at, updated_at
│   ├── 2024_01_02_000003_create_sso_sessions_table.php
│   │   # Columns: id, connection_id, session_token, 
│   │   #          expires_at, created_at
│   ├── 2024_01_02_000004_create_sso_group_mappings_table.php
│   │   # Columns: id, provider_id, external_group, role_id, created_at
│   ├── 2024_01_03_000001_create_roles_table.php
│   │   # Columns: id, tenant_id, name, slug, description, 
│   │   #          is_system, created_at, updated_at
│   ├── 2024_01_03_000002_create_permissions_table.php
│   │   # Columns: id, tenant_id, name, slug, resource, 
│   │   #          action, description, created_at, updated_at
│   ├── 2024_01_03_000003_create_policies_table.php
│   │   # Columns: id, tenant_id, name, rules, priority, 
│   │   #          is_active, created_at, updated_at
│   ├── 2024_01_03_000004_create_role_user_table.php
│   │   # Columns: role_id, user_id, assigned_at
│   ├── 2024_01_03_000005_create_permission_role_table.php
│   │   # Columns: permission_id, role_id, assigned_at
│   ├── 2024_01_03_000006_create_resource_permissions_table.php
│   │   # Columns: id, tenant_id, user_id, resource_type, 
│   │   #          resource_id, permission, created_at
│   ├── 2024_01_04_000001_create_providers_table.php
│   │   # Columns: id, tenant_id, user_id, uuid, type, 
│   │   #          status, rating, created_at, updated_at
│   ├── 2024_01_04_000002_create_provider_profiles_table.php
│   │   # Columns: id, provider_id, bio, specialties, 
│   │   #          years_experience, metadata
│   ├── 2024_01_04_000003_create_provider_availabilities_table.php
│   │   # Columns: id, provider_id, day_of_week, start_time, 
│   │   #          end_time, is_available, created_at, updated_at
│   ├── 2024_01_04_000004_create_provider_specialties_table.php
│   │   # Columns: id, provider_id, specialty, certification, 
│   │   #          verified_at, created_at
│   ├── 2024_01_04_000005_create_provider_documents_table.php
│   │   # Columns: id, provider_id, type, path, verified_at, 
│   │   #          expires_at, created_at
│   ├── 2024_01_04_000006_create_provider_ratings_table.php
│   │   # Columns: id, provider_id, user_id, rating, review, 
│   │   #          created_at, updated_at
│   ├── 2024_01_05_000001_create_bookings_table.php
│   │   # Columns: id, tenant_id, user_id, provider_id, uuid, 
│   │   #          status, scheduled_at, duration, notes,
│   │   #          created_at, updated_at
│   ├── 2024_01_05_000002_create_booking_slots_table.php
│   │   # Columns: id, provider_id, start_time, end_time, 
│   │   #          is_available, created_at
│   ├── 2024_01_05_000003_create_booking_histories_table.php
│   │   # Columns: id, booking_id, status, changed_by, 
│   │   #          reason, created_at
│   ├── 2024_01_05_000004_create_booking_cancellations_table.php
│   │   # Columns: id, booking_id, cancelled_by, reason, 
│   │   #          refund_amount, created_at
│   ├── 2024_01_05_000005_create_booking_payments_table.php
│   │   # Columns: id, booking_id, amount, currency, status, 
│   │   #          payment_method, transaction_id, created_at
│   ├── 2024_01_06_000001_create_workflows_table.php
│   │   # Columns: id, tenant_id, name, description, status, 
│   │   #          config, created_at, updated_at
│   ├── 2024_01_06_000002_create_workflow_steps_table.php
│   │   # Columns: id, workflow_id, name, type, order, 
│   │   #          config, created_at, updated_at
│   ├── 2024_01_06_000003_create_workflow_instances_table.php
│   │   # Columns: id, workflow_id, tenant_id, user_id, 
│   │   #          status, started_at, completed_at, metadata
│   ├── 2024_01_06_000004_create_workflow_step_instances_table.php
│   │   # Columns: id, instance_id, step_id, status, 
│   │   #          started_at, completed_at, data
│   ├── 2024_01_06_000005_create_workflow_forms_table.php
│   │   # Columns: id, workflow_id, step_id, name, schema, 
│   │   #          validation_rules, created_at, updated_at
│   ├── 2024_01_06_000006_create_workflow_form_submissions_table.php
│   │   # Columns: id, form_id, instance_id, user_id, 
│   │   #          data, submitted_at
│   ├── 2024_01_06_000007_create_workflow_transitions_table.php
│   │   # Columns: id, workflow_id, from_step_id, to_step_id, 
│   │   #          condition, created_at
│   ├── 2024_01_06_000008_create_workflow_variables_table.php
│   │   # Columns: id, instance_id, key, value, type, created_at
│   ├── 2024_01_07_000001_create_notifications_table.php
│   │   # Columns: id, tenant_id, user_id, type, channel, 
│   │   #          subject, body, read_at, created_at
│   ├── 2024_01_07_000002_create_notification_templates_table.php
│   │   # Columns: id, tenant_id, name, type, subject, 
│   │   #          body, variables, created_at, updated_at
│   ├── 2024_01_07_000003_create_notification_logs_table.php
│   │   # Columns: id, notification_id, channel, status, 
│   │   #          sent_at, error_message, created_at
│   ├── 2024_01_07_000004_create_notification_preferences_table.php
│   │   # Columns: id, user_id, channel, type, enabled, created_at
│   └── 2024_01_08_000001_create_audit_logs_table.php
│       # Columns: id, tenant_id, user_id, action, resource_type,
│       #          resource_id, old_values, new_values, 
│       #          ip_address, user_agent, created_at
│
└── seeders/                                      # Database Seeders
    ├── DatabaseSeeder.php                       # Main seeder
    ├── TenantSeeder.php                         # Seed tenants
    ├── RoleSeeder.php                           # Seed default roles
    ├── PermissionSeeder.php                     # Seed permissions
    ├── UserSeeder.php                           # Seed users
    ├── ProviderSeeder.php                       # Seed providers
    └── WorkflowSeeder.php                       # Seed workflows

```

---

## 📁 `/public` - Public Assets

```
public/
├── index.php                                     # Entry point
├── .htaccess                                     # Apache config
├── robots.txt                                    # Robots file
├── favicon.ico                                   # Favicon
├── css/                                          # Compiled CSS
│   └── app.css                                  # Main CSS
├── js/                                           # Compiled JS
│   └── app.js                                   # Main JS
└── images/                                       # Public images
    └── logo.png                                 # Logo

```

---

## 📁 `/resources` - Frontend Resources

```
resources/
├── css/                                          # Source CSS
│   └── app.css                                  # Tailwind/styles
│
├── js/                                           # Source JavaScript
│   ├── app.js                                   # Main JS entry
│   ├── bootstrap.js                             # Bootstrap JS
│   └── components/                              # Vue/React components (if needed)
│
├── views/                                        # Blade Templates
│   ├── layouts/
│   │   ├── app.blade.php                       # Main layout
│   │   ├── auth.blade.php                      # Auth layout
│   │   ├── dashboard.blade.php                 # Dashboard layout
│   │   └── partials/
│   │       ├── header.blade.php                # Header partial
│   │       ├── footer.blade.php                # Footer partial
│   │       ├── navigation.blade.php            # Navigation
│   │       └── sidebar.blade.php               # Sidebar
│   │
│   ├── auth/                                    # Auth Views
│   │   ├── login.blade.php                     # Login page
│   │   ├── register.blade.php                  # Register page
│   │   ├── otp.blade.php                       # OTP page
│   │   ├── forgot-password.blade.php           # Forgot password
│   │   ├── reset-password.blade.php            # Reset password
│   │   ├── verify-email.blade.php              # Email verification
│   │   └── mfa.blade.php                       # MFA page
│   │
│   ├── sso/
│   │   ├── select-provider.blade.php           # SSO provider selection
│   │   └── callback.blade.php                  # SSO callback page
│   │
│   ├── dashboards/                              # Dashboard Views
│   │   ├── super-admin/
│   │   │   ├── index.blade.php                 # Super admin home
│   │   │   ├── tenants.blade.php               # Tenant management
│   │   │   ├── analytics.blade.php             # System analytics
│   │   │   └── settings.blade.php              # System settings
│   │   ├── tenant-admin/
│   │   │   ├── index.blade.php                 # Tenant admin home
│   │   │   ├── users.blade.php                 # User management
│   │   │   ├── roles.blade.php                 # Role management
│   │   │   ├── settings.blade.php              # Tenant settings
│   │   │   └── sso.blade.php                   # SSO configuration
│   │   ├── provider/
│   │   │   ├── index.blade.php                 # Provider dashboard
│   │   │   ├── bookings.blade.php              # Provider bookings
│   │   │   ├── availability.blade.php          # Availability management
│   │   │   └── profile.blade.php               # Provider profile
│   │   ├── ops/
│   │   │   ├── index.blade.php                 # Ops dashboard
│   │   │   ├── workflows.blade.php             # Workflow management
│   │   │   ├── bookings.blade.php              # Booking management
│   │   │   └── providers.blade.php             # Provider management
│   │   └── customer/
│   │       ├── index.blade.php                 # Customer dashboard
│   │       ├── bookings.blade.php              # My bookings
│   │       └── profile.blade.php               # My profile
│   │
│   ├── tenants/                                 # Tenant Management
│   │   ├── index.blade.php                     # List tenants
│   │   ├── create.blade.php                    # Create tenant
│   │   ├── edit.blade.php                      # Edit tenant
│   │   └── show.blade.php                      # Tenant details
│   │
│   ├── users/                                   # User Management
│   │   ├── index.blade.php                     # List users
│   │   ├── create.blade.php                    # Create user
│   │   ├── edit.blade.php                      # Edit user
│   │   └── show.blade.php                      # User details
│   │
│   ├── roles/                                   # Role Management
│   │   ├── index.blade.php                     # List roles
│   │   ├── create.blade.php                    # Create role
│   │   ├── edit.blade.php                      # Edit role
│   │   └── permissions.blade.php               # Assign permissions
│   │
│   ├── providers/                               # Provider Management
│   │   ├── index.blade.php                     # List providers
│   │   ├── create.blade.php                    # Create provider
│   │   ├── edit.blade.php                      # Edit provider
│   │   └── show.blade.php                      # Provider details
│   │
│   ├── bookings/                                # Booking Management
│   │   ├── index.blade.php                     # List bookings
│   │   ├── create.blade.php                    # Create booking
│   │   ├── edit.blade.php                      # Edit booking
│   │   └── show.blade.php                      # Booking details
│   │
│   ├── workflows/                               # Workflow Management
│   │   ├── index.blade.php                     # List workflows
│   │   ├── create.blade.php                    # Create workflow
│   │   ├── edit.blade.php                      # Edit workflow
│   │   ├── builder.blade.php                   # Workflow builder
│   │   └── instances.blade.php                 # Workflow instances
│   │
│   ├── notifications/                           # Notifications
│   │   ├── index.blade.php                     # List notifications
│   │   └── templates.blade.php                 # Manage templates
│   │
│   ├── components/                              # Blade Components
│   │   ├── alert.blade.php                     # Alert component
│   │   ├── button.blade.php                    # Button component
│   │   ├── card.blade.php                      # Card component
│   │   ├── modal.blade.php                     # Modal component
│   │   ├── table.blade.php                     # Table component
│   │   └── form/
│   │       ├── input.blade.php                 # Input field
│   │       ├── select.blade.php                # Select dropdown
│   │       ├── textarea.blade.php              # Textarea
│   │       └── checkbox.blade.php              # Checkbox
│   │
│   └── errors/                                  # Error Pages
│       ├── 401.blade.php                       # Unauthorized
│       ├── 403.blade.php                       # Forbidden
│       ├── 404.blade.php                       # Not found
│       ├── 419.blade.php                       # CSRF token expired
│       ├── 429.blade.php                       # Too many requests
│       ├── 500.blade.php                       # Server error
│       └── 503.blade.php                       # Maintenance mode
│
└── lang/                                         # Translations
    ├── en/
    │   ├── auth.php                            # Auth translations
    │   ├── pagination.php                      # Pagination
    │   ├── passwords.php                       # Password messages
    │   ├── validation.php                      # Validation messages
    │   ├── messages.php                        # General messages
    │   └── modules/
    │       ├── tenant.php                      # Tenant messages
    │       ├── booking.php                     # Booking messages
    │       └── workflow.php                    # Workflow messages
    └── es/                                      # Spanish (example)
        └── [same structure]

```

---

## 📁 `/routes` - Route Files

```
routes/
├── web.php                                       # Web routes
│   # Contains:
│   # - Auth routes (login, register, logout)
│   # - SSO routes
│   # - Dashboard routes (super-admin, tenant-admin, provider, ops, customer)
│   # - Tenant management routes
│   # - User management routes
│   # - Role management routes
│   # - Provider management routes
│   # - Booking management routes
│   # - Workflow management routes
│
├── api.php                                       # API routes
│   # Contains:
│   # - API v1 routes
│   #   - Auth endpoints (/login, /register, /otp, /logout)
│   #   - SSO endpoints (/sso/login/{provider}, /sso/callback)
│   #   - User endpoints (/users, /profile)
│   #   - Tenant endpoints (/tenants)
│   #   - Role endpoints (/roles, /permissions)
│   #   - Provider endpoints (/providers)
│   #   - Booking endpoints (/bookings)
│   #   - Workflow endpoints (/workflows, /workflow-instances)
│   #   - Notification endpoints (/notifications)
│
├── console.php                                   # Console routes
│   # Contains:
│   # - Artisan closure commands
│   # - Scheduled commands
│
└── channels.php                                  # Broadcast channels
    # Contains:
    # - Private channels
    # - Presence channels
    # - Notification channels

```

---

## 📁 `/storage` - Storage Directory

```
storage/
├── app/                                          # Application files
│   ├── public/                                  # Public files
│   │   ├── avatars/                            # User avatars
│   │   ├── documents/                          # Provider documents
│   │   └── uploads/                            # General uploads
│   ├── private/                                 # Private files
│   │   ├── exports/                            # Data exports
│   │   └── reports/                            # Generated reports
│   └── temp/                                    # Temporary files
│
├── framework/                                    # Framework files
│   ├── cache/                                   # Framework cache
│   │   └── data/                               # Cache data
│   ├── sessions/                                # Session files
│   ├── testing/                                 # Testing files
│   └── views/                                   # Compiled views
│
└── logs/                                         # Log files
    ├── laravel.log                              # Application log
    ├── audit.log                                # Audit log
    ├── security.log                             # Security log
    └── query.log                                # Query log

```

---

## 📁 `/tests` - Test Files

```
tests/
├── Feature/                                      # Feature Tests
│   ├── Auth/
│   │   ├── LoginTest.php                       # Test login
│   │   ├── RegisterTest.php                    # Test registration
│   │   ├── OtpTest.php                         # Test OTP
│   │   ├── PasswordResetTest.php               # Test password reset
│   │   └── MfaTest.php                         # Test MFA
│   ├── Sso/
│   │   ├── SsoLoginTest.php                    # Test SSO login
│   │   └── SsoCallbackTest.php                 # Test SSO callback
│   ├── Tenant/
│   │   ├── TenantCreationTest.php              # Test tenant creation
│   │   ├── TenantIsolationTest.php             # Test isolation
│   │   └── TenantSettingsTest.php              # Test settings
│   ├── User/
│   │   ├── UserManagementTest.php              # Test user CRUD
│   │   └── UserPermissionsTest.php             # Test permissions
│   ├── Role/
│   │   ├── RoleManagementTest.php              # Test role CRUD
│   │   └── PermissionAssignmentTest.php        # Test assignments
│   ├── Provider/
│   │   ├── ProviderManagementTest.php          # Test provider CRUD
│   │   └── ProviderAvailabilityTest.php        # Test availability
│   ├── Booking/
│   │   ├── BookingCreationTest.php             # Test booking create
│   │   ├── BookingCancellationTest.php         # Test cancellation
│   │   └── BookingReminderTest.php             # Test reminders
│   └── Workflow/
│       ├── WorkflowExecutionTest.php           # Test execution
│       └── WorkflowFormTest.php                # Test forms
│
├── Unit/                                         # Unit Tests
│   ├── Services/
│   │   ├── AuthenticationServiceTest.php       # Test auth service
│   │   ├── TenantServiceTest.php               # Test tenant service
│   │   ├── RoleServiceTest.php                 # Test role service
│   │   ├── ProviderServiceTest.php             # Test provider service
│   │   ├── BookingServiceTest.php              # Test booking service
│   │   └── WorkflowServiceTest.php             # Test workflow service
│   ├── Actions/
│   │   ├── CreateTenantActionTest.php          # Test create tenant
│   │   ├── LoginUserActionTest.php             # Test login action
│   │   ├── AssignRoleActionTest.php            # Test role assignment
│   │   └── CreateBookingActionTest.php         # Test booking action
│   ├── Models/
│   │   ├── TenantTest.php                      # Test tenant model
│   │   ├── UserTest.php                        # Test user model
│   │   ├── RoleTest.php                        # Test role model
│   │   └── BookingTest.php                     # Test booking model
│   ├── ValueObjects/
│   │   ├── EmailTest.php                       # Test email VO
│   │   ├── PhoneNumberTest.php                 # Test phone VO
│   │   └── UUIDTest.php                        # Test UUID VO
│   └── Policies/
│       ├── TenantPolicyTest.php                # Test tenant policy
│       ├── UserPolicyTest.php                  # Test user policy
│       └── BookingPolicyTest.php               # Test booking policy
│
├── Integration/                                  # Integration Tests
│   ├── Api/
│   │   ├── AuthApiTest.php                     # Test auth API
│   │   ├── TenantApiTest.php                   # Test tenant API
│   │   ├── BookingApiTest.php                  # Test booking API
│   │   └── WorkflowApiTest.php                 # Test workflow API
│   ├── Sso/
│   │   ├── OAuthIntegrationTest.php            # Test OAuth flow
│   │   └── SamlIntegrationTest.php             # Test SAML flow
│   └── Queue/
│       ├── OtpJobTest.php                      # Test OTP job
│       └── NotificationJobTest.php             # Test notification job
│
├── Browser/                                      # Browser Tests (Dusk)
│   ├── Auth/
│   │   ├── LoginBrowserTest.php                # Test login UI
│   │   └── RegisterBrowserTest.php             # Test register UI
│   ├── Dashboard/
│   │   ├── SuperAdminDashboardTest.php         # Test super admin UI
│   │   └── TenantAdminDashboardTest.php        # Test tenant admin UI
│   └── Booking/
│       └── BookingFlowTest.php                 # Test booking flow
│
├── TestCase.php                                  # Base test case
├── CreatesApplication.php                        # Application creator trait
└── Traits/
    ├── WithTenant.php                           # Tenant testing trait
    ├── WithAuthentication.php                   # Auth testing trait
    └── WithPermissions.php                      # Permission testing trait

```

---

## 📁 Root Configuration Files

```
project-root/
├── .env                                          # Environment variables
│   # Contains:
│   # - APP_NAME, APP_ENV, APP_KEY, APP_DEBUG, APP_URL
│   # - Database credentials
│   # - Cache/Queue/Session drivers
│   # - Mail configuration
│   # - Redis configuration
│   # - Tenant configuration
│   # - SSO configuration
│   # - OTP configuration
│
├── .env.example                                  # Example env file
├── .env.testing                                  # Testing environment
├── .gitignore                                    # Git ignore rules
├── .gitattributes                                # Git attributes
├── .editorconfig                                 # Editor config
├── .phpunit.xml                                  # PHPUnit config
├── phpunit.xml                                   # PHPUnit settings
├── composer.json                                 # PHP dependencies
│   # Contains:
│   # - laravel/framework: ^12.0
│   # - laravel/sanctum: ^4.0
│   # - laravel/tinker: ^2.9
│   # - spatie/laravel-permission (if using)
│   # - predis/predis
│   # - league/flysystem-aws-s3-v3
│   # - symfony/http-client
│   # - symfony/mailgun-mailer
│   # Development dependencies:
│   # - laravel/pint
│   # - laravel/sail
│   # - mockery/mockery
│   # - phpunit/phpunit
│   # - fakerphp/faker
│
├── composer.lock                                 # Locked dependencies
├── package.json                                  # Node dependencies
│   # Contains:
│   # - vite
│   # - laravel-vite-plugin
│   # - tailwindcss
│   # - postcss
│   # - autoprefixer
│   # - axios
│
├── package-lock.json                             # Locked node deps
├── vite.config.js                                # Vite configuration
│   # Contains:
│   # - Build settings
│   # - Input files
│   # - Output settings
│
├── tailwind.config.js                            # Tailwind configuration
│   # Contains:
│   # - Content paths
│   # - Theme customization
│   # - Plugins
│
├── postcss.config.js                             # PostCSS configuration
├── artisan                                       # Artisan CLI
├── server.php                                    # PHP built-in server
├── phpcs.xml                                     # Code sniffer config
├── pint.json                                     # Laravel Pint config
├── rector.php                                    # Rector config (optional)
└── README.md                                     # Project documentation

```

---

## 📁 Additional Directories (Optional but Recommended)

```
project-root/
├── docs/                                         # Documentation
│   ├── architecture/
│   │   ├── overview.md                         # Architecture overview
│   │   ├── multi-tenancy.md                    # Tenant architecture
│   │   ├── authentication.md                   # Auth architecture
│   │   ├── authorization.md                    # RBAC architecture
│   │   └── sso-integration.md                  # SSO architecture
│   ├── api/
│   │   ├── authentication.md                   # API auth docs
│   │   ├── endpoints.md                        # API endpoints
│   │   ├── rate-limiting.md                    # Rate limits
│   │   └── versioning.md                       # API versioning
│   ├── modules/
│   │   ├── tenant-management.md                # Tenant module docs
│   │   ├── provider-management.md              # Provider module docs
│   │   ├── booking-system.md                   # Booking module docs
│   │   └── workflow-engine.md                  # Workflow module docs
│   ├── deployment/
│   │   ├── production.md                       # Production deployment
│   │   ├── staging.md                          # Staging deployment
│   │   └── docker.md                           # Docker setup
│   └── guides/
│       ├── getting-started.md                  # Getting started
│       ├── tenant-onboarding.md                # Tenant setup
│       ├── sso-setup.md                        # SSO configuration
│       └── workflow-creation.md                # Workflow guide
│
├── scripts/                                      # Utility scripts
│   ├── setup.sh                                # Initial setup script
│   ├── deploy.sh                               # Deployment script
│   ├── backup.sh                               # Backup script
│   ├── restore.sh                              # Restore script
│   └── seed-demo-data.sh                       # Seed demo data
│
└── docker/                                       # Docker files
    ├── Dockerfile                              # Main Dockerfile
    ├── docker-compose.yml                      # Docker compose
    ├── nginx/
    │   └── default.conf                        # Nginx config
    ├── php/
    │   ├── php.ini                            # PHP config
    │   └── www.conf                           # PHP-FPM config
    └── mysql/
        └── my.cnf                             # MySQL config

```

---

## 🎯 KEY ARCHITECTURAL PATTERNS IN FILES

### **1. Domain Models** (e.g., `app/Domain/Identity/Models/User.php`)
```php
// Contains:
// - Eloquent model definition
// - Relationships (belongsTo, hasMany)
// - Scopes (tenant scoping)
// - Accessors/Mutators
// - Casting
// - Events (creating, created, updating, etc.)
```

### **2. Services** (e.g., `app/Domain/Identity/Services/AuthenticationService.php`)
```php
// Contains:
// - Business logic orchestration
// - Calls to Actions
// - Transaction management
// - Event dispatching
// - Exception handling
// - Public methods for controllers
```

### **3. Actions** (e.g., `app/Domain/Identity/Actions/LoginUserAction.php`)
```php
// Contains:
// - Single responsibility actions
// - Input validation
// - Database operations
// - Event firing
// - Returns success/failure
// - Atomic operations
```

### **4. Repositories** (e.g., `app/Domain/Tenant/Repositories/TenantRepository.php`)
```php
// Contains:
// - Data access layer
// - Query builders
// - Caching logic
// - Tenant scoping
// - Complex queries
// - Return collections/models
```

### **5. API Controllers** (e.g., `app/Http/Controllers/Api/V1/Auth/LoginController.php`)
```php
// Contains:
// - Request validation (via FormRequest)
// - Service method calls
// - Resource transformation
// - HTTP response building
// - Status codes
// - Error handling
```

### **6. Web Controllers** (e.g., `app/Http/Controllers/Web/Dashboard/SuperAdminController.php`)
```php
// Contains:
// - View rendering
// - Session management
// - Flash messages
// - Redirects
// - View data preparation
// - Middleware usage
```

### **7. Middleware** (e.g., `app/Http/Middleware/TenantIdentification.php`)
```php
// Contains:
// - Request inspection
// - Tenant resolution (domain/subdomain/header)
// - Context setting
// - Early returns (403, 404)
// - Request modification
```

### **8. Policies** (e.g., `app/Policies/BookingPolicy.php`)
```php
// Contains:
// - Authorization methods (view, create, update, delete)
// - Access checks based on roles/permissions
// - Resource ownership checks
// - Tenant isolation enforcement
// - Returns boolean or Response
```

### **9. Events** (e.g., `app/Domain/Identity/Events/UserRegistered.php`)
```php
// Contains:
// - Event data (user, metadata)
// - ShouldBroadcast interface (if real-time)
// - Serializable properties
// - Constructor
```

### **10. Listeners** (e.g., `app/Domain/Identity/Listeners/SendVerificationEmail.php`)
```php
// Contains:
// - Handle method
// - Job dispatching
// - External service calls
// - Logging
// - Error handling
```

### **11. Jobs** (e.g., `app/Jobs/Auth/SendOtpJob.php`)
```php
// Contains:
// - Queueable trait
// - Handle method
// - Retry logic
// - Failure handling
// - Rate limiting
// - Batch processing
```

### **12. Form Requests** (e.g., `app/Http/Requests/Api/Auth/LoginRequest.php`)
```php
// Contains:
// - Authorization check
// - Validation rules
// - Custom validation logic
// - Error messages
// - Input preparation
```

### **13. Resources** (e.g., `app/Http/Resources/UserResource.php`)
```php
// Contains:
// - toArray method
// - Conditional attributes
// - Relationship loading
// - Data transformation
// - Metadata inclusion
```

### **14. Migrations** (e.g., `database/migrations/2024_01_01_000001_create_tenants_table.php`)
```php
// Contains:
// - up() method: table creation/modification
// - down() method: rollback
// - Indexes
// - Foreign keys
// - Column definitions
```

### **15. Seeders** (e.g., `database/seeders/RoleSeeder.php`)
```php
// Contains:
// - run() method
// - Model creation
// - Relationship seeding
// - Factory usage
// - Default data setup
```

### **16. Config Files** (e.g., `config/tenant.php`)
```php
// Contains:
// - Configuration arrays
// - Environment variable access
// - Default values
// - Feature flags
// - Driver settings
```

### **17. Routes** (e.g., `routes/api.php`)
```php
// Contains:
// - Route definitions
// - Middleware groups
// - Route grouping
// - Versioning
// - Rate limiting
// - Named routes
```

### **18. Blade Views** (e.g., `resources/views/dashboards/super-admin/index.blade.php`)
```html
<!-- Contains: -->
<!-- - Layout extension (@extends) -->
<!-- - Sections (@section) -->
<!-- - Components (@component) -->
<!-- - Conditionals (@if, @auth) -->
<!-- - Loops (@foreach) -->
<!-- - Form elements -->
<!-- - CSRF tokens (@csrf) -->
```

---

## 📊 FILE COUNT ESTIMATE

```
Domains:           ~150-200 files
Controllers:       ~50-70 files
Middleware:        ~10-15 files
Requests:          ~40-50 files
Resources:         ~15-20 files
Jobs:              ~20-30 files
Events:            ~30-40 files
Listeners:         ~30-40 files
Policies:          ~10-15 files
Migrations:        ~50-60 files
Seeders:           ~10-15 files
Tests:             ~100-150 files
Views:             ~80-120 files
Config:            ~15-20 files
Routes:            ~4 files

TOTAL:             ~600-850 files
```

---

## 🔑 CRITICAL FILES SUMMARY

### **Must Have (Day 1):**
1. `config/tenant.php` - Tenant configuration
2. `app/Domain/Tenant/Models/Tenant.php` - Tenant model
3. `app/Http/Middleware/TenantIdentification.php` - Tenant resolver
4. `app/Domain/Identity/Models/User.php` - User model
5. `app/Domain/Authorization/Models/Role.php` - Role model
6. `app/Domain/Authorization/Models/Permission.php` - Permission model
7. `database/migrations/*_create_tenants_table.php` - Tenant migration
8. `database/migrations/*_create_users_table.php` - User migration

### **Critical for Auth (Week 1):**
9. `app/Domain/Identity/Services/AuthenticationService.php`
10. `app/Domain/Identity/Actions/LoginUserAction.php`
11. `app/Domain/Identity/Services/OtpService.php`
12. `app/Http/Controllers/Api/V1/Auth/LoginController.php`
13. `routes/api.php` - API routes
14. `routes/web.php` - Web routes

### **Critical for SSO (Week 2):**
15. `config/sso.php` - SSO configuration
16. `app/Domain/SSO/Services/SsoService.php`
17. `app/Domain/SSO/Adapters/SsoAdapterInterface.php`
18. `app/Domain/SSO/Models/SsoProvider.php`

### **Critical for RBAC (Week 2):**
19. `config/rbac.php` - RBAC configuration
20. `app/Domain/Authorization/Services/AccessEvaluationService.php`
21. `app/Http/Middleware/CheckPermission.php`

### **Critical for Modules (Week 3-4):**
22. `app/Domain/Provider/Models/Provider.php`
23. `app/Domain/Booking/Models/Booking.php`
24. `app/Domain/Workflow/Models/Workflow.php`
25. `app/Domain/Notification/Services/NotificationService.php`

---

## ✅ FINAL SUMMARY

This structure provides:

✅ **Clear Domain Separation** - Each domain is self-contained
✅ **Scalable Architecture** - Easy to add new modules
✅ **Strong Isolation** - Tenant data never leaks
✅ **Testable Code** - Each layer can be tested independently
✅ **API + Web Support** - Both from same codebase
✅ **Enterprise Ready** - SSO, RBAC, Audit, Multi-tenant
✅ **Queue Support** - Async processing for heavy tasks
✅ **Event-Driven** - Loose coupling via events
✅ **Repository Pattern** - Clean data access
✅ **Action Pattern** - Single responsibility
✅ **Service Layer** - Business logic orchestration

This is a **production-ready enterprise platform structure** that can scale to millions of users across thousands of tenants! 🚀