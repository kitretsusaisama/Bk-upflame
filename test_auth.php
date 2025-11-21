<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use App\Domains\Identity\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Try to authenticate a user
try {
    $user = User::where('email', 'admin@example.com')->first();
    
    if ($user) {
        echo "User found:\n";
        echo "Email: " . $user->email . "\n";
        echo "ID: " . $user->id . "\n";
        echo "Remember token: " . ($user->remember_token ?? 'NULL') . "\n";
        
        // Test updating the remember token
        $user->remember_token = 'test_token';
        $user->save();
        
        echo "Remember token updated successfully!\n";
    } else {
        echo "User not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}