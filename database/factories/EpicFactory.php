<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EpicFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high',
                'critical',
            ]),
            'status' => fake()->randomElement([
                'not_started',
                'in_progress',
                'testing',
                'completed',
            ]),
            'planned_start_date' => $start,
            'planned_end_date' => (clone $start)->modify('+30 days'),
            'progress' => fake()->numberBetween(0, 100),
        ];
    }
}
