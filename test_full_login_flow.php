<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Domains\Identity\Services\AuthenticationService;
use App\Domains\Identity\Repositories\UserRepository;

// Simulate the full login flow including redirect
echo "Testing full login flow...\n";

// Get a test user
$user = User::where('email', 'provider@example.com')->first();
if (!$user) {
    echo "Test user not found!\n";
    exit(1);
}

echo "User: {$user->email}\n";

// Create the necessary services
$userRepository = new UserRepository(new User());
$authService = new AuthenticationService($userRepository);
$loginController = new LoginController($authService);

// Test 1: Login without remember me and check redirect
echo "\n=== Test 1: Full Login Flow WITHOUT Remember Me ===\n";

// Create a mock request
$request = new \Illuminate\Http\Request();
$request->setMethod('POST');
$request->request->add([
    'email' => $user->email,
    'password' => 'password',
    'remember' => null // Not checked
]);

// Validate the request (this is what Laravel does internally)
$validator = \Illuminate\Support\Facades\Validator::make(
    $request->all(), 
    [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]
);

if ($validator->fails()) {
    echo "Validation failed: " . implode(', ', $validator->errors()->all()) . "\n";
} else {
    echo "Validation passed\n";
    
    // Try to login using the controller method
    try {
        echo "Calling login method...\n";
        $response = $loginController->login($request);
        
        echo "Login method executed successfully\n";
        echo "Response type: " . get_class($response) . "\n";
        echo "Response status: " . $response->getStatusCode() . "\n";
        
        if ($response->isRedirect()) {
            echo "Redirect URL: " . $response->getTargetUrl() . "\n";
        }
        
        // Check if user is authenticated after login
        echo "Auth check after login: " . (Auth::check() ? 'true' : 'false') . "\n";
        if (Auth::check()) {
            echo "Authenticated user: " . Auth::user()->email . "\n";
        }
        
    } catch (Exception $e) {
        echo "Exception during login: " . $e->getMessage() . "\n";
        echo "Exception trace: " . $e->getTraceAsString() . "\n";
    }
}

// Test 2: Login with remember me and check redirect
echo "\n=== Test 2: Full Login Flow WITH Remember Me ===\n";

// Create a new mock request
$request = new \Illuminate\Http\Request();
$request->setMethod('POST');
$request->request->add([
    'email' => $user->email,
    'password' => 'password',
    'remember' => 'on' // Checked
]);

// Validate the request
$validator = \Illuminate\Support\Facades\Validator::make(
    $request->all(), 
    [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]
);

if ($validator->fails()) {
    echo "Validation failed: " . implode(', ', $validator->errors()->all()) . "\n";
} else {
    echo "Validation passed\n";
    
    // Try to login using the controller method
    try {
        echo "Calling login method...\n";
        $response = $loginController->login($request);
        
        echo "Login method executed successfully\n";
        echo "Response type: " . get_class($response) . "\n";
        echo "Response status: " . $response->getStatusCode() . "\n";
        
        if ($response->isRedirect()) {
            echo "Redirect URL: " . $response->getTargetUrl() . "\n";
        }
        
        // Check if user is authenticated after login
        echo "Auth check after login: " . (Auth::check() ? 'true' : 'false') . "\n";
        if (Auth::check()) {
            echo "Authenticated user: " . Auth::user()->email . "\n";
        }
        
    } catch (Exception $e) {
        echo "Exception during login: " . $e->getMessage() . "\n";
        echo "Exception trace: " . $e->getTraceAsString() . "\n";
    }
}

echo "\n=== Session State After Tests ===\n";
echo "Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
if (Auth::check()) {
    echo "Authenticated user: " . Auth::user()->email . "\n";
}