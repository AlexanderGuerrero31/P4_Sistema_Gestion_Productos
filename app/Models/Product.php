<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nombre', 'descripcion', 'category_id', 'precio', 'existencias', 'estado'])]
class Product extends Model
{
    protected function casts(): array
    {
        return ['precio' => 'decimal:2'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}