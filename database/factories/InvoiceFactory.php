<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => User::inRandomOrder()->first()->id,
            'institution_id' => Institution::inRandomOrder()->first()->id,
            'sale_id' => Sale::inRandomOrder()->first()->id,
            'tipo_factura' => $this->faker->randomElement(['A', 'B', 'C']),
            'monto_total' => Sale::inRandomOrder()->first()->total,
            'fecha_factura' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
