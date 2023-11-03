<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description',
        'status', 'category_id', 
        'requester_id', 'total_need',
        'total_done', 'reward', 'link'
    ];

    public function requester(){
        return $this->belongsTo('App\Models\Requester');
    }

}
