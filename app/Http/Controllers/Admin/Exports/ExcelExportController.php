<?php

namespace App\Http\Controllers\Admin\Exports;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelExportController extends BaseExportController
{
    public function exportPembayaran($pembayaran, $bulan, $tahun)
    {
        $totalPembayaran = $pembayaran->sum('nominal');
        $html = view('admin.laporan.excel.pembayaran', compact('pembayaran', 'bulan', 'tahun', 'totalPembayaran'))->render();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        Cell::setValueBinder(new AdvancedValueBinder());
        
        // Set worksheet title
        $sheet->setTitle('Laporan Pembayaran');

        // Write the HTML to Excel
        $reader = new Html();
        $reader->setSheetIndex(0);
        $spreadsheet = $reader->loadFromString($html, $spreadsheet);
        
        // Set header style
        $this->setHeaderStyle($sheet, 'I');
        
        // Apply styling to the whole sheet
        $sheet->getStyle('A1:I' . ($pembayaran->count() + 4))->applyFromArray([
            'font' => ['size' => 12],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Set column widths
        $this->setPembayaranColumnWidths($sheet);

        // Set default row height
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        
        return $this->downloadExcel($spreadsheet, 'laporan-pembayaran.xlsx');
    }

    public function exportTunggakan($santri, $totalTunggakan)
    {
        $html = view('admin.laporan.excel.tunggakan', compact('santri', 'totalTunggakan'))->render();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        Cell::setValueBinder(new AdvancedValueBinder());

        // Set worksheet title
        $sheet->setTitle('Laporan Tunggakan');

        // Write the HTML to Excel
        $reader = new Html();
        $reader->setSheetIndex(0);
        $spreadsheet = $reader->loadFromString($html, $spreadsheet);
        
        // Set header style
        $this->setHeaderStyle($sheet, 'J', true);
        
        // Apply styling to the whole sheet
        $sheet->getStyle('A1:J' . ($santri->count() + 4))->applyFromArray([
            'font' => ['size' => 12],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set column widths
        $this->setTunggakanColumnWidths($sheet);

        // Set warning cell style
        $sheet->getStyle('A3:J3')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFF3CD'],
            ],
            'font' => ['bold' => true],
        ]);

        // Set row height for warning
        $sheet->getRowDimension(3)->setRowHeight(30);
        
        return $this->downloadExcel($spreadsheet, 'laporan-tunggakan.xlsx');
    }

    private function setPembayaranColumnWidths($sheet)
    {
        $widths = [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 15,  // NISN
            'D' => 30,  // Nama Santri
            'E' => 12,  // Kelas
            'F' => 15,  // Bulan
            'G' => 10,  // Tahun
            'H' => 20,  // Nominal
            'I' => 12,  // Status
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function setTunggakanColumnWidths($sheet)
    {
        $widths = [
            'A' => 5,   // No
            'B' => 15,  // NIS
            'C' => 30,  // Nama Santri
            'D' => 12,  // Kelas
            'E' => 20,  // Kategori
            'F' => 25,  // Wali Santri
            'G' => 15,  // No HP
            'H' => 12,  // Jumlah Bulan
            'I' => 20,  // Total Tunggakan
            'J' => 25,  // Bulan Tunggakan
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function downloadExcel($spreadsheet, $filename)
    {
        // Set margins and print area
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageMargins()
            ->setTop(0.5)
            ->setRight(0.5)
            ->setLeft(0.5)
            ->setBottom(0.5);

        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
