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

    public function index()
    {
        $auditLogs = AuditLog::with(['employee', 'details'])
            ->orderBy('logID', 'desc')
            ->paginate(10);

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
            // Add other mappings as needed
        ];

        return view('admin.audit.index', compact('auditLogs', 'tableNames'));
    }
}
