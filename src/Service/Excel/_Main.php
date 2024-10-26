<?php

namespace App\Service\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum as Sum;
/**
 * Description of _Main
 *
 * @author Tomasz Borczynski
 */
abstract class _Main {
    
    protected Spreadsheet $spreadsheet;
    protected $activeWorksheet;
    protected ?int $dataSetSumRow = null;
    protected ?int $firstDataSetRow = null;
    protected ?int $lastDataSetRow = null;
    private ?string $dataSetSumColumn = null;
    private ?string $dataSetColumnToSum = null;
    private ?string $dataSetSumColumnLabel = null;
    private ?string $dataSetSumColumnLabelValue = null;
    protected ?float $sumTime = null;
    
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
        $this->dataSetSumRow=$this->row;
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
    protected function setDataSumColumn(string $column='A')
    {
        $this->dataSetSumColumn = $column;
    }
    protected function setDataSumColumnLabel(string $column='A',string $label='')
    {
        $this->dataSetSumColumnLabel = $column;
        $this->dataSetSumColumnLabelValue = $label;
    }
    protected function setDataColumnToSum(string $column='A')
    {
        $this->dataSetColumnToSum = $column;
    }
    protected function sumDataSetRow():void
    {
        /*
         * CHECK IS THERE SOMETHING TO SUM
         */
        if($this->firstDataSetRow === null || $this->sumTime===null){
            return ;
        }
        self::checkDataSetRowProperties();
        /*
         * SET SUM LABEL
         */
        $this->activeWorksheet->setCellValue($this->dataSetSumColumnLabel.$this->dataSetSumRow,$this->dataSetSumColumnLabelValue);
        /*
         * SET SUM VALUE
         */
        $this->activeWorksheet->setCellValue($this->dataSetSumColumn.$this->dataSetSumRow,$this->sumTime);
    }
    private function checkDataSetRowProperties()
    {
        /*
         * CHECK SUM COLUMN
         */
        if($this->dataSetSumColumn === null){
            Throw New \Exception("SET SUM COLUMN");
        }
        /*
         * CHECK COLUMN TO SUM
         */
        if($this->dataSetColumnToSum === null){
            Throw New \Exception("SET COLUMN TO SUM");
        }
        /*
         * CHECK DATA SET SUM ROW
         */
        if($this->dataSetSumRow === null){
            Throw New \Exception("SET COLUMN ROW TO SUM");
        }
        /*
         * CHECK SUM COLUMN LABEL
         */
        if($this->dataSetSumColumnLabel === null || $this->dataSetSumColumnLabelValue === null){
            Throw New \Exception("SET COLUMN SUM LABEL");
        }
    }
    protected function getTimeInH(int $time=0):float
    {
        if($time === 0){
            return 0;
        }
        $halfAnHour = 0;
        $halfAnHourDiv = 0;
        $hour = 0;
        /*
         * modulo
         */
        $modulo = $time % 30;
        if($modulo>0){
            $halfAnHour = 0.5;
            $time = $time - $modulo;
        }
        if($time === 0){
            return $halfAnHour;
        }
        /*
         * GET HOUR
         */
        $hour =  $time / 60;
        if($hour>0){
            $time = $time - ($hour * 60);
        }
        if($time === 0){
            return $halfAnHour+$hour;
        }
        /*
         * GET HALF AN HOUR
         */
        if(($time / 30)>0){
            return $halfAnHour+$hour+0.5;
        }
        return $halfAnHour+$hour;
    }
}
