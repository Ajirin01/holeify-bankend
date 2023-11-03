<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['worker_id', 'amount', 'done_tasks', 'status'];

    public function worker()
    {
        return $this->belongsTo('App\Models\Worker');
    }
}
