<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'is_featured',
        'status',
        'sort_order',
    ];

    /**
     * Get the books in this category.
     *
     * @return HasMany<Book, $this>
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    /** @var array<string,string> */
    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];
}
