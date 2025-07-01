<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'nombre_producto' => $this->faker->word(),
            'cantidad' => $this->faker->numberBetween(1, 10),
            'precio_unitario' => $this->faker->randomFloat(2, 10, 500),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
