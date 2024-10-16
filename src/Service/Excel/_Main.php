<?php

namespace App\Service\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Description of _Main
 *
 * @author Tomasz Borczynski
 */
abstract class _Main {
    
    protected Spreadsheet $spreadsheet;
    protected $activeWorksheet;
    
    public function __construct(
        private string $appTmp,
        protected int $row=1
        )
    {
        /*
         * initialize Spreadsheet
         */
        $this->spreadsheet = new Spreadsheet();
        /*
         * SET ACTIVE SHEET
         */
        $this->activeWorksheet = $this->spreadsheet->getActiveSheet();
    }
    protected function setColumnsDimensions(array $dimensions=[]):void
    {
        foreach($dimensions as $column => $dimension){
            $this->spreadsheet->getActiveSheet()->getColumnDimension($column)->setWidth($dimension);
        }
    }
    protected function setTitle(string $title='',array $columnsMerge=['A1','A1']):void
    {
        $this->activeWorksheet->setCellValue('A'.$this->row, $title);
        $this->activeWorksheet->MergeCells($columnsMerge[0].strval($this->row).':'.$columnsMerge[1].strval($this->row));
        $this->row++;
    }
    protected function setDescription(array $descriptions=[]):void
    {
        foreach($descriptions as $column => $description){
            $this->activeWorksheet->setCellValue($column.strval($this->row), $description);
            
        }
        $this->row++;
    }
    public function get()
    {
        $fileName = $this->appTmp.uniqid().'.xlsx';
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($fileName);
        $fileContent = file_get_contents($fileName);
        unlink($fileName);
        return $fileContent;
    }
}
