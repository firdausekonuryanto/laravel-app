<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

// ✅ Ubah nama kelas menjadi singular (TransactionDetail)
class TransactionDetails extends Model
{
    use HasFactory;

    // Default Laravel akan mencari tabel 'transaction_details'.

    // Kolom yang dapat diisi secara massal (Mass Assignable)
    // Berdasarkan skema: transaction_id, product_id, quantity, price, subtotal
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    // ✅ Relasi: TransactionDetail dimiliki oleh Transaction
    /**
     * Get the transaction that owns the detail.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transactions::class);
    }

    // ✅ Relasi: TransactionDetail berhubungan dengan Product
    /**
     * Get the product associated with the transaction detail.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}