<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
            'state_id' => $this->faker->randomElement([1, 2]), 
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '2005-01-01'),
            'direccion' => $this->faker->streetAddress(),
            'ciudad' => $this->faker->city(),
            'telefono' => $this->faker->unique()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
