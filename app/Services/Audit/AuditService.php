<?php

namespace App\Services\Audit;

use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Log an audit event.
     *
     * @param  string  $action
     * @param  array  $data
     * @return void
     */
    public function log($action, $data = [])
    {
        Log::info("Audit: {$action}", array_merge([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ], $data));
    }

    /**
     * Log a user action.
     *
     * @param  string  $action
     * @param  mixed  $model
     * @param  array  $changes
     * @return void
     */
    public function logUserAction($action, $model, $changes = [])
    {
        $this->log($action, [
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'changes' => $changes,
        ]);
    }
}