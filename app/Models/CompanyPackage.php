<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPackage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'expire_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
