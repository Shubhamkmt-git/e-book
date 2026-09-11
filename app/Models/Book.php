<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'slug',
        'author_name',
        'category_id',
        'price',
        'selling_price',
        'status',
        'is_featured',
        'description',
        'key_highlights',
        'table_of_contents',
        'suggested_for',
        'cover_image',
        'gallery_images',
        'sample_file',
        'ebook_file',
        'pages',
        'language',
        'format',
        'file_size',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /** @var array<string,string> */
    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'suggested_for' => 'array',
        'gallery_images' => 'array',
        'pages' => 'integer',
    ];

    /**
     * Get the category the book belongs to.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the testimonials associated with the book.
     *
     * @return HasMany<Testimonial, $this>
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Get list of highlights as an array.
     *
     * @return list<string>
     */
    public function getHighlightsListAttribute(): array
    {
        if (empty($this->key_highlights)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', (string) $this->key_highlights);
        if (! is_array($lines)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', $lines), fn ($line) => $line !== ''));
    }

    /**
     * Calculate discount percentage between regular and selling price.
     */
    public function getDiscountPercentageAttribute(): int
    {
        $regular = (float) $this->price;
        $selling = (float) $this->selling_price;

        if ($regular > 0 && $regular > $selling) {
            return (int) round((($regular - $selling) / $regular) * 100);
        }

        return 0;
    }

    /**
     * Get cover image URL or placeholder.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        if (str_starts_with($this->cover_image, 'images/')) {
            return asset($this->cover_image);
        }

        return Storage::disk('public')->url($this->cover_image);
    }

    /**
     * Get full URLs for all gallery images.
     *
     * @return list<string>
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        if (empty($this->gallery_images) || ! is_array($this->gallery_images)) {
            return [];
        }

        $urls = [];
        foreach ($this->gallery_images as $image) {
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                $urls[] = $image;
            } elseif (str_starts_with($image, 'images/')) {
                $urls[] = asset($image);
            } else {
                $urls[] = Storage::disk('public')->url($image);
            }
        }

        return $urls;
    }

    /**
     * Get Sample PDF file download / preview URL.
     */
    public function getSampleFileUrlAttribute(): ?string
    {
        if (! $this->sample_file) {
            return null;
        }

        if (str_starts_with($this->sample_file, 'http://') || str_starts_with($this->sample_file, 'https://')) {
            return $this->sample_file;
        }

        return Storage::disk('public')->url($this->sample_file);
    }

    /**
     * Get Full E-Book PDF file download / preview URL.
     */
    public function getEbookFileUrlAttribute(): ?string
    {
        if (! $this->ebook_file) {
            return null;
        }

        if (str_starts_with($this->ebook_file, 'http://') || str_starts_with($this->ebook_file, 'https://')) {
            return $this->ebook_file;
        }

        return Storage::disk('public')->url($this->ebook_file);
    }

    /**
     * Get parsed suggested for audiences with title and icon.
     *
     * @return list<array{title: string, icon: string}>
     */
    public function getSuggestedForListAttribute(): array
    {
        if (empty($this->suggested_for) || ! is_array($this->suggested_for)) {
            return [];
        }

        $defaultIcons = [
            'Beginners & Starters' => 'fa-solid fa-seedling',
            'Students & Academics' => 'fa-solid fa-graduation-cap',
            'Software Engineers & Developers' => 'fa-solid fa-laptop-code',
            'Data Scientists & AI Practitioners' => 'fa-solid fa-brain',
            'Entrepreneurs & Business Leaders' => 'fa-solid fa-briefcase',
            'Designers & Creatives' => 'fa-solid fa-palette',
            'Researchers & Scientists' => 'fa-solid fa-microscope',
            'Self-Learners & Hobbyists' => 'fa-solid fa-book-open-reader',
            'Kids & Young Adults' => 'fa-solid fa-child-reaching',
            'General Readers & Enthusiasts' => 'fa-solid fa-glasses',
        ];

        $result = [];
        foreach ($this->suggested_for as $item) {
            if (is_array($item)) {
                $title = trim((string) ($item['title'] ?? ''));
                $icon = trim((string) ($item['icon'] ?? ($defaultIcons[$title] ?? 'fa-solid fa-user-tag')));
                if ($title !== '') {
                    $result[] = ['title' => $title, 'icon' => $icon ?: 'fa-solid fa-user-tag'];
                }
            } elseif (is_string($item)) {
                if (str_contains($item, ':::')) {
                    [$title, $icon] = explode(':::', $item, 2);
                    $title = trim($title);
                    $icon = trim($icon);
                } else {
                    $title = trim($item);
                    $icon = $defaultIcons[$title] ?? 'fa-solid fa-user-tag';
                }
                if ($title !== '') {
                    $result[] = ['title' => $title, 'icon' => $icon ?: 'fa-solid fa-user-tag'];
                }
            }
        }

        return $result;
    }
}
