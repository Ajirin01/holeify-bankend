<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title'];

    protected $with = ['category'];
    
    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
}
