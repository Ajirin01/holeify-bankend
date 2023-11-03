<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoneTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id', 'task_id', 'earning', 'paid'
    ];

    public function worker(){
        return $this->belongsTo('App\Models\Worker');
    }
}
