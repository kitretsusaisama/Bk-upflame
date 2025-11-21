<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Domains\Identity\Models\User;

// Check if users exist
echo "Checking for existing users...\n";
$users = DB::table('users')->get();

if ($users->isEmpty()) {
    echo "No users found in database\n";
} else {
    echo "Found " . $users->count() . " users:\n";
    foreach ($users as $user) {
        echo "- {$user->email} (ID: {$user->id})\n";
    }
}

// Try to authenticate a user
echo "\nTesting authentication...\n";
try {
    $user = User::where('email', 'admin@example.com')->first();
    
    if ($user) {
        echo "User found: {$user->email}\n";
        echo "User ID: {$user->id}\n";
        echo "User status: {$user->status}\n";
        
        // Test password verification
        if (Hash::check('password', $user->password)) {
            echo "Password verification successful!\n";
        } else {
            echo "Password verification failed!\n";
        }
    } else {
        echo "User not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}