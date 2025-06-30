<?php

namespace Database\Factories;

use App\Models\SubActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fee>
 */
class FeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sub_activity_id' => SubActivity::inRandomOrder()->first()?->id,
            'monto' => $this->faker->randomFloat(2, 1000, 5000), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
