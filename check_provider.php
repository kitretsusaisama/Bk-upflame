<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\User;
use App\Domains\Provider\Models\Provider;

// Check provider user
echo "Checking provider user...\n";
$user = User::where('email', 'provider@example.com')->first();

if ($user) {
    echo "User found: {$user->email}\n";
    echo "User ID: {$user->id}\n";
    
    // Check if user has provider profile
    $provider = $user->provider;
    if ($provider) {
        echo "Provider profile found: {$provider->id}\n";
    } else {
        echo "No provider profile found!\n";
        echo "This is likely why the provider is being redirected back to login.\n";
    }
    
    // Check roles
    $user->load('roles');
    $roles = $user->roles->pluck('name')->toArray();
    echo "Roles: " . implode(', ', $roles) . "\n";
} else {
    echo "Provider user not found!\n";
}