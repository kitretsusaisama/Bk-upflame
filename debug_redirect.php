<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;

// Check if we can authenticate and get the redirect route
echo "Debugging redirect issue...\n";
$user = User::where('email', 'provider@example.com')->first();

if ($user) {
    echo "User found: {$user->email}\n";
    
    // Authenticate the user
    Auth::login($user);
    
    if (Auth::check()) {
        echo "User authenticated successfully\n";
        
        // Check what route they should be redirected to
        $user->load('roles');
        
        echo "User roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
        
        // Check if user hasRole works
        echo "Has Provider role: " . ($user->hasRole('Provider') ? 'Yes' : 'No') . "\n";
        
        // Try to determine dashboard route manually
        $roleRedirects = [
            'Super Admin' => 'superadmin.dashboard',
            'Tenant Admin' => 'tenantadmin.dashboard',
            'Provider' => 'provider.dashboard',
            'Ops' => 'ops.dashboard',
            'Premium Customer' => 'customer.dashboard',
            'Customer' => 'customer.dashboard',
        ];
        
        $redirectRoute = 'customer.dashboard'; // default
        
        if ($user->primary_role_id) {
            $primaryRole = $user->roles->firstWhere('id', $user->primary_role_id);
            if ($primaryRole && isset($roleRedirects[$primaryRole->name])) {
                $redirectRoute = $roleRedirects[$primaryRole->name];
                echo "Primary role redirect: {$redirectRoute}\n";
            }
        }
        
        foreach ($roleRedirects as $role => $route) {
            if ($user->hasRole($role)) {
                $redirectRoute = $route;
                echo "Found role {$role}, redirecting to: {$redirectRoute}\n";
                break;
            }
        }
        
        echo "Final redirect route: {$redirectRoute}\n";
        
        // Try to generate the route URL
        try {
            $routeUrl = route($redirectRoute);
            echo "Route URL: {$routeUrl}\n";
        } catch (Exception $e) {
            echo "Error generating route: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "Authentication failed\n";
    }
} else {
    echo "User not found\n";
}