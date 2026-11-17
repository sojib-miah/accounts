<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'visitor_key',
        'first_visited_at',
    ];

    protected $casts = [
        'first_visited_at' => 'datetime',
    ];
}
