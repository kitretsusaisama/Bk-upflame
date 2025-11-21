<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;

echo "Final Remember Me Test\n";
echo "=====================\n";

// Get a test user
$user = User::where('email', 'provider@example.com')->first();
if (!$user) {
    echo "ERROR: Test user not found!\n";
    exit(1);
}

echo "Test user: {$user->email}\n";

// Test login without remember me
echo "\n--- Testing login WITHOUT remember me ---\n";

// Start a session
session()->start();
echo "Session started\n";

$credentials = ['email' => $user->email, 'password' => 'password'];
$result = Auth::attempt($credentials, false);

echo "Auth attempt result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
echo "Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

if ($result) {
    echo "User authenticated successfully!\n";
    echo "User ID: " . Auth::id() . "\n";
    
    // Test session persistence
    session()->regenerate();
    echo "Session regenerated\n";
    echo "Session ID: " . session()->getId() . "\n";
    echo "Auth check after regenerate: " . (Auth::check() ? 'true' : 'false') . "\n";
    
    Auth::logout();
    echo "User logged out\n";
}

// Test login with remember me
echo "\n--- Testing login WITH remember me ---\n";

// Start a new session
session()->flush();
session()->start();
echo "New session started\n";

$result = Auth::attempt($credentials, true);

echo "Auth attempt result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
echo "Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";

if ($result) {
    echo "User authenticated successfully!\n";
    echo "User ID: " . Auth::id() . "\n";
    
    // Test session persistence
    session()->regenerate();
    echo "Session regenerated\n";
    echo "Session ID: " . session()->getId() . "\n";
    echo "Auth check after regenerate: " . (Auth::check() ? 'true' : 'false') . "\n";
    
    Auth::logout();
    echo "User logged out\n";
}

echo "\nTest completed successfully!\n";