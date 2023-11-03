<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->words(rand(10, 100), true),
            'status' => $this->faker->boolean(), 
            'category_id' => rand(1, 6), 
            'requester_id' => rand(1, 10), 
            'total_need' => rand(10, 200000),
            'total_done' => rand(0, 200000), 
            'reward' => rand(10, 200),
            'link' => $this->faker->unique()->url,
        ];
    }
}
