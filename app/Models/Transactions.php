<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ✅ Ubah nama kelas menjadi singular
class Transactions extends Model
{
    use HasFactory;
    
    // Default Laravel akan mencari tabel 'transactions'.
    // Tidak perlu mendefinisikan $table jika Anda mengikuti konvensi.

    // Kolom yang dapat diisi secara massal (Mass Assignable)
    protected $fillable = [
        'customer_id',
        'user_id',
        'payment_method_id',
        'invoice_number',
        'total_qty',
        'total_price',
        'discount',
        'tax',
        'grand_total',
        'paid_amount',
        'change_amount',
        'status',
        // tambahkan kolom lain yang mungkin
    ];

 // Relasi ke detail transaksi
    public function details()
    {
        // ✅ Perbaikan: Mendefinisikan foreign key secara eksplisit sebagai 'transaction_id'
        return $this->hasMany(TransactionDetails::class, 'transaction_id'); 
    }    
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method_id');
    }
    
   
}