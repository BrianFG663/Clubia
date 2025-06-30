<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleDetail>
 */
class SaleDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cantidad = $this->faker->numberBetween(1, 10);
        $product = Product::inRandomOrder()->first();

        return [
            'sale_id' => Sale::inRandomOrder()->first()->id,
            'product_id' => $product->id,
            'cantidad' => $cantidad,
            'subtotal' => $cantidad * $product->precio,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
