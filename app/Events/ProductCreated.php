<?php

namespace App\Events;

use App\Models\Product; 
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('products-channel'),
        ];
    }
    
    public function broadcastAs()
    {
        return 'new-product-added';
    }

    // --- TAMBAHKAN FUNGSI INI ---
    public function broadcastWith(): array
    {
        // Debug: Kirim hanya field yang dibutuhkan untuk menghindari error serialisasi.
        // Data di sini harus sesuai dengan e.product.name yang Anda panggil di frontend.
        return [
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'stock' => $this->product->stock,
            ],
            'debug_time' => now()->toDateTimeString(),
        ];
    }
    // ----------------------------
}