<?php

namespace App\Models;

use Database\Factories\AdminPermissionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'status', 'description'])]
class AdminPermission extends Model
{
    /** @use HasFactory<AdminPermissionFactory> */
    use HasFactory;

    /**
     * Automatically generate slug if not provided.
     */
    protected static function booted(): void
    {
        static::saving(function (AdminPermission $permission) {
            if (empty($permission->slug) && ! empty($permission->title)) {
                $permission->slug = Str::slug($permission->title);
            }
        });
    }

    /**
     * Roles having this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            AdminRole::class,
            'admin_role_permissions',
            'admin_permission_id',
            'admin_role_id'
        )->withTimestamps();
    }
}
