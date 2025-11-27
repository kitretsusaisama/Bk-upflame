<?php

namespace App\Models;

use App\Domains\Identity\Models\User;

/**
 * Staff Profile Model
 * 
 * Represents clinical and administrative staff members.
 */
class StaffProfile extends BaseModel
{
    protected $fillable = [
        'user_id',
        'org_id',
        'staff_type',
        'employee_id',
        'department',
        'joining_date',
        'certifications',
        'responsibilities',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'certifications' => 'array',
    ];

    /**
     * Get the user that owns this profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization this staff member belongs to
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Scope to filter by staff type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('staff_type', $type);
    }

    /**
     * Scope to filter by department
     */
    public function scopeInDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }
}
