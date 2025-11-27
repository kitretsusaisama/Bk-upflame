<?php

namespace App\Models;

use App\Domains\Identity\Models\User;

/**
 * Patient Profile Model
 * 
 * Stores medical information and history for patients.
 */
class PatientProfile extends BaseModel
{
    protected $fillable = [
        'user_id',
        'org_id',
        'dob',
        'gender',
        'blood_group',
        'height',
        'weight',
        'chronic_conditions',
        'allergies',
        'medical_notes',
        'medications',
        'emergency_contact',
        'insurance_provider',
        'insurance_policy_number',
    ];

    protected $casts = [
        'dob' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'chronic_conditions' => 'array',
        'allergies' => 'array',
        'medications' => 'array',
        'emergency_contact' => 'array',
    ];

    /**
     * Get the user that owns this profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization this patient belongs to
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Get all appointments for this patient
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'user_id');
    }

    /**
     * Calculate patient's current age
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    /**
     * Calculate BMI (Body Mass Index)
     */
    public function getBmiAttribute(): ?float
    {
        if (!$this->height || !$this->weight) {
            return null;
        }
        
        $heightInMeters = $this->height / 100;
        return round($this->weight / ($heightInMeters * $heightInMeters), 2);
    }
}
