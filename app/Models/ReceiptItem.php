<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    protected $guarded = [];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class);
    }
}
