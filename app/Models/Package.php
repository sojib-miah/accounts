<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = [];

    protected $casts = [
        'end_date' => 'datetime',
    ];


    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_packages');
    }
}
