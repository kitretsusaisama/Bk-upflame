<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check sessions table directly
echo "Checking sessions table directly...\n";

try {
    $sessions = DB::table('sessions')->get();
    echo "Total sessions: " . $sessions->count() . "\n";
    
    foreach ($sessions as $session) {
        echo "ID: {$session->id}\n";
        echo "User ID: {$session->user_id}\n";
        echo "IP: {$session->ip_address}\n";
        echo "User Agent: " . substr($session->user_agent, 0, 50) . "...\n";
        echo "Payload: " . strlen($session->payload) . " chars\n";
        echo "Last Activity: " . date('Y-m-d H:i:s', $session->last_activity) . "\n";
        echo "---\n";
    }
} catch (Exception $e) {
    echo "Error querying sessions table: " . $e->getMessage() . "\n";
}

// Check if sessions table exists
echo "\nChecking if sessions table exists...\n";
try {
    $exists = DB::select("SHOW TABLES LIKE 'sessions'");
    if (empty($exists)) {
        echo "Sessions table does not exist!\n";
    } else {
        echo "Sessions table exists.\n";
        
        // Check table structure
        $columns = DB::select("DESCRIBE sessions");
        echo "Table structure:\n";
        foreach ($columns as $column) {
            echo "  {$column->Field} ({$column->Type})\n";
        }
    }
} catch (Exception $e) {
    echo "Error checking table: " . $e->getMessage() . "\n";
}