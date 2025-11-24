<?php

namespace App\Domains\Menu\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Support\Domain\Shared\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasUuids, TenantScoped;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'key',
        'label',
        'icon',
        'route',
        'url',
        'permission',
        'order',
        'parent_id',
        'type',
        'is_enabled',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
        'is_enabled' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menu items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Scope a query to only include enabled menu items.
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope a query to only include items of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}