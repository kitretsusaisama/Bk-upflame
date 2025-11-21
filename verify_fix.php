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

echo "Verifying Remember Me Fix\n";
echo "========================\n";

// Get a test user
$user = User::where('email', 'provider@example.com')->first();
if (!$user) {
    echo "ERROR: Test user not found!\n";
    exit(1);
}

echo "Test user: {$user->email}\n";

// Create the necessary services
$userRepository = new UserRepository(new User());
$authService = new AuthenticationService($userRepository);
$loginController = new LoginController($authService);

// Test 1: Login without remember me (should now work)
echo "\n--- Test 1: Login WITHOUT Remember Me ---\n";

// Create a proper request with session
$request = Request::create('/login', 'POST', [
    'email' => $user->email,
    'password' => 'password'
]);

// Start session
session()->start();
$request->setLaravelSession(session());

try {
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
    
    Auth::logout();
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Test 2: Login with remember me (should still work)
echo "\n--- Test 2: Login WITH Remember Me ---\n";

// Create a new request
$request = Request::create('/login', 'POST', [
    'email' => $user->email,
    'password' => 'password',
    'remember' => 'on'
]);

// Start new session
session()->flush();
session()->start();
$request->setLaravelSession(session());

try {
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
    
    Auth::logout();
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Session Configuration ===\n";
echo "SESSION_DOMAIN: " . config('session.domain') . "\n";
echo "SESSION_DRIVER: " . config('session.driver') . "\n";
echo "SESSION_SECURE_COOKIE: " . (config('session.secure') ? 'true' : 'false') . "\n";

echo "\nFix verification completed!\n";