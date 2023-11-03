<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id', 'requester_name', 'fund', 'status'
    ];

    public function requester(){
        return $this->belongsTo('App\Models\Requester');
    }
}
