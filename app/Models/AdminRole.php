<?php

namespace App\Models;

use Database\Factories\AdminRoleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'status', 'description'])]
class AdminRole extends Model
{
    /** @use HasFactory<AdminRoleFactory> */
    use HasFactory;

    /**
     * Automatically generate slug if not provided.
     */
    protected static function booted(): void
    {
        static::saving(function (AdminRole $role) {
            if (empty($role->slug) && ! empty($role->title)) {
                $role->slug = Str::slug($role->title);
            }
        });
    }

    /**
     * Get the count of users having this role.
     */
    public function getAdminUsersCountAttribute(): int
    {
        return AdminUser::where('role', $this->slug)->count();
    }

    /**
     * Permissions belonging to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            AdminPermission::class,
            'admin_role_permissions',
            'admin_role_id',
            'admin_permission_id'
        )->withTimestamps();
    }
}
