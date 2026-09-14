<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CustomerOtp extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer_otps';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'otp_code',
        'name',
        'password_hash',
        'mobile',
        'expires_at',
        'attempts',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * Check if the OTP is currently valid and unexpired.
     */
    public function isValid(string $code): bool
    {
        if ($this->isExpired() || $this->attempts >= 5) {
            return false;
        }

        return hash_equals((string) $this->otp_code, trim($code));
    }

    /**
     * Check if the OTP has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Generate a new 6-digit OTP for the specified registration data.
     */
    public static function generateFor(string $email, ?string $name = null, ?string $password = null, ?string $mobile = null): self
    {
        $normalizedEmail = strtolower(trim($email));

        // Invalidate old OTPs for this email
        self::where('email', $normalizedEmail)->delete();

        // 6-digit numeric OTP
        $otp = sprintf('%06d', random_int(100000, 999999));

        return self::create([
            'email' => $normalizedEmail,
            'otp_code' => $otp,
            'name' => $name ? trim($name) : null,
            'password_hash' => $password ? Hash::make($password) : null,
            'mobile' => $mobile ? trim($mobile) : null,
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
        ]);
    }
}
