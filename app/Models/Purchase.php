<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'customer_id', 'book_identifier', 'book_title', 'amount', 'transaction_id', 'status', 'gateway_response',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the matched Book instance if it exists in the database.
     */
    public function getBookAttribute(): ?Book
    {
        return Book::where('slug', $this->book_identifier)
            ->orWhere('id', $this->book_identifier)
            ->first();
    }
}
