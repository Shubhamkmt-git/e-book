<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bg_image',
        'label',
        'title',
        'subtitle',
        'status',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the full URL for the background image.
     */
    public function getBgImageUrlAttribute(): ?string
    {
        return $this->bg_image ? Storage::disk('public')->url($this->bg_image) : null;
    }

    /**
     * Available statuses.
     *
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }

    /**
     * Scope a query to only active CTAs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
