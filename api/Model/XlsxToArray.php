<?php
declare(strict_types=1);

use function PHPUnit\Framework\fileExists;

//namespace RX_Comparer;

require_once '/RX_Comparer/api/Model/FileNameGenerator.php';
require_once '/RX_Comparer/api/Config/config.php';
require_once '/RX_Comparer/api/Model/Model.php';
require_once '/RX_Comparer/api/Utils/memCheck.php';
require      "/RX_Comparer\\vendor\autoload.php";


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxToArray{
  private $inputFileType;
  private $inputFileName;
  private $sheetname;
  private $arrayFromXlsx;
  private $array2;


  public function __construct($inputFileName, $inputFileType, $sheetname, $array2){
$this->inputFileName=$inputFileName;
$this->inputFileType=$inputFileType;
$this->sheetname=$sheetname;
$this->array2 =$array2;
  }

  private function loadDataFromFile(){
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($this->inputFileType);

  $reader->setLoadSheetsOnly($this->sheetname);
  
  $spreadsheet = $reader->load($this->inputFileName);

  return $spreadsheet;

  }


public function mergeArrays(){
$spreadsheet = $this->loadDataFromFile();

$sheet1 =$spreadsheet->getSheetByName("A1");
$sheet2 = $spreadsheet->getSheetByName("A2");
$sheet3 = $spreadsheet->getSheetByName("A3");

$dataArray = array_merge($sheet1->toArray(),array_slice($sheet2->toArray(),2),array_slice($sheet3->toArray(),2));

$this->array2=array_slice($dataArray,2);
return $this->array2;



//return $arrayFromXlsx;
}

public function generator (){
yield $this->array2;
}

public function create(){
$generator = self::generator();

foreach ($generator as $value){
$arrayFromXlsx[] =$value; 

}
return $arrayFromXlsx;


}




}

