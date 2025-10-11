<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $fechaNacimiento = $this->faker->dateTimeBetween('-60 years', '-10 years');
        $menor = $fechaNacimiento > now()->subYears(18); 
        $dni = $this->faker->unique()->numerify('########');

        return [
            'nombre' => $this->faker->firstName,
            'apellido' => $this->faker->lastName,
            'dni' => $dni,
            'password' => Hash::make($dni),
            'email' => $this->faker->unique()->safeEmail,
            'state_id' => State::inRandomOrder()->first()->id,
            'fecha_nacimiento' => $fechaNacimiento->format('Y-m-d'),
            'direccion' => $this->faker->streetAddress,
            'ciudad' => $this->faker->city,
            'telefono' => $this->faker->unique()->phoneNumber,
            'email_verified_at' => now(),
            'menor' => $menor,
            'jefe_grupo' => $this->faker->boolean(10), // 10% de probabilidad de ser jefe de grupo
            'responsable_id' => null,
            'remember_token' => Str::random(10),
        ];
    }
}
