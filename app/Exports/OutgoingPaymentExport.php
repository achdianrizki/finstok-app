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

class OutgoingPaymentExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Purchase::with(['outgoingPayments', 'supplier'])->get()->map(function ($purchase) {
            return [
                $purchase->purchase_number ?? '',   
                \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') ?? '',      
                $purchase->supplier->name ?? '',       
                'Rp. ' . number_format($purchase->total_price ?? '', 2, ',', '.'),             
                $purchase->status == 'belum_lunas' ? 'Belum Lunas' : 'Lunas',             
            ];
        });
    }


    public function headings(): array
    {
        return ['Nomor Pembelian', 'Tanggal Pembelian', 'Supplier', 'Jumlah yang Harus Dibayar', 'Status'];
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
                $sheet->setCellValue('A1', 'LAPORAN PELUNASAN PEMBELIAN');

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
