<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseItemsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $period;
    protected $startDate;
    protected $endDate;

    protected $purchases;

    public function __construct($period, $startDate = null, $endDate = null)
    {
        $this->period = $period;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Purchase::with('items');

        if ($this->period === 'day') {
            $query->whereDate('purchase_date', now()->toDateString());
        } elseif ($this->period === 'month') {
            $query->whereMonth('purchase_date', now()->month)
                ->whereYear('purchase_date', now()->year);
        } elseif ($this->period === 'custom' && $this->startDate && $this->endDate) {
            $query->whereBetween('purchase_date', [$this->startDate, $this->endDate]);
        }

        $this->purchases = $query->get();

        // Mapping data agar hasil export rapi
        return $this->purchases->flatMap(function ($purchase) {
            return $purchase->items->map(function ($item) use ($purchase) {
                return [
                    'purchase_date'   => \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y'),
                    'purchase_number' => $purchase->purchase_number,
                    'supplier_id'     => $purchase->supplier->name,
                    'item_id'         => $item->name,
                    'qty'             => $item->pivot->qty ?? 0,
                    'unit'            => $item->unit ?? 0,
                    'discount1'       => $item->pivot->discount1 ?? 0,
                    'discount2'       => $item->pivot->discount2 ?? 0,
                    'discount3'       => $item->pivot->discount3 ?? 0,
                    'ad'              => (string) $item->pivot->ad ?? 0,
                    'price_per_item'  => $item->pivot->price_per_item ?? 0,
                    // 'Total'           => ($item->pivot->quantity ?? 0) * ($item->pivot->price ?? 0),
                ];
            });
        });
    }


    public function headings(): array
    {
        // Judul kolom ditaruh di baris ke-2 karena baris pertama untuk "DATA BARANG"
        return ['Tanggal', 'Nomor Pembelian', 'Nama Supplier', 'Nama Barang', 'Jumlah', 'Satuan', 'Diskon 1', 'Diskon 2', 'Diskon 3', 'Ad', 'Harga Beli'];
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
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Sisipkan 2 baris di atas heading
                $sheet->insertNewRowBefore(1, 2);

                // Judul besar
                $sheet->setCellValue('A1', 'LAPORAN PEMBELIAN');

                // Dapatkan kolom terakhir untuk merge
                $highestColumn = $sheet->getHighestColumn();

                // Merge dan style judul
                $sheet->mergeCells("A1:{$highestColumn}1");
                $sheet->getStyle("A1")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '89D8FC'],
                    ],
                ]);

                // Format periode secara dinamis
                $periodeText = 'Periode: ';
                if ($this->period === 'day') {
                    $periodeText .= now()->translatedFormat('d F Y'); // contoh: 05 April 2025
                } elseif ($this->period === 'month') {
                    $periodeText .= now()->translatedFormat('F Y'); // contoh: April 2025
                } elseif ($this->period === 'custom' && $this->startDate && $this->endDate) {
                    $start = \Carbon\Carbon::parse($this->startDate)->translatedFormat('d F Y');
                    $end = \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y');
                    $periodeText .= "{$start} - {$end}";
                } else {
                    $periodeText .= 'Semua Data';
                }

                // Set dan style periode
                $sheet->setCellValue('A2', $periodeText);
                $sheet->mergeCells("A2:{$highestColumn}2");
                $sheet->getStyle("A2")->applyFromArray([
                    'font' => ['italic' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '89D8FC'],
                    ],
                ]);

                // Set border
                $highestRow = $sheet->getHighestRow();
                $cellRange = 'A2:' . $highestColumn . $highestRow;

                $sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                );

                $highestRow = $sheet->getHighestRow();
                $totalRow = $highestRow + 2;

                $sheet->setCellValue('A' . $totalRow, 'TOTAL PEMBELIAN');
                $sheet->mergeCells("A{$totalRow}:D{$totalRow}"); // Gabungkan kolom A sampai F
                $sheet->getStyle("A{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                $totalQty = $this->purchases->flatMap(function ($purchase) {
                    return $purchase->items->map(function ($item) {
                        return ($item->pivot->qty ?? 0);
                    });
                })->sum();


                $formulaColumn = 'E';
                $sheet->setCellValue("{$formulaColumn}{$totalRow}", $totalQty);

                $sheet->getStyle("{$formulaColumn}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);

                $totalDiscount = $this->purchases->sum(function ($purchase) {
                    return 
                        ($purchase->total_discount1 ?? 0) + 
                        ($purchase->total_discount2 ?? 0) + 
                        ($purchase->total_discount3 ?? 0);
                });

                $totalPurchasePrice = $this->purchases->flatMap(function ($purchase) {
                    return $purchase->items->map(function ($item) {
                        return ($item->pivot->qty ?? 0) * ($item->pivot->price_per_item ?? 0);
                    });
                })->sum();

                $sheet->getStyle("A{$totalRow}:{$highestColumn}{$totalRow}")->applyFromArray([
                    'borders' => [
                        'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                        'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                        'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                        'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM],
                    ],
                ]);

                $formulaColumn = 'K';
                $sheet->setCellValue("{$formulaColumn}{$totalRow}", $totalPurchasePrice - $totalDiscount);

                $sheet->getStyle("{$formulaColumn}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);

                $rupiahFormat = '"Rp." #,##0.00;"Rp. -" #,##0.00';

                $sheet->getStyle('K2:K' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode($rupiahFormat);

                // Format untuk total selisih
                $sheet->getStyle("{$formulaColumn}{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode($rupiahFormat);

                // Auto size tiap kolom
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
