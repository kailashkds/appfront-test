<?php

namespace App\Console\Commands;

use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details (used for admin editing via CLI)';

    public function __construct(protected ProductServiceInterface $productService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $id = $this->argument('id');

        $data = [
            'name' => $this->option('name'),
            'description' => $this->option('description'),
            'price' => $this->option('price'),
        ];

        $validator = Validator::make(
            $data, ProductService::PRODUCT_VALIDATION_RULE
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }
            return 1;
        }

        $data = array_filter($data, fn ($value) => !is_null($value));

        $product = $this->productService->find((int) $id);

        if (!$product) {
            $this->error("Product with ID {$id} not found.");
            return 1;
        }

        if (!empty($data)) {
            $this->productService->update(new Request($data), $id);

            $this->info("Product updated successfully.");
            Log::info("Product ID {$product->id} updated by CLI", $data);
        } else {
            $this->info("No changes provided. Product remains unchanged.");
        }

        return 0;
    }
}
