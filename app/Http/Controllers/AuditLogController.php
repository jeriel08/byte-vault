<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $orders = Order::with(['createdBy', 'updatedBy'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('audit.index', compact('orders'));
    }
}