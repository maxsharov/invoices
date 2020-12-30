<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;

class Invoice extends Mailable
{
    use Queueable, SerializesModels;
    
    public $amount;
    public $name;
    public $id;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->amount = $item->amount;
        $this->name = $item->name;
        $this->id = $item->id;
        $this->url = "http://invoices.test/api/item/pay/" . $item->id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('invoice.details');
    }
}
