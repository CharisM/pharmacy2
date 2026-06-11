<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        if (empty($this->image)) return null;
        // Stored file (products/xxx.jpg)
        if (!str_starts_with($this->image, 'http')) {
            return Storage::disk('public')->url($this->image);
        }
        return $this->image;
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
