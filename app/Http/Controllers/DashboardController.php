<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with all required data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch data from the Dashboard model
        $totalSales = Dashboard::getTotalSales();
        $totalOrders = Dashboard::getTotalOrders();
        $totalProductsInStock = Dashboard::getTotalProductsInStock();
        $lowStockProducts = Dashboard::getLowStockProducts();
        $dailySales = Dashboard::getDailySales();
        $categoryDistribution = Dashboard::getCategoryDistribution();
        $salesByCategory = Dashboard::getSalesByCategory();

        // Format total sales with peso sign and 2 decimal places
        $formattedTotalSales = '₱' . number_format($totalSales, 2);

        // Prepare data for the Sales Overview chart
        $salesLabels = array_map(function ($item) {
            return $item->date;
        }, $dailySales);

        $salesData = array_map(function ($item) {
            return $item->total_sales;
        }, $dailySales);

        // Prepare data for the Category Distribution chart
        $categoryLabels = array_map(function ($item) {
            return $item->categoryName;
        }, $categoryDistribution);

        $categoryData = array_map(function ($item) {
            return $item->product_count;
        }, $categoryDistribution);

        // Prepare data for the Sales by Category chart
        $salesCategoryLabels = array_map(function ($item) {
            return $item->categoryName;
        }, $salesByCategory);

        $salesCategoryData = array_map(function ($item) {
            return $item->total_sales;
        }, $salesByCategory);

        // Pass data to the view
        return view('dashboard', [
            'totalSales' => $formattedTotalSales,
            'totalOrders' => $totalOrders,
            'totalProductsInStock' => $totalProductsInStock,
            'lowStockProducts' => $lowStockProducts,
            'salesLabels' => json_encode($salesLabels),
            'salesData' => json_encode($salesData),
            'categoryLabels' => json_encode($categoryLabels),
            'categoryData' => json_encode($categoryData),
            'salesCategoryLabels' => json_encode($salesCategoryLabels),
            'salesCategoryData' => json_encode($salesCategoryData),
        ]);
    }
}