<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FundWallet>
 */
class FundWalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'requester_id' => rand(1,30), 
            'requester_name' => "", 
            'fund' => rand(0,5000), 
            'status' => $this->getStatus(rand(0,2))
        ];
    }

    public function getStatus($index){
        if($index == 0){
            return "high";
        }else if($index == 1){
            return "low";
        }else if($index == 2){
            return "moderate";
        }
    }
}
