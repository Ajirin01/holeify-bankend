<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EarningWallet>
 */
class EarningWalletFactory extends Factory
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
            'earning' => rand(0,5000), 
            'status' => $this->getStatus(0,2)
        ];
    }

    public function getStatus($index){
        if($index == 0){
            return "earining";
        }else if($index == 1){
            return "payout requested";
        }else if($index == 2){
            return "request approved";
        }
    }
}
