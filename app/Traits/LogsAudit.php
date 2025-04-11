<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsAudit
{
    protected function logAudit($model, $actionType, $columnName = null, $oldValue = null, $newValue = null)
    {
        AuditLog::create([
            'tableName' => $model->getTable(),
            'recordID' => $model->getKey(),
            'actionType' => $actionType,
            'columnName' => $columnName,
            'oldValue' => $oldValue,
            'newValue' => $newValue,
            'employeeID' => Auth::id(), // Assumes authenticated user
        ]);
    }
}
