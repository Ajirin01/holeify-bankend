<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'reward_range'
    ];

    protected $cast = [
        'reward_range' => 'array'
    ];

    public function tasks(){
        return $this->hasMany('App\Models\Task');
    }

    public function task_types(){
        return $this->hasMany('App\Models\TaskType');
    }
}
