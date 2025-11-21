# Laravel Folder Structure and Code Stubs

## Overview

This document outlines the recommended Laravel folder structure for the enterprise-grade multi-tenant backend platform. The structure follows Domain-Driven Design (DDD) principles with a modular monolith approach.

## Directory Structure

```
app/
├── Console/
│   └── Commands/
├── Domains/
│   ├── Identity/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Events/
│   │   ├── Listeners/
│   │   ├── Notifications/
│   │   └── Exceptions/
│   ├── Access/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Policies/
│   │   └── Exceptions/
│   ├── Tenant/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   └── Exceptions/
│   ├── Workflow/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Events/
│   │   └── Exceptions/
│   ├── Provider/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   └── Exceptions/
│   ├── User/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   └── Exceptions/
│   ├── Booking/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   └── Exceptions/
│   └── Notification/
│       ├── Models/
│       ├── Repositories/
│       ├── Services/
│       ├── Http/
│       │   ├── Controllers/
│       │   ├── Requests/
│       │   └── Resources/
│       └── Exceptions/
├── Infrastructure/
│   ├── Database/
│   │   ├── Factories/
│   │   ├── Migrations/
│   │   └── Seeders/
│   ├── Cache/
│   ├── Queue/
│   ├── Security/
│   ├── SSO/
│   └── PolicyEngine/
├── Application/
│   ├── Commands/
│   ├── Queries/
│   └── Services/
├── Interfaces/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── CLI/
│   └── Consumers/
└── Support/
    ├── Traits/
    ├── Helpers/
    └── Enums/

config/
database/
routes/
resources/
tests/
storage/
```

## Key Domain Classes

### Identity Domain

#### Models
```php
<?php

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'config_json',
        'status'
    ];

    protected $casts = [
        'config_json' => 'array',
        'status' => 'string'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function domains()
    {
        return $this->hasMany(TenantDomain::class);
    }
}
```

```php
<?php

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'tenant_id',
        'email',
        'password',
        'status',
        'primary_role_id',
        'mfa_enabled',
        'email_verified',
        'phone_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'mfa_enabled' => 'boolean',
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'status' => 'string'
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }
}
```

#### Services
```php
<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate($email, $password, $tenantId = null)
    {
        $user = $this->userRepository->findByEmail($email, $tenantId);
        
        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }
        
        if ($user->status !== 'active') {
            return null;
        }
        
        return $user;
    }

    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }
}
```

### Access Domain

#### Models
```php
<?php

namespace App\Domains\Access\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'role_family',
        'is_system'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'role_family' => 'string'
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
```

```php
<?php

namespace App\Domains\Access\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'resource',
        'action',
        'description'
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
```

#### Policies
```php
<?php

namespace App\Domains\Access\Policies;

use App\Domains\Identity\Models\User;
use App\Domains\Booking\Models\Booking;

class BookingPolicy
{
    public function create(User $user)
    {
        return $user->roles->contains('name', 'Customer');
    }

    public function update(User $user, Booking $booking)
    {
        // Check if user owns the booking or has admin role
        return $user->id === $booking->user_id || 
               $user->roles->contains('name', 'Admin');
    }

    public function cancel(User $user, Booking $booking)
    {
        // Implement business logic for cancellation policy
        return $user->id === $booking->user_id && 
               $booking->status === 'confirmed';
    }
}
```

### Tenant Domain

#### Services
```php
<?php

namespace App\Domains\Tenant\Services;

use App\Domains\Tenant\Models\Tenant;
use App\Domains\Tenant\Repositories\TenantRepository;

class TenantService
{
    protected $tenantRepository;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function createTenant($data)
    {
        // Create tenant
        $tenant = $this->tenantRepository->create($data);
        
        // Create default roles for tenant
        $this->createDefaultRoles($tenant);
        
        // Create default configurations
        $this->createDefaultConfigurations($tenant);
        
        return $tenant;
    }

    protected function createDefaultRoles(Tenant $tenant)
    {
        // Implementation for creating default roles
    }

    protected function createDefaultConfigurations(Tenant $tenant)
    {
        // Implementation for creating default configurations
    }

    public function resolveTenantFromDomain($domain)
    {
        return $this->tenantRepository->findByDomain($domain);
    }
}
```

