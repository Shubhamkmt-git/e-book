<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'app_name',
        'app_short_description',
        'logo_dark',
        'logo_light',
        'favicon',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'contact_address',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Retrieve the singleton settings record or initialize defaults.
     */
    public static function getSettings(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'app_name' => config('app.name', 'E-Book CMS'),
                'app_short_description' => 'A modern and intuitive digital e-book library platform.',
                'meta_title' => config('app.name', 'E-Book CMS').' - Digital Library Platform',
                'meta_description' => 'Discover, read, and explore high quality digital e-books and publications.',
                'meta_keywords' => 'ebooks, digital library, books, pdf, reading',
            ]
        );
    }

    /**
     * Get the logo URL for dark background.
     */
    public function getLogoDarkUrlAttribute(): ?string
    {
        if ($this->logo_dark && Storage::disk('public')->exists($this->logo_dark)) {
            return Storage::disk('public')->url($this->logo_dark);
        }

        return null;
    }

    /**
     * Get the logo URL for light background.
     */
    public function getLogoLightUrlAttribute(): ?string
    {
        if ($this->logo_light && Storage::disk('public')->exists($this->logo_light)) {
            return Storage::disk('public')->url($this->logo_light);
        }

        return null;
    }

    /**
     * Get the favicon URL.
     */
    public function getFaviconUrlAttribute(): ?string
    {
        if ($this->favicon && Storage::disk('public')->exists($this->favicon)) {
            return Storage::disk('public')->url($this->favicon);
        }

        return null;
    }
}
