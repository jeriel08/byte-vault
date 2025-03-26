<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    //
    protected $primaryKey = 'adjustmentID';
    protected $fillable = ['adjustmentDate', 'adjustmentReason', 'created_by', 'updated_by'];

    public function stockOut()
    {
        return $this->morphOne(StockOut::class, 'reference', 'referenceTable', 'referenceID');
    }
}
