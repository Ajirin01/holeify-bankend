<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCreateTempData extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_data',
        'transaction_id'
    ];
}
