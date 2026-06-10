<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'old_price',
        'rating',
        'image',
        'stock',
        'is_featured',
        'description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return !empty($this->image) ? $this->image : null;
    }

    /**
     * Deduct stock safely, never going below zero.
     * Returns false if stock is insufficient.
     */
    public function decrementStock(int $qty): bool
    {
        if ($this->stock < $qty) return false;
        $this->decrement('stock', $qty);
        return true;
    }
}
