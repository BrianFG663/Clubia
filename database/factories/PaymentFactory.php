<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use App\Models\User;
use App\Models\PaymentType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'payment_type_id' => PaymentType::inRandomOrder()->value('id'),
            'monto' => $this->faker->randomFloat(2, 100, 1000), 
            'fecha_pago' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
