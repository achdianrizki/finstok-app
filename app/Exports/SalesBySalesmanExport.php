<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesBySalesmanExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $salesman_id;

    public function __construct($salesman_id)
    {
        $this->salesman_id = $salesman_id;
    }

    public function collection()
    {
        $query = Sale::with('salesman', 'items', 'buyer');

        if ($this->salesman_id) {
            $query->where('salesman_id', $this->salesman_id);
        }

        $sales = $query->get();

        return $sales->map(function ($sale) {
            return [
                        'sale_date'             => \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y'),
                        'sale_number'           => $sale->sale_number,
                        'buyer_id'              => $sale->buyer->name ?? '',
                        'qty_sold'              => $sale->qty_sold ?? 0,
                        'sub_total'             => 'Rp. ' . number_format($sale->sub_total, 2, ',', '.'),
                        'discount1'             => 'Rp. ' . number_format($sale->discount1_value ?? 0, 2, ',', '.'),
                        'discount2'             => 'Rp. ' . number_format($sale->discount2_value ?? 0, 2, ',', '.'),
                        'discount3'             => 'Rp. ' . number_format($sale->discount3_value ?? 0, 2, ',', '.'),
                        'total_discount'        => 'Rp. ' . number_format($sale->total_discount ?? 0, 2, ',', '.'),
                        'tax'                   => 'Rp. ' . number_format($sale->tax ?? 0, 2, ',', '.'),
                        'total_price'           => 'Rp. ' . number_format($sale->total_price ?? 0, 2, ',', '.'),
                    ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal Penjualan', 'Nomor Penjualan', 'Nama Pelanggan', 'Qty', 'Sub Total', 'Diskon 1', 'Diskon 2', 'Diskon 3', 'Total Diskon', 'Pajak', 'Total Price'];
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
                $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');

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

                $salesText = 'Nama Sales: ';

                if ($this->salesman_id) {
                    $salesman = \App\Models\Salesman::find($this->salesman_id);
                    $salesText .= $salesman ? $salesman->name : '-';
                } else {
                    $salesText .= 'Semua Sales';
                }

                // Set dan style periode
                $sheet->setCellValue('A2', $salesText);
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

                // Auto size tiap kolom
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
