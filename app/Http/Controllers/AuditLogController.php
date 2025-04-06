<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
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
        $orders = Order::with(['createdBy', 'updatedBy'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.audit.index', compact('orders'));
    }
}
