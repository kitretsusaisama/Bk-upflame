<?php

namespace App\Domains\Workflow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\Identity\Models\User;

class WorkflowStep extends Model
{
    use HasUuids;

    protected $fillable = [
        'workflow_id',
        'step_key',
        'title',
        'description',
        'step_type',
        'config_json',
        'data_json',
        'status',
        'assigned_to',
        'attempted_at',
        'completed_at'
    ];

    protected $casts = [
        'config_json' => 'array',
        'data_json' => 'array',
        'attempted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function forms()
    {
        return $this->hasMany(WorkflowForm::class, 'step_id');
    }
}
