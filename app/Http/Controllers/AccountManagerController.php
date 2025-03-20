<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class AccountManagerController extends BaseController
{
    //
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
        // Fetch all employees except the current admin
        $employees = User::where('employeeID', '!=', auth()->user()->employeeID)->get();
        return view('account-manager', compact('employees'));
    }

    public function update(Request $request, $employeeID)
    {
        $employee = User::findOrFail($employeeID);

        // Validate the request
        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:Active,Inactive',
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        // Update status
        $employee->status = $request->status;

        // Manually handle updated_at and updated_by since timestamps are off
        $employee->updated_at = now();
        $employee->updated_by = auth()->user()->employeeID;

        $employee->save();

        return redirect()->route('account.manager')->with('success', 'Employee updated successfully');
    }
}
