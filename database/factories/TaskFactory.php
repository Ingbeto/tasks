<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'priority' => $this->faker->randomElement(['hight', 'middle','low']),
            'state' => $this->faker->randomElement(['progress', 'test','finish']),
            'due_date' => $this->faker->date('Y-m-d'),
            'created_at' =>  $this->faker->date('Y-m-d'),
            'updated_at' => $this->faker->date('Y-m-d'),
        ];
    }
}
