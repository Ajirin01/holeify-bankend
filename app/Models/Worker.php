<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'account_number', 'bank_code', 'bank_name'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function done_tasks(){
        return $this->hasMany('App\Models\DoneTask');
    }
}
