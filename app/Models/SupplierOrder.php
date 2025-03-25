<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    //
    protected $primaryKey = 'supplierOrderID';
    protected $fillable = [
        'supplierID',
        'orderDate',
        'expectedDeliveryDate',
        'status',
        'totalCost',
        'created_by',
        'updated_by',
    ];

    // Cast dates to Carbon instances
    protected $casts = [
        'orderDate' => 'date',
        'expectedDeliveryDate' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplierID', 'supplierID');
    }

    public function details()
    {
        return $this->hasMany(SupplierOrderDetail::class, 'supplierOrderID', 'supplierOrderID');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
