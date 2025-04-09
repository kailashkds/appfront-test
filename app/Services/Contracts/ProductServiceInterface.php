<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;
use App\Models\Product;

interface ProductServiceInterface
{
    public function all();
    public function find($id): Product;
    public function create(Request $request): Product;
    public function update(Request $request, $id): Product;
    public function delete($id): bool;
}