### Workflow Domain

#### Models
```php
<?php

namespace App\Domains\Workflow\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [
        'tenant_id',
        'definition_id',
        'entity_type',
        'entity_id',
        'current_step_key',
        'status',
        'context_json'
    ];

    protected $casts = [
        'context_json' => 'array',
        'status' => 'string'
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function definition()
    {
        return $this->belongsTo(WorkflowDefinition::class, 'definition_id');
    }

    public function steps()
    {
        return $this->hasMany(WorkflowStep::class);
    }

    public function currentStep()
    {
        return $this->hasOne(WorkflowStep::class)
                    ->where('step_key', $this->current_step_key);
    }
}
```

### Provider Domain

#### Models
```php
<?php

namespace App\Domains\Provider\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'provider_type',
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'workflow_id',
        'profile_json'
    ];

    protected $casts = [
        'profile_json' => 'array',
        'status' => 'string',
        'provider_type' => 'string'
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function documents()
    {
        return $this->hasMany(ProviderDocument::class);
    }

    public function services()
    {
        return $this->hasMany(ProviderService::class);
    }

    public function availability()
    {
        return $this->hasMany(ProviderAvailability::class);
    }
}
```

### Booking Domain

#### Models
```php
<?php

namespace App\Domains\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'provider_id',
        'service_id',
        'booking_reference',
        'status',
        'scheduled_at',
        'duration_minutes',
        'amount',
        'currency',
        'metadata'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'metadata' => 'array',
        'status' => 'string',
        'amount' => 'decimal:2'
    ];

    protected $dates = [
        'scheduled_at'
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(BookingStatusHistory::class);
    }

    // Methods
    public function isCancelable()
    {
        // Implement business logic for cancellation eligibility
        return $this->status === 'confirmed' && 
               $this->scheduled_at->isFuture();
    }
}
```

## Repository Pattern Examples

### Base Repository
```php
<?php

namespace App\Domains\Base\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findAll($limit = 20)
    {
        return $this->model->paginate($limit);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->findById($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = $this->findById($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
```

### User Repository
```php
<?php

namespace App\Domains\Identity\Repositories;

use App\Domains\Base\Repositories\BaseRepository;
use App\Domains\Identity\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email, $tenantId = null)
    {
        $query = $this->model->where('email', $email);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->first();
    }

    public function findByStatus($status, $limit = 20)
    {
        return $this->model->where('status', $status)->paginate($limit);
    }
}
```

## Service Layer Examples

### Booking Service
```php
<?php

namespace App\Domains\Booking\Services;

use App\Domains\Booking\Models\Booking;
use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Notification\Services\NotificationService;
use Illuminate\Support\Str;

class BookingService
{
    protected $bookingRepository;
    protected $notificationService;

    public function __construct(
        BookingRepository $bookingRepository,
        NotificationService $notificationService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->notificationService = $notificationService;
    }

    public function createBooking($data)
    {
        // Generate booking reference
        $data['booking_reference'] = 'BK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        
        // Set initial status
        $data['status'] = 'processing';
        
        // Create booking
        $booking = $this->bookingRepository->create($data);
        
        // Send confirmation notification
        $this->sendBookingConfirmation($booking);
        
        return $booking;
    }

    public function cancelBooking($bookingId, $userId)
    {
        $booking = $this->bookingRepository->findById($bookingId);
        
        // Check if user can cancel this booking
        if ($booking->user_id !== $userId) {
            throw new \Exception('Unauthorized to cancel this booking');
        }
        
        // Check if booking is eligible for cancellation
        if (!$booking->isCancelable()) {
            throw new \Exception('Booking cannot be cancelled');
        }
        
        // Update status
        $booking = $this->bookingRepository->update($bookingId, [
            'status' => 'cancelled'
        ]);
        
        // Record status change
        $booking->statusHistory()->create([
            'status' => 'cancelled',
            'changed_by' => $userId,
            'notes' => 'Cancelled by user'
        ]);
        
        // Send cancellation notification
        $this->sendCancellationNotification($booking);
        
        return $booking;
    }

    protected function sendBookingConfirmation(Booking $booking)
    {
        // Implementation for sending booking confirmation
        $this->notificationService->send([
            'recipient_user_id' => $booking->user_id,
            'template_name' => 'booking_confirmation',
            'channel' => 'email',
            'variables' => [
                'booking_reference' => $booking->booking_reference,
                'scheduled_time' => $booking->scheduled_at->format('Y-m-d H:i'),
                // Add more variables as needed
            ]
        ]);
    }

    protected function sendCancellationNotification(Booking $booking)
    {
        // Implementation for sending cancellation notification
    }
}
```

