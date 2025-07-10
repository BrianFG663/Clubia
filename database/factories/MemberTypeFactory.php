<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberType>
 */
class MemberTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution_id' => Institution::inRandomOrder()->first()->id,
            'nombre' => $this->faker->word,
            'arancel' => $this->faker->randomFloat(2, 1000, 5000),
        ];
    }
}
