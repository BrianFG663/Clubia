<?php

namespace Database\Factories;

use App\Models\Condition;
use Faker\Provider\bg_BG\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
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
           'telefono'=>$this->faker->unique()->PhoneNumber(),
           'direccion'=>$this->faker->streetAddress(),
           'cuit' => $this->faker->numerify('20#########'),
           'condicion_id' => Condition::inRandomOrder()->first()?->id,
           
        ];
    }
}
