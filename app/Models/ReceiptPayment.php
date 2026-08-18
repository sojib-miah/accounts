<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptPayment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function party()
    {
        return $this->hasOneThrough(
            Party::class,
            Receipt::class,
            'id',
            'id',
            'receipt_id',
            'party_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
