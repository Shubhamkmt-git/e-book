<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroBanner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'title_l2',
        'description',
        'banner_image',
        'primary_button',
        'primary_button_link',
        'secondary_button',
        'secondary_button_link',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the full URL for the banner image.
     */
    public function getBannerImageUrlAttribute(): ?string
    {
        if (! $this->banner_image) {
            return null;
        }

        return Storage::url($this->banner_image);
    }
}
