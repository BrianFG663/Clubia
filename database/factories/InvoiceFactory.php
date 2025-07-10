<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Sale;
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
        $payment = Payment::inRandomOrder()->first();

        return [
            'client_id' => Partner::inRandomOrder()->first()->id,
            'institution_id' => Institution::inRandomOrder()->first()->id,
            'sale_id' => Sale::inRandomOrder()->first()->id,
            'tipo_factura' => $this->faker->randomElement(['A', 'B', 'C']),
            'monto_total' => $this->faker->randomFloat(2, 1000, 10000),
            'fecha_factura' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
