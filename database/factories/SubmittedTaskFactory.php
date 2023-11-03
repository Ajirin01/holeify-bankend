<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubmittedTask>
 */
class SubmittedTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'task_id' => rand(1,20), 
            'worker_id' => rand(1,30), 
            'requester_id' => rand(1,30), 
            'reward' => rand(10, 200), 
            'prove_photo' => "", 
            'status' => $this->getStatus(rand(1,3))
        ];
    }

    public function getStatus($index){
        if($index == 1){
            return "approved";
        }else if($index == 2){
            return "pending";
        }else{
            return "declined";
        }
    }
}
