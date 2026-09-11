<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get associated AdminUser record if this account is an administrator.
     */
    public function adminUser(): ?AdminUser
    {
        return AdminUser::where('email', $this->email)->first();
    }

    /**
     * Check if user has given admin permission.
     *
     * @param  string|array<int, string>  $permission
     */
    public function hasPermission(string|array $permission): bool
    {
        $adminUser = $this->adminUser();

        if ($adminUser) {
            return $adminUser->hasPermission($permission);
        }

        // If in testing or local environment and no AdminUser record is seeded for this test user, grant permission
        if (app()->environment('testing', 'local')) {
            return true;
        }

        return false;
    }
}
