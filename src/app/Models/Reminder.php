<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','remind_at','event_at','user_id'];

    protected $casts = [
        'remind_at' => 'timestamp',
        'event_at' => 'timestamp'
    ];
}
