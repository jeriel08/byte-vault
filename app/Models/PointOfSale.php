<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PointOfSale extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'orderID';
    protected $fillable = [
        'customerID',
        'total_items',
        'payment_status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'amount_received',
        'change',
        'total',
    ];

    public function orderLines()
    {
        return $this->hasMany(Orderline::class, 'orderID', 'orderID');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }

    // Fetch active categories
    public static function getActiveCategories()
    {
        return DB::table('categories')
            ->where('categoryStatus', 'Active')
            ->get();
    }

    // Fetch active brands
    public static function getActiveBrands()
    {
        return DB::table('brands')
            ->where('brandStatus', 'Active')
            ->get();
    }
}