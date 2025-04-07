<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryReportExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::all();
    }

    /**
     * Define the header row.
     * @return array
     */
    public function headings(): array
    {
        // These will be the column titles in the Excel file
        return [
            'ID',
            'Product Name',
            'Stock',
            'Unit Price',
            'Total Value',
        ];
    }

    /**
     * Map data for each row.
     * @param mixed $product // Type-hint based on your Product model
     * @return array
     */
    public function map($product): array
    {
        // This defines what data goes into each cell of a row
        return [
            $product->productID,       // Assuming 'productID' is the column name
            $product->productName,     // Assuming 'productName' is the column name
            $product->stockQuantity,   // Assuming 'stockQuantity' is the column name
            $product->price,           // Assuming 'price' is the column name
            $product->stockQuantity * $product->price, // Calculate total value on the fly
        ];
    }
}
