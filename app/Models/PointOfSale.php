<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orderline;

class PointOfSale extends Model
{
    protected $table = 'orders'; // Assuming this maps to the orders table
    protected $fillable = ['status', 'total', 'employeeID']; // Adjust fields as needed

    public function orderLines()
    {
        return $this->hasMany(Orderline::class, 'orderID', 'orderID');
    }
}
