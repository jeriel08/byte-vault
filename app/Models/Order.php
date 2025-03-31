<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    protected $primaryKey = 'orderID';
    protected $fillable = ['orderID', 'customerID', 'product_id', 'quantity', 'amount', 'payment_status', 'status', 'created_by', 'updated_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'productID');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (Auth::check()) {
                $order->created_by = Auth::id();
            }
        });

        static::updating(function ($order) {
            if (Auth::check()) {
                $order->updated_by = Auth::id();
            }
        });
    }
}