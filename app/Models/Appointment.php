<?php

namespace App\Models;

use App\Domains\Identity\Models\User;

/**
 * Appointment Model
 * 
 * Handles appointment scheduling, tracking, and management
 * for in-person and teleconsultation appointments.
 */
class Appointment extends BaseModel
{
    protected $fillable = [
        'org_id',
        'patient_id',
        'doctor_id',
        'appointment_number',
        'type',
        'status',
        'scheduled_at',
        'duration_minutes',
        'checked_in_at',
        'started_at',
        'completed_at',
        'reason_for_visit',
        'symptoms',
        'doctor_notes',
        'cancellation_reason',
        'meeting_link',
        'meeting_id',
        'consultation_fee',
        'payment_status',
        'created_by_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'consultation_fee' => 'decimal:2',
        'duration_minutes' => 'integer',
    ];

    protected $attributes = [
        'status' => 'scheduled',
        'duration_minutes' => 15,
        'payment_status' => 'pending',
    ];

    /**
     * Get the organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Get the patient
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the user who created the appointment
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Scope for upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
                    ->whereIn('status', ['scheduled', 'confirmed']);
    }

    /**
     * Scope for appointments by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for teleconsult appointments
     */
    public function scopeTeleconsult($query)
    {
        return $query->where('type', 'teleconsult');
    }

    /**
     * Check if appointment is in the past
     */
    public function isPast(): bool
    {
        return $this->scheduled_at->isPast();
    }

    /**
     * Check if appointment can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['scheduled', 'confirmed']) && !$this->isPast();
    }

    /**
     * Check if appointment is teleconsult
     */
    public function isTeleconsult(): bool
    {
        return $this->type === 'teleconsult';
    }
}
