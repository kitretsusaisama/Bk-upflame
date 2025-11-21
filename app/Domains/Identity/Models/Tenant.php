<?php

namespace App\Domains\Identity\Models;

use App\Support\BaseModel;
use Illuminate\Support\Str;

class Tenant extends BaseModel
{

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'config_json',
        'status',
        'tier',
        'timezone',
        'locale',
        'parent_tenant_id',
    ];

    protected $casts = [
        'config_json' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            if (empty($tenant->slug)) {
                $tenant->slug = static::generateUniqueSlug($tenant->name ?? Str::uuid()->toString());
            }

            $tenant->tier ??= 'free';
            $tenant->timezone ??= 'UTC';
            $tenant->locale ??= 'en-US';
            $tenant->status ??= 'pending_setup';
        });
    }

    protected static function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = Str::lower(Str::random(8));
        }

        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function domains()
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function configs()
    {
        return $this->hasOne(TenantConfig::class);
    }

    public function modules()
    {
        return $this->hasMany(TenantModule::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    protected static function newFactory()
    {
        return \Database\Factories\TenantFactory::new();
    }
}
