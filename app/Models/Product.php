<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    // 1. FILLABLE (Kolom yang diizinkan untuk diisi secara massal/mass assignable)
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'price',
        'cost_price',
        'stock',
        'unit',
        'created_by',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}