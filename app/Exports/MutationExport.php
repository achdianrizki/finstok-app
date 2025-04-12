<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class MutationExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return \DB::table('mutations')
            ->join('warehouses as source', 'mutations.from_warehouse_id', '=', 'source.id')
            ->join('warehouses as destination', 'mutations.to_warehouse_id', '=', 'destination.id')
            ->select(
                'mutations.id',
                'mutations.mutated_at as mutation_date',
                'source.name as source_warehouse',
                'destination.name as destination_warehouse',
                'mutations.qty as total_items'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Mutasi',
            'Tanggal Mutasi',
            'Gudang Asal',
            'Gudang Tujuan',
            'Total Barang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EFEEEE'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul besar di atas header
                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'LAPORAN MUTASI BARANG ANTAR GUDANG');

                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // Merge & styling judul besar
                $sheet->mergeCells("A1:{$highestColumn}1");

                $sheet->getStyle("A1")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '89D8FC'],
                    ],
                ]);

                // Border untuk seluruh isi data
                $cellRange = 'A2:' . $highestColumn . $highestRow;

                $sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                );

                // Wrap text dan auto size kolom
                $sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
