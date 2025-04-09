<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_it_can_create_a_product()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99,
        ]);

        $product->delete();
    }

    public function test_it_can_update_a_product()
    {
        $product = Product::factory()->create();
        $product->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
        ]);

        $product->delete();
    }

    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()->create();
        $product->delete();

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
