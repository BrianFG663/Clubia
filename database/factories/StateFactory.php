<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\State>
 */
class StateFactory extends Factory
{
    public function definition(): array
{
    $names = ['Activo', 'Inactivo'];

    return [
        'name' => $this->faker->unique()->randomElement($names),
        'created_at' => now(),
        'updated_at' => now(),
    ];
}

}
