<?php

namespace App\Service\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;
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
    
    protected ?float $rate=null;
    protected ?float $mileage=null;
    
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
    public function setRate(?float $rate=null):void
    {
        if($rate===null){
            return;
        }
        $this->rate=$rate;
    }
    public function setMileage(?float $mileage=null):void
    {
        if($mileage===null){
            return;
        }
        $this->mileage=$mileage;
    }
    protected function setColumnsDimensions(array $dimensions=[]):void
    {
        foreach($dimensions as $column => $dimension){
            $this->spreadsheet->getActiveSheet()->getColumnDimension($column)->setWidth($dimension);
        }
    }
    protected function setTitle(string $title='',string $columnsMergeStarts='A', string $columnMergeEnds='A'):void
    {
        $richText = new RichText();
        $customRichText = $richText->createTextRun($title);
        $customRichText->getFont()->setBold(true);
        $customRichText->getFont()->setItalic(false);
        $customRichText->getFont()->setColor( new Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
        $this->spreadsheet->getActiveSheet()->getCell('A'.$this->row)->setValue($richText);
        $this->activeWorksheet->MergeCells($columnsMergeStarts.strval($this->row).':'.$columnMergeEnds.strval($this->row));
        $this->activeWorksheet->getStyle($columnsMergeStarts.':'.$columnMergeEnds)->getAlignment()->setHorizontal('center');
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
    protected function setSum($time,$distance,$timeCost,$distanceCost)
    {
        /*
         * SET SUM LABEL
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Suma:");
        /*
         * SET TIME SUM VALUE
         */
        $this->activeWorksheet->setCellValue("D".$this->row,$time);
        /*
         * SET DISTANCE SUM VALUE
         */
        $this->activeWorksheet->setCellValue("E".$this->row,$distance);
        /*
         * INCREMENT ROW
         */
        $this->row++;
        /*
         * SET SUM COST LABEL
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Koszt:");
        /*
         * SET TIME SUM COST VALUE
         */
       
        $this->activeWorksheet->setCellValue("D".$this->row,$timeCost);
        /*
         * SET DISTANCE SUM COST VALUE
         */
        
        $this->activeWorksheet->setCellValue("E".$this->row,$distanceCost);
        $this->row++;
        /*
         * SET THE WHOLE COST
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Razem:");
         /*
         * SET TIME AND DISTNACE COST SUM VALUE
         */
        $this->activeWorksheet->setCellValue("D".$this->row,$timeCost+$distanceCost);
        $this->row++;
    }
}
