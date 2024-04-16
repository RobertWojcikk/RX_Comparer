<?php
declare(strict_types=1);

use function PHPUnit\Framework\fileExists;

//namespace RX_Comparer;

require_once '/RX_Comparer/api/Model/FileNameGenerator.php';
require_once '/RX_Comparer/api/Config/config.php';
require_once '/RX_Comparer/api/Model/Model.php';
require_once '/RX_Comparer/api/Utils/memCheck.php';


class DownloadXlsx{
  private $xlsxFilePath;
  private $dirName;
  private $fileDate;
  private $xlsxDownloadPath;
  
  public function __construct(string $xlsxFilePath, string $dirName, string $fileDate){
    $this->xlsxFilePath = $xlsxFilePath;
    $this->dirName = $dirName;
    $this->fileDate = $fileDate;}

  public function downloadFile(){

    $xlsxFile = file_get_contents($this->xlsxFilePath);
   
    $domDocument = new DOMDocument();
    libxml_use_internal_errors(true);
    $domDocument->loadHtml($xlsxFile);
    libxml_clear_errors();
  
    foreach ($domDocument->getElementsByTagName('a') as $node) {
   
      if(str_contains($node->nodeValue, "Zalacznik") && str_contains($node->nodeValue, "xlsx")){
        $xlsxDownloadPath =  "https://www.gov.pl" . $node->getAttribute("href");
         }
  }
  
  $xlsxFile = file_get_contents($xlsxDownloadPath);
  
  if (!file_exists($this->dirName)) {
      mkdir($this->dirName, 0777, true);
  }
  try{
  
      $newFilePath = '/RX_Comparer/api//'. $this->dirName.'/'. "downloadedXLSX" . $this->fileDate.'.xlsx';
      file_put_contents($newFilePath, $xlsxFile);
   }
  catch (error $e){
      var_dump($e);
  }
  
  }



  }
  
  
    
  
  
  