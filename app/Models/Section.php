<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description'])]
class Section extends Model
{
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
