<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['product_id', 'quantity', 'reserved_quantity', 'min_stock_level', 'last_restocked_at'];

    protected $casts = [
        'last_restocked_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getAvailableQuantity()
    {
        return $this->quantity - $this->reserved_quantity;
    }

    public function isLowStock()
    {
        return $this->getAvailableQuantity() <= $this->min_stock_level;
    }
}
