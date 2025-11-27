<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$perms = App\Domains\Access\Models\Permission::all();
echo "Total Permissions: " . $perms->count() . "\n";
foreach($perms->take(10) as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Resource: {$p->resource}\n";
}
