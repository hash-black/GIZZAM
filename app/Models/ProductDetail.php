<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    protected $fillable = [
        'product_id',
        'brand',
        'warranty',
        'description'
    ];

    // One-to-One
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}