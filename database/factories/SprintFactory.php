<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SprintFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');

        return [
            'name' => 'Sprint ' . fake()->numberBetween(1, 10),
            'goal' => fake()->sentence(),
            'start_date' => $start,
            'end_date' => (clone $start)->modify('+14 days'),
            'status' => fake()->randomElement([
                'planned',
                'active',
                'closed'
            ]),
            'progress' => fake()->numberBetween(0, 100),
        ];
    }
}
