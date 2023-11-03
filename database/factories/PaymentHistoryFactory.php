<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentHistory>
 */
class PaymentHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'worker_id' => rand(1,30), 
            'worker_name' => "", 
            'date_requested' => $this->faker->dateTimeBetween('-30 days', '+30 days'), 
            'date_paid' => $this->faker->dateTimeBetween('-30 days', '+30 days'), 
            'status' => "done", 
            'amount' => rand(200, 5000)
        ];
    }
}
