<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedTask extends Model
{
    use HasFactory;

    protected $with = ['task'];

    protected $fillable = [
        'task_id', 'worker_id', 'requester_id', 'reward', 'prove_photo', 'status'
    ];

    public function task(){
        return $this->belongsTo('App\Models\Task');
    }

    public function worker(){
        return $this->belongsTo('App\Models\Worker');
    }

    public function requester(){
        return $this->belongsTo('App\Models\Requester');
    }
}
