<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoneTask>
 */
class DoneTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'worker_id' => rand(1, 30), 
            'task_id' => rand(1,30), 
            'earning' => rand(10, 200), 
            'paid' => $this->getPaid(rand(1,2))
        ];
    }

    public function getPaid($index){
        if($index == 1){
            return true;
        }else{
            return false;
        }
    }
}
