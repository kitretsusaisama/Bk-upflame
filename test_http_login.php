<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Handle the request through the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

// Test 1: Login without remember me
echo "Testing HTTP login without remember me...\n";

// Create a request that simulates a real HTTP POST
$request = Request::create('/login', 'POST', [
    'email' => 'provider@example.com',
    'password' => 'password'
    // No remember parameter
]);

// Process the request through the kernel
$response = $kernel->handle($request);

echo "Response status: " . $response->getStatusCode() . "\n";
echo "Response content preview: " . substr($response->getContent(), 0, 200) . "...\n";

if ($response->isRedirect()) {
    echo "Redirect URL: " . $response->getTargetUrl() . "\n";
}

// Test 2: Login with remember me
echo "\nTesting HTTP login with remember me...\n";

$request = Request::create('/login', 'POST', [
    'email' => 'provider@example.com',
    'password' => 'password',
    'remember' => 'on'
]);

$response = $kernel->handle($request);

echo "Response status: " . $response->getStatusCode() . "\n";
echo "Response content preview: " . substr($response->getContent(), 0, 200) . "...\n";

if ($response->isRedirect()) {
    echo "Redirect URL: " . $response->getTargetUrl() . "\n";
}

echo "\nDone.\n";