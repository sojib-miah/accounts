<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptPayment extends Model
{
    protected $guarded = [];

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
            'id',        // Foreign key on receipts
            'id',        // Foreign key on parties
            'receipt_id', // Local key on receipt_payments
            'party_id'   // Local key on receipts
        );
    }
}
