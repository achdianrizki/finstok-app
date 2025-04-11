<?php

namespace App\Exports;

use App\Models\ReturnSale;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReturnSaleItemsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return ReturnSale::with(['sale', 'buyer', 'items'])->get()->flatMap(function ($returnSale) {
            return $returnSale->items->map(function ($item) use ($returnSale) {
                return [
                    'return_date'     => \Carbon\Carbon::parse($returnSale->return_date)->format('d-m-Y'),
                    'sale_id'         => $returnSale->sale->sale_number,
                    'buyer_id'        => $returnSale->buyer->name,
                    'item_id'         => $item->name,
                    'qty'             => $item->pivot->qty,
                    'unit'            => $item->unit,
                    'reason'          => $returnSale->reason ?? '-',
                    'price_per_item'  => 'Rp. ' . number_format($item->pivot->price_per_item, 2, ',', '.') ?? '-',
                ];
            });
        });
    }


    public function headings(): array
    {
        return ['Tanggal', 'Nomor Penjualan', 'Nama Pelanggan', 'Nama Barang', 'Jumlah Retur', 'Satuan', 'Alasan', 'Harga Beli'];
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

                // Sisipkan judul besar di baris pertama
                $sheet->insertNewRowBefore(1, 1); // Menyisipkan baris kosong di atas headings
                $sheet->setCellValue('A1', 'LAPORAN RETUR PENJUALAN');

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

                $sheet->getStyle('F2:F' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);

                $sheet->getStyle('D2:F' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);

                // Auto size tiap kolom
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}