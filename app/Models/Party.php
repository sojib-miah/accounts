<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'party_id');
    }

    public function receiptItems()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
