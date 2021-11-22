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
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'data_of_birth' => $this->faker->date(),
        ];
    }
}
