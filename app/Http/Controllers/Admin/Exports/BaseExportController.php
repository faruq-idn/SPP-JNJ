<?php

namespace App\Http\Controllers\Admin\Exports;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExportController extends Controller
{
    protected function setHeaderStyle(Worksheet $sheet, $columns = 'I', $hasSubtitle = true)
    {
        // Center and merge cells for title
        $sheet->mergeCells("A1:{$columns}1");
        if ($hasSubtitle) {
            $sheet->mergeCells("A2:{$columns}2");
        }
        
        // Title style
        $sheet->getStyle('A1')->getFont()->setSize(18)->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(12);
        
        // Center alignment
        $sheet->getStyle("A1:{$columns}" . ($hasSubtitle ? '2' : '1'))->getAlignment()->applyFromArray([
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ]);
        
        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(40);
        if ($hasSubtitle) {
            $sheet->getRowDimension(2)->setRowHeight(25);
        }
    }
}
