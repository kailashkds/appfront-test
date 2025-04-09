<?php

namespace Database\Factories;

use App\Services\ProductService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'image' => ProductService::DEFAULT_PRODUCT_PLACEHOLDER,
        ];
    }
}
