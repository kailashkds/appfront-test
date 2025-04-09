<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    const ROUTE_LIST = 'admin.product.list';

    public function __construct(protected ProductServiceInterface $productService) {}

    public function index()
    {
        $products = $this->productService->all();

        return view('admin.product.list', compact('products'));
    }

    public function edit($id)
    {
        $product = $this->productService->find($id);

        return view('admin.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ProductService::PRODUCT_VALIDATION_RULE);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->productService->update($request, $id);

        return redirect()->route(self::ROUTE_LIST)
            ->with('success', 'Product updated successfully');
    }

    public function delete($id)
    {
        $this->productService->delete($id);

        return redirect()->route(self::ROUTE_LIST)
            ->with('success', 'Product deleted successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ProductService::PRODUCT_VALIDATION_RULE);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->productService->create($request);

        return redirect()->route(self::ROUTE_LIST)
            ->with('success', 'Product added successfully');
    }
}
