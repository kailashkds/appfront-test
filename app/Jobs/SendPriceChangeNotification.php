<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PriceChangeNotification;
use App\Models\Product;

class SendPriceChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Product $product;
    protected float $oldPrice;
    protected float $newPrice;
    protected string $email;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Product $product,
        float $oldPrice,
        float $newPrice,
        string $email
    ) {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $log = [
            'product_id' => $this->product->id ?? null,
            'email' => $this->email,
        ];

        try {
            Mail::to($this->email)->send(
                new PriceChangeNotification(
                    $this->product,
                    $this->oldPrice,
                    $this->newPrice
                )
            );
            Log::info('Price change notification email sent', $log);
        } catch (\Exception $e) {
            Log::error(
                'Failed to send price change notification email',
                array_merge($log, ['error' => $e->getMessage()])
            );
        }
    }
}
