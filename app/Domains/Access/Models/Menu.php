<?php

namespace App\Domains\Access\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasUuids;

    protected $fillable = [
        'parent_id',
        'label',
        'route',
        'url',
        'icon',
        'group',
        'group_order',
        'scope',
        'required_permissions',
        'sort_order',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
        'required_permissions' => 'array',
        'sort_order' => 'integer',
        'group_order' => 'integer',
    ];

    /**
     * Parent menu item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Child menu items
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Scope: Only active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Root level menus (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Menus visible to a specific user based on permissions and scope
     */
    public function scopeForUser($query, $user)
    {
        // 1. Filter by Scope
        $query->where(function ($q) use ($user) {
            $q->where('scope', 'both');
            
            if ($user->isSuperAdmin()) {
                $q->orWhere('scope', 'platform');
            } else {
                $q->orWhere('scope', 'tenant');
            }
        });

        // 2. Filter by Permissions (handled in PHP usually, but basic check here)
        // Since permissions are JSON, complex filtering is better done in application logic
        // or using a whereJsonContains if supported by DB.
        // For now, we'll return all scoped menus and filter by permission in the Service.
        
        return $query;
    }

    /**
     * Check if menu item is accessible by user
     */
    public function isAccessibleBy($user): bool
    {
        if (empty($this->required_permissions)) {
            return true; // No permission required
        }

        // Check if user has ANY of the required permissions
        foreach ($this->required_permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
