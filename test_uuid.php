<?php

require_once 'vendor/autoload.php';

use App\Domains\Access\Models\Permission;

// Create a new permission instance
$permission = new Permission();
$permission->name = 'test';
$permission->resource = 'test';
$permission->action = 'test';
$permission->description = 'test';

echo "UUID Column: " . $permission->getUuidColumn() . "\n";
echo "ID before save: " . ($permission->id ?? 'null') . "\n";

// Try to save
try {
    $permission->save();
    echo "ID after save: " . ($permission->id ?? 'null') . "\n";
    echo "Saved successfully\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}