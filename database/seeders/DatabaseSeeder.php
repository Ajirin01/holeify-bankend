<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::create(
            ['name'=> "Custom Task", "description"=> "A custom task", "reward_range"=> "[100, 200]"]
        );
        \App\Models\User::create(
            [
            'name'=> "Olagoke Mubarak",
            'email'=> "mubarakolagoke@gmail.com",
            'password'=> "Ajirin01",
            'phone'=> "07036998003",
            'role'=> "admin"
            ]
        );
        \App\Models\User::factory(20)->create();
        \App\Models\Category::factory(6)->create();
        \App\Models\Task::factory(30)->create();
        \App\Models\TaskType::factory(15)->create();
        \App\Models\EarningWallet::factory(20)->create();
        \App\Models\FundWallet::factory(15)->create();
        \App\Models\PaymentHistory::factory(15)->create();
        \App\Models\Requester::factory(15)->create();
        \App\Models\SubmittedTask::factory(50)->create();
        \App\Models\Worker::factory(15)->create();
    }
}
