<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'category_id',
    'name',
    'description',
    'photo_ref',
    'original_price',
    'markup',
    'available_for_rent',
])]
class Item extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
