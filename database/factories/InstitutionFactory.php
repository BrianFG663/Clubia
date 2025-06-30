<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Institution>
 */
class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'=> $this->faker->company(),
            'telefono'=>$this->faker->unique()->phoneNumber(),
            'ciudad'=>$this->faker->city(),
            'direccion'=>$this->faker->unique()->streetAddress(),
        ];
    }
}
