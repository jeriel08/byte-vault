<?php

namespace App\Exports;

use App\Models\Product; // Make sure your Product model namespace is correct
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;      // <-- Add this
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // <-- Add this
use Maatwebsite\Excel\Concerns\WithStyles;           // <-- Add this
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;      // <-- Add this for styling
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;     // <-- Add this for number formats

class InventoryReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithColumnWidths,      // <-- Implement
    WithColumnFormatting, // <-- Implement
    WithStyles            // <-- Implement
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Product::query();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Header row titles
        return [
            'ID',             // Column A
            'Product Name',   // Column B
            'Stock',          // Column C
            'Unit Price',     // Column D
            'Total Value',    // Column E
        ];
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        // Data for each row
        return [
            $product->productID,
            $product->productName,
            $product->stockQuantity,
            $product->price, // Keep as raw number for formatting
            $product->stockQuantity * $product->price, // Keep as raw number
        ];
    }

    /**
     * Define specific column widths.
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10, // ID column width
            'B' => 45, // Product Name column width
            'C' => 10, // Stock column width
            'D' => 15, // Unit Price column width
            'E' => 15, // Total Value column width
        ];
    }

    /**
     * Define number formatting for columns.
     * @return array
     */
    public function columnFormats(): array
    {
        // Define custom format string for Philippine Peso (₱)
        $pesoFormat = '"₱"#,##0.00'; // Places ₱ symbol, uses comma separator, 2 decimal places

        return [
            'C' => NumberFormat::FORMAT_NUMBER, // Format Stock as plain number (or '#,##0' for thousands separator)
            'D' => $pesoFormat,                 // Format Unit Price using Peso format
            'E' => $pesoFormat,                 // Format Total Value using Peso format
        ];
    }

    /**
     * Apply styles to the worksheet.
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Make the entire first row (headers) bold.
        $sheet->getStyle('1')->getFont()->setBold(true);

        // You can add more complex styling here, e.g.:
        // $sheet->getStyle('C')->getAlignment()->setHorizontal('right'); // Right-align column C
        // $sheet->getStyle('D')->getAlignment()->setHorizontal('right'); // Right-align column D
        // $sheet->getStyle('E')->getAlignment()->setHorizontal('right'); // Right-align column E
    }
}
