<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryReportExport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function inventoryReport()
    {
        // Fetch all products
        $products = Product::all();

        // Calculate summary stats
        $totalProducts = $products->count();
        $totalValue = $products->sum(function ($product) {
            return $product->stockQuantity * $product->price;
        });
        $lowStockCount = $products->where('stockQuantity', '<', 5)->count();

        // Pass data to the view
        return view('admin.reports.inventory', compact('products', 'totalProducts', 'totalValue', 'lowStockCount'));
    }

    public function downloadInventoryPdf()
    {
        // Fetch the same data as the main report [cite: 1, 2, 3, 4]
        $products = Product::all();
        $totalProducts = $products->count();
        $totalValue = $products->sum(function ($product) {
            return $product->stockQuantity * $product->price;
        });
        $lowStockCount = $products->where('stockQuantity', '<', 5)->count();
        $reportDate = \Carbon\Carbon::now()->format('F j, Y'); // Get the current date for the report

        // Prepare data for the PDF view
        $data = [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'totalValue' => $totalValue,
            'lowStockCount' => $lowStockCount,
            'reportDate' => $reportDate,
        ];

        // Load the dedicated PDF view and pass data
        $pdf = PDF::loadView('admin.reports.inventory_pdf', $data);

        // Option 1: Stream the download (shows in browser first if possible)
        // return $pdf->stream('inventory-report-'.date('Y-m-d').'.pdf');

        // Option 2: Force download
        return $pdf->download('inventory-report-' . date('Y-m-d') . '.pdf');
    }

    public function downloadInventoryExcel()
    {
        // Define a filename for the downloaded file
        $fileName = 'inventory-report-' . date('Y-m-d') . '.xlsx';

        // Trigger the download using the Excel facade and your export class
        return Excel::download(new InventoryReportExport(), $fileName);
    }
}
