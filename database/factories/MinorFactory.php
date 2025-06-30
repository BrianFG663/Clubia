<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Minor>
 */
class MinorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'dni' => $this->faker->unique()->numberBetween(20000000, 45000000),
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '-10 years'), 
            'relacion' => $this->faker->randomElement(['Padre', 'Madre', 'Abuelo', 'Tia']), 
        ];
    }
}
