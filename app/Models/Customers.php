<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'phone', 
        'address',
        'email',
    ];

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'customer_id');
    }
}
