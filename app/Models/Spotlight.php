<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spotlight extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'book_id',
        'badge_text',
        'custom_title',
        'custom_description',
        'feature_tags',
        'button_text',
        'status',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'feature_tags' => 'array',
    ];

    /**
     * Get the selected book for this spotlight.
     *
     * @return BelongsTo<Book, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Effective title (custom override or book title).
     */
    public function getEffectiveTitleAttribute(): string
    {
        return ! empty($this->custom_title)
            ? (string) $this->custom_title
            : (string) ($this->book?->title ?? 'Featured Publication');
    }

    /**
     * Effective description (custom override or book description).
     */
    public function getEffectiveDescriptionAttribute(): string
    {
        return ! empty($this->custom_description)
            ? (string) $this->custom_description
            : (string) ($this->book?->description ?? '');
    }

    /**
     * Parsed list of feature tags.
     *
     * @return list<string>
     */
    public function getTagsListAttribute(): array
    {
        if (is_array($this->feature_tags) && count($this->feature_tags) > 0) {
            return array_values(array_filter(array_map('trim', $this->feature_tags)));
        }

        return ['Instant Download', 'Lifetime Access'];
    }
}
