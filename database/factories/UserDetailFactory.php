<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'telephone' => "+237678370695",
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'data_of_birth' => $this->faker->date(),
        ];
    }
}
