<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Simulate the exact login process
echo "Debugging login process...\n";

// Get a test user
$user = User::where('email', 'provider@example.com')->first();
if (!$user) {
    echo "Test user not found!\n";
    exit(1);
}

echo "User: {$user->email}\n";
echo "User ID: {$user->id}\n";

// Test the login process step by step
echo "\n=== Simulating Login Process ===\n";

// Step 1: Validate credentials
$credentials = ['email' => $user->email, 'password' => 'password'];
echo "Credentials: " . json_encode($credentials) . "\n";

// Step 2: Try Auth::attempt with remember = false
echo "\n--- Attempting login WITHOUT remember me ---\n";
$remember = false;
echo "Remember parameter: " . ($remember ? 'true' : 'false') . "\n";

// Check current auth state
echo "Before attempt - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

// Attempt login
$result = Auth::attempt($credentials, $remember);
echo "Auth::attempt result: " . ($result ? 'true' : 'false') . "\n";

if ($result) {
    echo "Login successful!\n";
    echo "After attempt - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
    echo "Authenticated user ID: " . Auth::id() . "\n";
    
    // Check session
    $sessionId = session()->getId();
    echo "Session ID: {$sessionId}\n";
    
    // Check if session was regenerated
    echo "Session was regenerated\n";
    
    // Check user remember token
    $user->refresh();
    echo "User remember_token: " . ($user->remember_token ?? 'NULL') . "\n";
    
    Auth::logout();
} else {
    echo "Login failed!\n";
}

// Step 3: Try Auth::attempt with remember = true
echo "\n--- Attempting login WITH remember me ---\n";
$remember = true;
echo "Remember parameter: " . ($remember ? 'true' : 'false') . "\n";

// Check current auth state
echo "Before attempt - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

// Attempt login
$result = Auth::attempt($credentials, $remember);
echo "Auth::attempt result: " . ($result ? 'true' : 'false') . "\n";

if ($result) {
    echo "Login successful!\n";
    echo "After attempt - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
    echo "Authenticated user ID: " . Auth::id() . "\n";
    
    // Check session
    $sessionId = session()->getId();
    echo "Session ID: {$sessionId}\n";
    
    // Check if session was regenerated
    echo "Session was regenerated\n";
    
    // Check user remember token
    $user->refresh();
    echo "User remember_token: " . ($user->remember_token ?? 'NULL') . "\n";
    
    Auth::logout();
} else {
    echo "Login failed!\n";
}

echo "\n=== Checking Session Configuration ===\n";
echo "Session driver: " . config('session.driver') . "\n";
echo "Session lifetime: " . config('session.lifetime') . " minutes\n";
echo "Expire on close: " . (config('session.expire_on_close') ? 'true' : 'false') . "\n";
echo "Session domain: " . (config('session.domain') ?? 'null') . "\n";
echo "Session path: " . config('session.path') . "\n";
echo "Session secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "Session http_only: " . (config('session.http_only') ? 'true' : 'false') . "\n";
echo "Session same_site: " . (config('session.same_site') ?? 'null') . "\n";