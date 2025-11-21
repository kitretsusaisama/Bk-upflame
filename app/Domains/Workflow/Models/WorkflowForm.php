<?php

namespace App\Domains\Workflow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WorkflowForm extends Model
{
    use HasUuids;

    protected $fillable = [
        'step_id',
        'field_key',
        'field_type',
        'label',
        'placeholder',
        'is_required',
        'validation_rules',
        'options_json',
        'sort_order'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'validation_rules' => 'array',
        'options_json' => 'array',
        'sort_order' => 'integer',
    ];

    public function step()
    {
        return $this->belongsTo(WorkflowStep::class, 'step_id');
    }
}
