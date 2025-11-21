<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Domains\Identity\Services\AuthenticationService;
use App\Domains\Identity\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

// Test the exact issue: login with and without remember me
echo "Testing the Remember Me issue...\n";

// Get a test user
$user = User::where('email', 'provider@example.com')->first();
if (!$user) {
    echo "Test user not found!\n";
    exit(1);
}

echo "Test user: {$user->email}\n";

// Create services
$userRepository = new UserRepository(new User());
$authService = new AuthenticationService($userRepository);
$loginController = new LoginController($authService);

// Test 1: Login WITHOUT remember me (the failing case)
echo "\n=== Test 1: Login WITHOUT Remember Me (Problem Case) ===\n";

$request = Request::create('/login', 'POST', [
    'email' => $user->email,
    'password' => 'password'
    // No 'remember' parameter
]);

try {
    echo "Attempting login without remember me...\n";
    $response = $loginController->login($request);
    
    echo "SUCCESS: Login without remember me worked!\n";
    echo "Response status: " . $response->getStatusCode() . "\n";
    if ($response->isRedirect()) {
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";
    }
    
    // Check if user is authenticated
    echo "Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
    if (Auth::check()) {
        echo "Authenticated user: " . Auth::user()->email . "\n";
    }
    
    Auth::logout(); // Clean up
    
} catch (ValidationException $e) {
    echo "VALIDATION ERROR: Login without remember me failed!\n";
    echo "Errors: " . json_encode($e->errors()) . "\n";
} catch (Exception $e) {
    echo "EXCEPTION: Login without remember me failed!\n";
    echo "Message: " . $e->getMessage() . "\n";
}

// Test 2: Login WITH remember me (the working case)
echo "\n=== Test 2: Login WITH Remember Me (Working Case) ===\n";

$request = Request::create('/login', 'POST', [
    'email' => $user->email,
    'password' => 'password',
    'remember' => 'on' // Checkbox checked
]);

try {
    echo "Attempting login with remember me...\n";
    $response = $loginController->login($request);
    
    echo "SUCCESS: Login with remember me worked!\n";
    echo "Response status: " . $response->getStatusCode() . "\n";
    if ($response->isRedirect()) {
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";
    }
    
    // Check if user is authenticated
    echo "Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
    if (Auth::check()) {
        echo "Authenticated user: " . Auth::user()->email . "\n";
    }
    
    Auth::logout(); // Clean up
    
} catch (ValidationException $e) {
    echo "VALIDATION ERROR: Login with remember me failed!\n";
    echo "Errors: " . json_encode($e->errors()) . "\n";
} catch (Exception $e) {
    echo "EXCEPTION: Login with remember me failed!\n";
    echo "Message: " . $e->getMessage() . "\n";
}

echo "\n=== Debug Information ===\n";
echo "Session driver: " . config('session.driver') . "\n";
echo "Session lifetime: " . config('session.lifetime') . " minutes\n";