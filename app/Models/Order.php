<?php

namespace App\Models;

use App\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['total' => 'decimal:2', 'status' => OrderStatus::class];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
