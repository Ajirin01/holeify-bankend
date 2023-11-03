<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'photo'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function tasks(){
        return $this->hasMany('App\Models\Task');
    }

    public function submitted_tasks(){
        return $this->hasMany('App\Models\SubmittedTask');
    }
}
