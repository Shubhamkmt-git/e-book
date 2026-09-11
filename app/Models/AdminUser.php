<?php

namespace App\Models;

use Database\Factories\AdminUserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'role', 'status', 'profile_image'])]
#[Hidden(['password', 'remember_token'])]
class AdminUser extends Authenticatable
{
    /** @use HasFactory<AdminUserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the avatar URL or default placeholder with initials.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (! $this->profile_image) {
            return '';
        }

        if (str_starts_with($this->profile_image, 'http://') || str_starts_with($this->profile_image, 'https://') || str_starts_with($this->profile_image, 'data:image/')) {
            return $this->profile_image;
        }

        if (Storage::disk('public')->exists($this->profile_image)) {
            return Storage::disk('public')->url($this->profile_image);
        }

        if (file_exists(public_path($this->profile_image))) {
            return asset($this->profile_image);
        }

        if (file_exists(public_path('storage/'.$this->profile_image))) {
            return asset('storage/'.$this->profile_image);
        }

        return Storage::disk('public')->url($this->profile_image);
    }

    /**
     * Get initials of admin user name.
     */
    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_substr($part, 0, 1);
        }

        return strtoupper($initials ?: 'AD');
    }

    /**
     * Get the associated role definition for this admin user.
     */
    public function adminRole(): ?AdminRole
    {
        return AdminRole::where('slug', $this->role)->first();
    }

    /**
     * Get user role display title.
     */
    public function getRoleTitleAttribute(): string
    {
        $roleModel = $this->adminRole();
        if ($roleModel && ! empty($roleModel->title)) {
            return (string) $roleModel->title;
        }

        if (! empty($this->role)) {
            return ucwords(str_replace(['-', '_'], ' ', (string) $this->role));
        }

        return 'Super Admin';
    }

    /**
     * Check whether admin user has super-admin privileges.
     */
    public function isSuperAdmin(): bool
    {
        return $this->status === 'active' && in_array($this->role, ['super-admin', 'admin'], true);
    }

    /**
     * Check whether this admin user has the given permission slug(s).
     *
     * @param  string|array<int, string>  $permission
     */
    public function hasPermission(string|array $permission): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        $role = $this->adminRole();
        if (! $role || $role->status !== 'active') {
            return false;
        }

        $permissions = is_array($permission) ? $permission : [$permission];

        return $role->permissions()
            ->where('admin_permissions.status', 'active')
            ->whereIn('admin_permissions.slug', $permissions)
            ->exists();
    }
}
