# Security

> **Comprehensive security features and best practices**

## Table of Contents

- [Security Architecture](#security-architecture)
- [Tenant Isolation](#tenant-isolation)
- [Session Security](#session-security)
- [CSRF Protection](#csrf-protection)
- [XSS Prevention](#xss-prevention)
- [SQL Injection Prevention](#sql-injection-prevention)
- [Authentication Security](#authentication-security)
- [Authorization & RBAC](#authorization--rbac)
- [Audit Logging](#audit-logging)
- [Data Encryption](#data-encryption)
- [Security Headers](#security-headers)
- [Best Practices](#best-practices)
- [Cross-Links](#cross-links)

## Security Architecture

The platform implements **defense-in-depth** with multiple layers of security:

1. **Network Layer** - TLS/HTTPS, rate limiting
2. **Application Layer** - Authentication, authorization, CSRF
3. **Data Layer** - Encryption, tenant isolation, audit logs
4. **Access Layer** - RBAC, impersonation logging, session management

## Tenant Isolation

### Global Scopes

All tenant-scoped models automatically filter by `tenant_id`:

```php
// Automatic scoping
User::all(); // Returns only users for current tenant

// Manual bypass (use with extreme caution)
User::withoutGlobalScope('tenant')->get();
```

### Database Indexes

All multi-tenant tables have composite indexes:

```sql
KEY `idx_tenant_created` (`tenant_id`, `created_at`)
KEY `idx_tenant_status` (`tenant_id`, `status`)
```

**Why?** Optimizes tenant-scoped queries and prevents cross-tenant data leaks.

### Validation

Always validate tenant ownership before operations:

```php
public function update(Request $request, Booking $booking)
{
    // ✅ Good: Eloquent route model binding respects global scope
    $booking->update($request->validated());
    
    // ❌ Bad: Manual ID lookup without validation
    $booking = Booking::withoutGlobalScope('tenant')->find($id);
}
```

## Session Security

### Session Configuration

[`config/session.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/session.php):

```php
return [
    'driver' => 'database',           // Database for multi-server
    'lifetime' => 120,                // 2 hours
    'expire_on_close' => false,
    'encrypt' => false,               // Not needed for database driver
    'secure' => env('SESSION_SECURE_COOKIE', true),    // HTTPS only
    'http_only' => true,              // Prevent JavaScript access
    'same_site' => 'lax',            // CSRF protection
];
```

### Session Regeneration

**On login:**
```php
Auth::login($user);
$request->session()->regenerate(); // Prevent session fixation
```

**On privilege escalation:**
```php
$user->assignRole('Admin');
$request->session()->regenerate(); // Regenerate after permission change
```

### SessionSecurity Middleware

[`app/Http/Middleware/SessionSecurity.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Http/Middleware/SessionSecurity.php):

```php
class SessionSecurity
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Validate session hasn't been hijk hijacked
        if ($this->sessionHijacked($request)) {
            Auth::logout();
            return redirect('/login')->with('error', 'Session security check failed');
        }
        
        // 2. Update session activity
        $this->updateSessionActivity($request);
        
        return $next($request);
    }
    
    protected function sessionHijacked(Request $request): bool
    {
        $storedFingerprint = session('fingerprint');
        
        if (!$storedFingerprint) {
            session(['fingerprint' => $this->generateFingerprint($request)]);
            return false;
        }
        
        return $storedFingerprint !== $this->generateFingerprint($request);
    }
    
    protected function generateFingerprint(Request $request): string
    {
        return hash('sha256', $request->userAgent() . $request->ip());
    }
}
```

### Idle Timeout

[`app/Http/Middleware/AutoLogoutIdle.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Http/Middleware/AutoLogoutIdle.php):

```php
class AutoLogoutIdle
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = config('dashboard.idle_timeout', 30); // minutes
        
        if ($timeout > 0) {
            $lastActivity = session('last_activity_time');
            
            if ($lastActivity && now()->diffInMinutes($lastActivity) > $timeout) {
                Auth::logout();
                return redirect('/login')->with('info', 'Logged out due to inactivity');
            }
            
            session(['last_activity_time' => now()]);
        }
        
        return $next($request);
    }
}
```

## CSRF Protection

### Laravel's Built-In CSRF

All POST, PUT, PATCH, DELETE routes automatically require CSRF token:

```blade
<form method="POST" action="/users">
    @csrf  <!-- Generates hidden input with token -->
    ...
</form>
```

**AJAX requests:**
```javascript
// Laravel automatically includes CSRF token in X-CSRF-TOKEN header
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
```

### Excluded Routes

```php
// app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'api/*',        // API routes use Sanctum tokens
    'webhooks/*',   // Webhook callbacks (validated separately)
];
```

## XSS Prevention

### Blade Auto-Escaping

Blade templates automatically escape output:

```blade
<!-- ✅ Safe: Auto-escaped -->
<p>{{ $userInput }}</p>

<!-- ❌ Dangerous: Not escaped -->
<p>{!! $userInput !!}</p>  <!-- Only use for trusted HTML -->
```

### Content Security Policy

Add CSP headers:

```php
// app/Http/Middleware/SecurityHeaders.php
public function handle(Request $request, Closure $next)
{
    $response = $next($request);
    
    $response->headers->set('Content-Security-Policy', 
        "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.example.com"
    );
    
    return $response;
}
```

## SQL Injection Prevention

### Parameterized Queries

Eloquent ORM automatically parameterizes queries:

```php
// ✅ Safe: Parameterized
User::where('email', $email)->first();

// ✅ Safe: Explicitly parameterized
DB::select('SELECT * FROM users WHERE email = ?', [$email]);

// ❌ Dangerous: Raw concatenation
DB::select("SELECT * FROM users WHERE email = '$email'");
```

### Input Validation

Always validate user input:

```php
$request->validate([
    'email' => 'required|email|max:255',
    'age' => 'required|integer|min:18|max:120',
]);
```

## Authentication Security

### Password Hashing

Uses bcrypt with 12 rounds (configurable):

```php
// .env
BCRYPT_ROUNDS=12

// Hashing
$hashed = Hash::make($password);

// Verification
Hash::check($password, $hashed);
```

### Rate Limiting

Login attempts limited:

```php
protected function incrementLoginAttempts(Request $request)
{
    $key = 'login|' . $request->ip();
    RateLimiter::hit($key, 300); // 5-minute decay
    
    if (RateLimiter::tooManyAttempts($key, 5)) {
        throw new TooManyRequestsException();
    }
}
```

### Account Lockout

After 5 failed attempts, lock account for 30 minutes:

```php
if ($user->failed_login_attempts >= 5) {
    $user->update(['locked_until' => now()->addMinutes(30)]);
}
```

## Authorization & RBAC

### Permission Checks

```php
// Middleware
Route::middleware('permission:delete-user')->delete('/users/{user}', ...);

// Controller
if (!auth()->user()->hasPermission('view-reports')) {
    abort(403);
}

// Blade
@if (auth()->user()->hasPermission('create-booking'))
    <button>Create Booking</button>
@endif
```

### Policy-Based Authorization

```php
// app/Policies/BookingPolicy.php
public function cancel(User $user, Booking $booking): bool
{
    // Only booking owner or admin can cancel
    return $booking->user_id === $user->id || $user->hasRole('Admin');
}

// Usage
if ($request->user()->cannot('cancel', $booking)) {
    abort(403);
}
```

## Audit Logging

### Access Logs

[`database/migrations/*_create_access_logs_table.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/database/migrations):

```php
Schema::create('access_logs', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained();
    $table->foreignUuid('tenant_id')->nullable()->constrained();
    $table->string('action');          // e.g., "user.deleted"
    $table->string('resource_type');   // e.g., "User"
    $table->string('resource_id');
    $table->json('changes')->nullable();
    $table->ipAddress('ip_address');
    $table->text('user_agent');
    $table->timestamps();
});
```

### Audit Logger Middleware

[`app/Http/Middleware/AuditLogger.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Http/Middleware/AuditLogger.php):

```php
class AuditLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Log sensitive actions
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            AccessLog::create([
                'user_id' => auth()->id(),
                'tenant_id' => app('tenant.id'),
                'action' => $request->route()->getName(),
                'resource_type' => $this->getResourceType($request),
                'resource_id' => $this->getResourceId($request),
                'changes' => $request->all(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        return $response;
    }
}
```

### Impersonation Audit

[`database/migrations/*_create_impersonation_logs_table.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/database/migrations):

```sql
CREATE TABLE impersonation_logs (
    id VARCHAR(26) PRIMARY KEY,
    impersonator_id VARCHAR(26) NOT NULL,
    impersonated_id VARCHAR(26) NOT NULL,
    tenant_id VARCHAR(26),
    started_at TIMESTAMP NOT NULL,
    ended_at TIMESTAMP NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (impersonator_id) REFERENCES users(id),
    FOREIGN KEY (impersonated_id) REFERENCES users(id)
);
```

## Data Encryption

### Encrypted Database Columns

```php
use Illuminate\Database\Eloquent\Casts\Encrypted;

class MfaMethod extends Model
{
    protected $casts = [
        'secret' => Encrypted::class,
        'backup_codes' => 'encrypted:array',
    ];
}
```

### Application Key

```bash
# Generate strong encryption key
php artisan key:generate

# .env
APP_KEY=base64:random_32_byte_key_here
```

### HTTPS Enforcement

```php
// app/Providers/AppServiceProvider.php
public function boot()
{
    if (app()->environment('production')) {
        URL::forceScheme('https');
    }
}
```

## Security Headers

### Recommended Headers

```php
// app/Http/Middleware/SecurityHeaders.php
public function handle(Request $request, Closure $next)
{
    $response = $next($request);
    
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
    
    return $response;
}
```

## Best Practices

### ✅ DO

1. **Always validate input** before processing
2. **Use Eloquent ORM** instead of raw queries
3. **Regenerate sessions** on privilege changes
4. **Log security events** (failed logins, permission changes)
5. **Use HTTPS** in production
6. **Implement rate limiting** on sensitive endpoints
7. **Regular security audits** of permission assignments
8. **Keep dependencies updated** (`composer update`)

### ❌ DON'T

1. **Don't store passwords in plain text**
2. **Don't bypass global scopes** without explicit checks
3. **Don't trust user input** (always validate)
4. **Don't expose sensitive data** in error messages
5. **Don't log passwords** or tokens
6. **Don't use `{!! !!}` in Blade** unless trusted content
7. **Don't commit `.env`** to version control

## Cross-Links

- [Authentication](authentication.md) - Authentication flows
- [Tenancy](tenancy.md) - Tenant isolation security
- [RBAC](rbac.md) - Authorization system
- [Impersonation](impersonation.md) - Impersonation security
- [Logging & Auditing](logging-and-auditing.md) - Audit trails
