<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProductService implements ProductServiceInterface
{
    const DEFAULT_EMAIL = 'admin@example.com';
    const DEFAULT_PRODUCT_PLACEHOLDER = 'product-placeholder.jpg';
    const PRODUCT_VALIDATION_RULE = [
        'name' => 'required|min:3',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
    ];

    public function all()
    {
        return Product::all();
    }

    public function find($id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(Request $request): Product
    {
        $product = new Product($request->only(['name', 'description', 'price']));

        if ($request->hasFile('image')) {
            $filename = $this->storeImage($request);
            $product->image = 'uploads/' . $filename;
        } else {
            $product->image = self::DEFAULT_PRODUCT_PLACEHOLDER;
        }

        $product->save();

        Log::info('Product created', [
            'admin_id' => auth()->id(),
            'product_id' => $product->id,
            'product_name' => $product->name,
        ]);

        return $product;
    }

    public function update(Request $request, $id): Product
    {
        $product = Product::findOrFail($id);
        $oldPrice = $product->price;
        $oldImage = $product->image;

        $product->fill($request->only(['name', 'description', 'price']));

        if ($request->hasFile('image')) {
            $this->deleteOldImage($oldImage);
            $filename = $this->storeImage($request);
            $product->image = 'uploads/' . $filename;
        }

        $product->save();

        Log::info('Product updated', [
            'admin_id' => auth()->id(),
            'product_id' => $product->id,
            'old_price' => $oldPrice,
            'new_price' => $product->price,
        ]);

        if ($oldPrice != $product->price) {
            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    env('PRICE_NOTIFICATION_EMAIL', self::DEFAULT_EMAIL)
                );
            } catch (\Exception $e) {
                Log::error('Failed to dispatch price change notification: ', [
                    'admin_id' => auth()->id(),
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $product;
    }

    public function delete($id): bool
    {
        $product = Product::findOrFail($id);

        Log::info('Product deleted', [
            'admin_id' => auth()->id(),
            'product_id' => $product->id,
            'product_name' => $product->name,
        ]);

        return $product->delete();
    }

    protected function storeImage(Request $request): string
    {
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        return $filename;
    }

    protected function deleteOldImage(?string $imagePath): void
    {
        if ($imagePath
            && $imagePath !== self::DEFAULT_PRODUCT_PLACEHOLDER
            && file_exists(public_path($imagePath))
        ) {
            @unlink(public_path($imagePath));
        }
    }
}
