<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domains\Identity\Models\User;

// Test the complete login flow
echo "Comprehensive Remember Me Test\n";
echo "=============================\n";

// Get a test user
$user = User::where('email', 'provider@example.com')->first();
if (!$user) {
    echo "ERROR: Test user not found!\n";
    exit(1);
}

echo "Test user: {$user->email}\n";
echo "User ID: {$user->id}\n";

// Test 1: Login without remember me
echo "\n--- Test 1: Login WITHOUT Remember Me ---\n";

// Create a proper HTTP request with session
$request = Request::create('/login', 'POST', [
    'email' => $user->email,
    'password' => 'password'
    // No remember parameter
]);

// Start a session for the request
session()->start();
$request->setLaravelSession(session());

echo "Before login - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

// Try to authenticate manually
$credentials = ['email' => $user->email, 'password' => 'password'];
$result = Auth::attempt($credentials, false); // false = no remember me

echo "Auth::attempt result: " . ($result ? 'true' : 'false') . "\n";
echo "After attempt - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

if ($result) {
    echo "SUCCESS: Login without remember me worked!\n";
    echo "Authenticated user ID: " . Auth::id() . "\n";
    
    // Regenerate session
    session()->regenerate();
    
    echo "Session regenerated\n";
    echo "Session ID: " . session()->getId() . "\n";
    
    // Check if user is still authenticated
    echo "After session regenerate - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
    
    Auth::logout();
} else {
    echo "FAILED: Login without remember me failed!\n";
}

// Test 2: Login with remember me
echo "\n--- Test 2: Login WITH Remember Me ---\n";

// Create a new session
session()->flush();
session()->start();

echo "Before login - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

// Try to authenticate with remember me
$result = Auth::attempt($credentials, true); // true = with remember me

echo "Auth::attempt result: " . ($result ? 'true' : 'false') . "\n";
echo "After attempt - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

if ($result) {
    echo "SUCCESS: Login with remember me worked!\n";
    echo "Authenticated user ID: " . Auth::id() . "\n";
    
    // Regenerate session
    session()->regenerate();
    
    echo "Session regenerated\n";
    echo "Session ID: " . session()->getId() . "\n";
    
    // Check if user is still authenticated
    echo "After session regenerate - Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
    
    Auth::logout();
} else {
    echo "FAILED: Login with remember me failed!\n";
}

echo "\n=== Session Configuration ===\n";
echo "Session driver: " . config('session.driver') . "\n";
echo "Session domain: " . config('session.domain') . "\n";
echo "Session path: " . config('session.path') . "\n";
echo "Session lifetime: " . config('session.lifetime') . " minutes\n";

echo "\nTest completed.\n";