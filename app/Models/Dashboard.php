<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dashboard extends Model
{
    // No table needed since this is an aggregator model
    protected $table = null;

    /**
     * Get the total sales (sum of totalCost from supplier_orders).
     *
     * @return float
     */
    public static function getTotalSales()
    {
        return DB::table('supplier_orders')
            ->sum('totalCost');
    }

    /**
     * Get the total number of orders (count of supplier_orders).
     *
     * @return int
     */
    public static function getTotalOrders()
    {
        return DB::table('supplier_orders')
            ->count();
    }

    /**
     * Get the total products in stock (sum of stockQuantity from products).
     *
     * @return int
     */
    public static function getTotalProductsInStock()
    {
        return DB::table('products')
            ->sum('stockQuantity');
    }

    /**
     * Get the count of low stock products (stockQuantity <= 10).
     *
     * @return int
     */
    public static function getLowStockProducts()
    {
        return DB::table('products')
            ->where('stockQuantity', '<=', 10)
            ->count();
    }

    /**
     * Get total sales per day from supplier_orders.
     *
     * @return array
     */
    public static function getDailySales()
    {
        return DB::table('supplier_orders')
            ->select(DB::raw('DATE(orderDate) as date'), DB::raw('SUM(totalCost) as total_sales'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * Get the distribution of products per category.
     *
     * @return array
     */
    public static function getCategoryDistribution()
    {
        return DB::table('products')
            ->join('categories', 'products.categoryID', '=', 'categories.categoryID')
            ->select('categories.categoryName', DB::raw('COUNT(products.productID) as product_count'))
            ->groupBy('categories.categoryName')
            ->get()
            ->toArray();
    }

    /**
     * Get total sales (cost) per category from supplier_order_details.
     *
     * @return array
     */
    public static function getSalesByCategory()
    {
        return DB::table('supplier_order_details')
            ->join('products', 'supplier_order_details.productID', '=', 'products.productID')
            ->join('categories', 'products.categoryID', '=', 'categories.categoryID')
            ->select(
                'categories.categoryName',
                DB::raw('SUM(supplier_order_details.unitCost * supplier_order_details.quantity) as total_sales')
            )
            ->groupBy('categories.categoryName')
            ->get()
            ->toArray();
    }
}