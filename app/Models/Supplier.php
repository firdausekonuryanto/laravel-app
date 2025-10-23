<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'contact', 
        'address',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }
}
