<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'amount'
    ];

    public function featured_tasks(){
        return $this->hasMany('App\Models\FeaturedTask');
    }
}
