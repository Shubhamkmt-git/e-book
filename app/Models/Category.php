<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /** @var array<string,string> */
    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];
}
