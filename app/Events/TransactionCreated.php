<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        // pastikan PUBLIC channel (bukan private)
        return new Channel('transactions');
    }

    public function broadcastAs()
    {
        // harus SAMA dengan yang kamu bind di JS
        return 'transaction.created';
    }
}
