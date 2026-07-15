<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),

            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high',
                'critical',
            ]),

            'github_link' => fake()->url(),

            'due_date' => fake()->dateTimeBetween('today', '+30 days'),
        ];
    }
}
