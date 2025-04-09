<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\ExchangeRateService;

class ProductController extends Controller
{
    public function __construct(
        protected ProductServiceInterface $productService,
        protected ExchangeRateService $exchangeRateService
    ) {}

    public function index()
    {
        $products = $this->productService->all();
        $exchangeRate = $this->exchangeRateService->getRate('EUR');

        return view('products.list', compact('products', 'exchangeRate'));
    }

    public function show($productId, Request $request)
    {
        $product = Product::findOrFail($productId);
        $exchangeRate = $this->exchangeRateService->getRate('EUR');

        return view('products.show', compact('product', 'exchangeRate'));
    }
}
