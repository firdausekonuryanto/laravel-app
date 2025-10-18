<?php

namespace App\Events;

use App\Models\Product; // Sesuaikan jika model Anda bukan Product
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

    // Tentukan Channel Publik
    public function broadcastOn(): array
    {
        return [
            new Channel('products-channel'), // Semua user akan mendengarkan channel ini
        ];
    }
    
    // Tentukan Nama Event saat disiarkan
    public function broadcastAs()
    {
        return 'new-product-added';
    }
}