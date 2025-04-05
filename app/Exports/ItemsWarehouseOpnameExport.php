<?php

namespace App\Exports;

use App\Models\Warehouse;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsWarehouseOpnameExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{

    protected $warehouseId;

    public function __construct($warehouseId)
    {
        $this->warehouseId = $warehouseId;
    }

    public function collection()
    {
        $warehouse = Warehouse::findOrFail($this->warehouseId);

        return $warehouse->item_warehouse()
            ->withPivot('stock', 'price_per_item')
            ->get()
            ->map(function ($item) {
                return [
                    'item_name'       => $item->name,
                    'price_per_item'  => $item->pivot->price_per_item,
                    'unit'            => $item->unit,
                    'original_stock'  => $item->pivot->original_stock,
                    'physical'        => $item->pivot->physical,
                    'stock'           => $item->pivot->stock,
                    'profit'          => (string) $item->pivot->profit,
                    'difference'      =>  $item->pivot->difference != 0.00 ? (float) $item->pivot->difference : (string) $item->pivot->difference   // CEK LAGI PAS TESTING
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama Barang', 'Harga/PCS', 'Satuan', 'Jumlah Stok Awal', 'Jumlah Stok Fisik', 'Jumlah Stok Akhir', 'Keuntungan', 'Selisih'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Styling untuk judul kolom di baris ke-2
            1 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EFEEEE'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        $warehouse = Warehouse::findOrFail($this->warehouseId);
        $warehouseName = $warehouse->name;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($warehouseName) {
                $sheet = $event->sheet->getDelegate();

                $warehouse = Warehouse::findOrFail($this->warehouseId);
                $warehouseName = $warehouse->name;

                // Sisipkan judul besar di baris pertama
                $sheet->insertNewRowBefore(1, 1); // Menyisipkan baris kosong di atas headings
                $sheet->setCellValue('A1', 'STOK OPNAME DI ' . strtoupper($warehouseName));

                // Merge dan style judul besar
                $highestColumn = $sheet->getHighestColumn();
                $sheet->mergeCells("A1:{$highestColumn}1");

                $sheet->getStyle("A1")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '89D8FC'], // Warna latar belakang
                    ],
                ]);

                // Set border
                $highestRow = $sheet->getHighestRow();
                $cellRange = 'A2:' . $highestColumn . $highestRow;

                $sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                );

                $sheet->getStyle('G2:G' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('F2:F' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('E2:F' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('D2:F' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);

                $sheet->getStyle('D2:F' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);

                $highestRow = $sheet->getHighestRow(); // Ambil baris terakhir
                $totalRow = $highestRow + 2; // Baris baru untuk total selisih

                $sheet->setCellValue('A' . $totalRow, 'TOTAL SELISIH');
                $sheet->mergeCells("A{$totalRow}:F{$totalRow}"); // Gabungkan kolom A sampai F
                $sheet->getStyle("A{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                $collection = $warehouse->item_warehouse()
                    ->withPivot('difference')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'difference' => (float) $item->pivot->difference,
                        ];
                    });

                $totalDifference = $collection->sum('difference');

                $formulaColumn = 'H';
                $sheet->setCellValue("{$formulaColumn}{$totalRow}", $totalDifference);

                $sheet->getStyle("{$formulaColumn}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);

                $rupiahFormat = '"Rp." #,##0.00;"Rp. -" #,##0.00';

                $sheet->getStyle('B2:B' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode($rupiahFormat);

                $sheet->getStyle('H2:H' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode($rupiahFormat);

                // Format untuk total selisih
                $sheet->getStyle("{$formulaColumn}{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode($rupiahFormat);

                $sheet->getStyle("A{$totalRow}:{$highestColumn}{$totalRow}")->applyFromArray([
                    'borders' => [
                        'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                        'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                        'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                        'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                    ],
                ]);

                // Auto size tiap kolom
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
