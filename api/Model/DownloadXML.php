<?php
declare(strict_types=1);

ini_set('max_execution_time', 340); 
use function PHPUnit\Framework\fileExists;

//namespace RX_Comparer;

require_once '/RX_Comparer/api/Model/FileNameGenerator.php';
require_once '/RX_Comparer/api/Config/config.php';
require_once '/RX_Comparer/api/Model/Model.php';
require_once '/RX_Comparer/api/Utils/memCheck.php';

class DownloadXML {

private $xmlFilePath;
private $xmlInputFilePath;
private $fileDate;

public function __construct(string $xmlFilePath, string $xmlInputFilePath, string $fileDate){
    $this->xmlFilePath = $xmlFilePath;
    $this->xmlInputFilePath = $xmlInputFilePath;
    $this->fileDate = $fileDate;
}
public function ifXmlExist($filePath):bool{
    if(file_exists($filePath)){return true;}
    else{ return false;}
}
// private function deleteXml():void{
// if(self::ifXmlExist($filePath)){

// }

// }

public function downloadFile():void{
    
    $newFilePath = $this->xmlInputFilePath . "dowloadedXML" . $this->fileDate . '.xml';
    
    if($this->ifXmlExist($newFilePath)){return;}
    
    try{
    $xmlFile = file_get_contents($this->xmlFilePath);
    file_put_contents($newFilePath, $xmlFile);
    unset($xmlFile);
    }
    catch (error $e){
    var_dump($e);
    }
}








}