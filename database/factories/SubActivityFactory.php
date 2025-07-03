<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubActivity>
 */
class SubActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'nombre' => $this->faker->unique()->words(2, true), 
            'descripcion' => $this->faker->sentence(),         
            'activity_id' => Activity::inRandomOrder()->first()?->id,
            'monto' => $this->faker->randomFloat(2, 1000, 5000), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
