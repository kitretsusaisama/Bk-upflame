<?php

namespace App\Models;

/**
 * Organization Model
 * 
 * Represents a hospital, clinic, diagnostic center, or pharmacy
 * in the multi-tenant medical platform.
 */
class Organization extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'status',
        'country',
        'state',
        'city',
        'address',
        'postal_code',
        'timezone',
        'phone',
        'email',
        'website',
        'created_by_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'type' => 'clinic',
        'status' => 'active',
        'timezone' => 'UTC',
    ];

    /**
     * Get the settings for this organization
     */
    public function settings()
    {
        return $this->hasOne(OrganizationSettings::class, 'org_id');
    }

    /**
     * Get all users belonging to this organization
     */
    public function users()
    {
        return $this->hasMany(User::class, 'org_id');
    }

    /**
     * Get all patient profiles in this organization
     */
    public function patientProfiles()
    {
        return $this->hasMany(PatientProfile::class, 'org_id');
    }

    /**
     * Get all doctor profiles in this organization
     */
    public function doctorProfiles()
    {
        return $this->hasMany(DoctorProfile::class, 'org_id');
    }

    /**
     * Get all staff profiles in this organization
     */
    public function staffProfiles()
    {
        return $this->hasMany(StaffProfile::class, 'org_id');
    }

    /**
     * Get all appointments in this organization
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'org_id');
    }

    /**
     * Get the user who created this organization
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Scope to filter active organizations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by organization type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if organization is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
