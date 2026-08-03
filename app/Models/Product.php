<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiptItems()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function items()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(ReceiptItem::class)
            ->whereHas('receipt', function ($q) {
                $q->where('type', 'Purchase-Order');
            });
    }

    public function salesItems()
    {
        return $this->hasMany(ReceiptItem::class)
            ->whereHas('receipt', function ($q) {
                $q->where('type', 'Sales-Order');
            });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class);
    }
}
