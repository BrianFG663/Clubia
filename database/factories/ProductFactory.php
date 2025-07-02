<?php

namespace Database\Factories;

use App\Models\Category;
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
            'category_id' => Category::inRandomOrder()->first()?->id,
            'descripcion' => $this->faker->sentence(),
            'nombre' => $this->faker->unique()->word(),
            'precio' => $this->faker->randomFloat(2, 10, 500), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
