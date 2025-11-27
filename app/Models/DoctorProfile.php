<?php

namespace App\Models;

use App\Domains\Identity\Models\User;

/**
 * Doctor Profile Model
 * 
 * Stores professional information for medical practitioners.
 */
class DoctorProfile extends BaseModel
{
    protected $fillable = [
        'user_id',
        'org_id',
        'specialization',
        'license_no',
        'license_expiry',
        'years_of_experience',
        'qualifications',
        'consultation_type',
        'consultation_fee',
        'avg_consultation_time',
        'availability_json',
        'accepting_new_patients',
        'bio',
        'profile_photo_url',
        'languages_spoken',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'consultation_fee' => 'decimal:2',
        'availability_json' => 'array',
        'accepting_new_patients' => 'boolean',
        'languages_spoken' => 'array',
    ];

    protected $attributes = [
        'consultation_type' => 'both',
        'avg_consultation_time' => 15',
        'accepting_new_patients' => true,
    ];

    /**
     * Get the user that owns this profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization this doctor belongs to
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Get all appointments for this doctor
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'user_id');
    }

    /**
     * Check if the doctor is accepting new patients
     */
    public function isAcceptingPatients(): bool
    {
        return $this->accepting_new_patients;
    }

    /**
     * Check if the doctor offers teleconsultation
     */
    public function offersTeleconsult(): bool
    {
        return in_array($this->consultation_type, ['online', 'both']);
    }

    /**
     * Check if the doctor offers in-person consultation
     */
    public function offersInPerson(): bool
    {
        return in_array($this->consultation_type, ['in_person', 'both']);
    }

    /**
     * Scope to filter doctors by specialization
     */
    public function scopeBySpecialization($query, string $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    /**
     * Scope to filter doctors accepting new patients
     */
    public function scopeAcceptingNewPatients($query)
    {
        return $query->where('accepting_new_patients', true);
    }
}
