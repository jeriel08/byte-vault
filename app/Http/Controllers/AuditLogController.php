<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct()
    {
        // Restrict to admins only
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'Admin') {
                abort(403, 'Only admins can access this page.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = AuditLog::with(['employee', 'details'])->orderBy('logID', 'desc');

        // Filter by User ID
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->whereIn('employee_id', $request->user_id);
        }

        // Filter by Date Range
        if ($request->has('date_range') && !empty($request->date_range)) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::parse($dates[0])->startOfDay(),
                    \Carbon\Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }

        // Filter by Action Type
        if ($request->has('action_type') && !empty($request->action_type)) {
            $query->whereIn('actionType', $request->action_type);
        }

        // Filter by Table Name
        if ($request->has('table_name') && !empty($request->table_name)) {
            $query->whereIn('tableName', $request->table_name);
        }

        $auditLogs = $query->paginate(10)->appends($request->query());

        $tableNames = [
            'products' => 'Product',
            'orders' => 'Order',
            'adjustments' => 'Adjustment',
            'stock_out_details' => 'Stock Out Detail',
            'stock_outs' => 'Stock Out',
            'return_to_suppliers' => 'Return to Supplier',
            'suppliers' => 'Supplier',
            'categories' => 'Category',
            'brands' => 'Brand',
            'supplier_orders' => 'Supplier Order',
            'supplier_order_details' => 'Supplier Order Detail',
        ];

        return view('admin.audit.index', compact('auditLogs', 'tableNames'));
    }
}
