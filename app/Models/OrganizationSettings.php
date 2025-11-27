<?php

namespace App\Models;

/**
 * Organization Settings Model
 * 
 * Stores configuration and settings for each organization.
 */
class OrganizationSettings extends BaseModel
{
    protected $fillable = [
        'org_id',
        'business_hours',
        'contact_info',
        'billing_config',
        'features_enabled',
        'logo_url',
        'description',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'contact_info' => 'array',
        'billing_config' => 'array',
        'features_enabled' => 'array',
    ];

    /**
     * Get the organization that owns these settings
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Check if a specific feature is enabled
     */
    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features_enabled ?? []);
    }
}