## HTTP Controllers

### Auth Controller
```php
<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Domains\Identity\Http\Requests\LoginRequest;
use App\Domains\Identity\Services\AuthenticationService;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $user = $this->authService->authenticate(
            $request->email,
            $request->password,
            $request->tenant_id
        );

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Invalid email or password'
                ]
            ], 401);
        }

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('auth.token_expiration', 900)
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Successfully logged out'
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }
}
```

## Middleware

### Tenant Resolution Middleware
```php
<?php

namespace App\Interfaces\Http\Middleware;

use App\Domains\Tenant\Services\TenantService;
use Closure;
use Illuminate\Http\Request;

class TenantResolutionMiddleware
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Try to resolve tenant from different sources
        $tenant = $this->resolveTenant($request);
        
        if (!$tenant) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant not found'
                ]
            ], 404);
        }
        
        // Set tenant in request and application context
        $request->attributes->set('tenant', $tenant);
        app()->instance('tenant', $tenant);
        
        return $next($request);
    }

    protected function resolveTenant(Request $request)
    {
        // 1. Try from header
        $tenantId = $request->header('X-Tenant-ID');
        if ($tenantId) {
            return $this->tenantService->findById($tenantId);
        }
        
        // 2. Try from host header
        $host = $request->getHost();
        if ($host) {
            return $this->tenantService->resolveTenantFromDomain($host);
        }
        
        // 3. Try from query parameter (fallback)
        $tenantId = $request->query('tenant_id');
        if ($tenantId) {
            return $this->tenantService->findById($tenantId);
        }
        
        return null;
    }
}
```

## Event System

### Booking Events
```php
<?php

namespace App\Domains\Booking\Events;

use App\Domains\Booking\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated
{
    use Dispatchable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }
}
```

### Event Listeners
```php
<?php

namespace App\Domains\Booking\Listeners;

use App\Domains\Booking\Events\BookingCreated;
use App\Domains\Notification\Services\NotificationService;

class SendBookingConfirmation
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(BookingCreated $event)
    {
        $this->notificationService->send([
            'recipient_user_id' => $event->booking->user_id,
            'template_name' => 'booking_confirmation',
            'channel' => 'email',
            'variables' => [
                'booking_reference' => $event->booking->booking_reference,
                'scheduled_time' => $event->booking->scheduled_at->format('Y-m-d H:i'),
            ]
        ]);
    }
}
```

## Exception Handling

### Custom Exceptions
```php
<?php

namespace App\Domains\Booking\Exceptions;

use Exception;

class BookingNotCancelableException extends Exception
{
    public function __construct($message = "Booking cannot be cancelled", $code = 400)
    {
        parent::__construct($message, $code);
    }
}
```

### Global Exception Handler
```php
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Handle custom exceptions
        if ($exception instanceof BookingNotCancelableException) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'BOOKING_NOT_CANCELABLE',
                    'message' => $exception->getMessage()
                ]
            ], $exception->getCode());
        }

        return parent::render($request, $exception);
    }
}
```

## Queue Jobs

### Notification Job
```php
<?php

namespace App\Domains\Notification\Jobs;

use App\Domains\Notification\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationData;

    public function __construct($notificationData)
    {
        $this->notificationData = $notificationData;
    }

    public function handle(NotificationService $notificationService)
    {
        $notificationService->send($this->notificationData);
    }
}
```

This folder structure and code stubs provide a solid foundation for implementing the enterprise-grade multi-tenant backend platform using Laravel with Domain-Driven Design principles. The modular approach ensures clear separation of concerns while maintaining the flexibility to extract modules as microservices in future phases.