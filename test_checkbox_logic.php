<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

// Test the checkbox logic
echo "Testing checkbox logic...\n";

// Test 1: Checkbox checked (sends "on")
echo "\n=== Test 1: Checkbox CHECKED ===\n";
$request = new Request();
$request->setMethod('POST');
$request->request->add([
    'email' => 'test@example.com',
    'password' => 'password',
    'remember' => 'on' // Checked checkbox sends "on"
]);

echo "Request data: " . json_encode($request->all()) . "\n";
echo "request->filled('remember'): " . ($request->filled('remember') ? 'true' : 'false') . "\n";
echo "request->has('remember'): " . ($request->has('remember') ? 'true' : 'false') . "\n";
echo "request->get('remember'): " . ($request->get('remember') ?? 'NULL') . "\n";

// Test 2: Checkbox unchecked (sends nothing)
echo "\n=== Test 2: Checkbox UNCHECKED ===\n";
$request = new Request();
$request->setMethod('POST');
$request->request->add([
    'email' => 'test@example.com',
    'password' => 'password'
    // No 'remember' parameter when unchecked
]);

echo "Request data: " . json_encode($request->all()) . "\n";
echo "request->filled('remember'): " . ($request->filled('remember') ? 'true' : 'false') . "\n";
echo "request->has('remember'): " . ($request->has('remember') ? 'true' : 'false') . "\n";
echo "request->get('remember'): " . ($request->get('remember') ?? 'NULL') . "\n";

// Test 3: Checkbox with empty value
echo "\n=== Test 3: Checkbox with EMPTY value ===\n";
$request = new Request();
$request->setMethod('POST');
$request->request->add([
    'email' => 'test@example.com',
    'password' => 'password',
    'remember' => '' // Empty value
]);

echo "Request data: " . json_encode($request->all()) . "\n";
echo "request->filled('remember'): " . ($request->filled('remember') ? 'true' : 'false') . "\n";
echo "request->has('remember'): " . ($request->has('remember') ? 'true' : 'false') . "\n";
echo "request->get('remember'): " . ($request->get('remember') ?? 'NULL') . "\n";

echo "\n=== Method Comparison ===\n";
echo "filled() returns true if value exists AND is not empty\n";
echo "has() returns true if value exists (even if empty)\n";