<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Test both scenarios: with and without remember me
echo "Testing Remember Me functionality...\n";

// Get a test user
$user = User::where('email', 'provider@example.com')->first();
if (!$user) {
    echo "Test user not found!\n";
    exit(1);
}

echo "User: {$user->email}\n";

// Test 1: Login without remember me
echo "\n=== Test 1: Login WITHOUT Remember Me ===\n";
Auth::logout(); // Ensure clean state

// Simulate login without remember me
$credentials = ['email' => $user->email, 'password' => 'password'];
$remember = false;

if (Auth::attempt($credentials, $remember)) {
    echo "Login successful without remember me\n";
    
    // Check if user is authenticated
    if (Auth::check()) {
        echo "User is authenticated\n";
        echo "User ID: " . Auth::id() . "\n";
        
        // Check session
        $sessionId = session()->getId();
        echo "Session ID: {$sessionId}\n";
        
        // Check if remember token exists in database
        $user->refresh();
        echo "Remember token in DB: " . ($user->remember_token ? 'Yes' : 'No') . "\n";
        if ($user->remember_token) {
            echo "Remember token: {$user->remember_token}\n";
        }
    } else {
        echo "User is NOT authenticated (unexpected!)\n";
    }
    
    Auth::logout();
} else {
    echo "Login failed without remember me\n";
}

// Test 2: Login with remember me
echo "\n=== Test 2: Login WITH Remember Me ===\n";

if (Auth::attempt($credentials, true)) {
    echo "Login successful with remember me\n";
    
    // Check if user is authenticated
    if (Auth::check()) {
        echo "User is authenticated\n";
        echo "User ID: " . Auth::id() . "\n";
        
        // Check session
        $sessionId = session()->getId();
        echo "Session ID: {$sessionId}\n";
        
        // Check if remember token exists in database
        $user->refresh();
        echo "Remember token in DB: " . ($user->remember_token ? 'Yes' : 'No') . "\n";
        if ($user->remember_token) {
            echo "Remember token: {$user->remember_token}\n";
        }
    } else {
        echo "User is NOT authenticated (unexpected!)\n";
    }
    
    Auth::logout();
} else {
    echo "Login failed with remember me\n";
}

echo "\n=== Session Table Check ===\n";
$sessions = DB::table('sessions')->get();
echo "Total sessions in database: " . $sessions->count() . "\n";

foreach ($sessions as $session) {
    echo "Session ID: {$session->id}\n";
    echo "User ID: {$session->user_id}\n";
    echo "IP Address: {$session->ip_address}\n";
    echo "User Agent: " . substr($session->user_agent, 0, 50) . "...\n";
    echo "Payload size: " . strlen($session->payload) . " bytes\n";
    echo "Last activity: " . date('Y-m-d H:i:s', $session->last_activity) . "\n";
    echo "---\n";
}