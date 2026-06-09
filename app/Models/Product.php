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
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) return null;
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return asset('storage/' . ltrim($this->image, '/'));
    }
}
