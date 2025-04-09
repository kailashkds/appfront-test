<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class PriceChangeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    const MAIL_SUBJECT = 'Product Price Change Notification';

    public Product $product;
    public float $oldPrice;
    public float $newPrice;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Product $product,
        float $oldPrice,
        float $newPrice
    ) {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject(self::MAIL_SUBJECT)
            ->view('emails.price-change');
    }
}
