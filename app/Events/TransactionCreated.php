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
        $transaction->load('user');
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        return new Channel('transactions');
    }

    public function broadcastAs()
    {
        return 'transaction.created';
    }
}
