<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domains\Access\Models\Permission;

class TestUuidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:uuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test UUID generation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing UUID generation...');
        
        // Create a new permission instance
        $permission = new Permission();
        $permission->name = 'test';
        $permission->resource = 'test';
        $permission->action = 'test';
        $permission->description = 'test';
        
        $this->info('UUID Column: ' . $permission->getUuidColumn());
        $this->info('ID before save: ' . var_export($permission->id, true));
        
        // Try to save
        try {
            $permission->save();
            $this->info('ID after save: ' . var_export($permission->id, true));
            $this->info('Saved successfully');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}