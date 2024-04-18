<?php
declare(strict_types=1);
ini_set('memory_limit','2048M');
ini_set('max_execution_time', 240); 

//namespace RX_Comparer;

require_once '/RX_Comparer/api/Model/FileNameGenerator.php';
require_once '/RX_Comparer/api/Config/config.php';
require_once '/RX_Comparer/api/Model/Model.php';
require_once '/RX_Comparer/api/Utils/debug.php';
require_once '/RX_Comparer/api/Utils/memCheck.php';
require_once '/RX_Comparer/api/Model/DownloadXML.php';
require_once '/RX_Comparer/api/Model/XmlToArray.php';
require_once '/RX_Comparer/api/Model/PrepareForDatabase.php';
require_once '/RX_Comparer/api/Model/DownloadXlsx.php';
require_once '/RX_Comparer/api/Model/XlsxToArray.php';

$time1=time();
printMem();
$fileDate = new FileNameGenerator();
$fileDate=$fileDate->getDate();
$xmlFile = new DownloadXML($xmlFilePath, $xmlInputFilePath, $fileDate);
$xmlFile->downloadFile();
unset($xmlFile);
function empty_generator(): Generator
{
    yield from [];
}

$arrayFromXml = new XmlToArray(0,null,"", '/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml', empty_generator(),empty_generator());
//$arrayFromXml->transformXMLToAssocArr('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml');
//$arrr=$arrayFromXml->getGenerator('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml');
//$arr1=$arrayFromXml->create('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml');
//$returnedArr=$arrayFromXml->getGenerator2('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml');
// $x=0;
// foreach( as $val){

// var_dump($val);
// $x++;
// }
//dump($x);
//var_dump($returnedArr);
//$arrayFromXml->create('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml');


//unset($arrayFromXml);
$arr = new PrepareForDatabase(0,[],0);
//$arr->generator($arrFromXml);
//$arr->createArray('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml');
//unset($arr1,$arrayFromXml);
foreach($arr->createArray('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml') as $val){
var_dump($val);
echo "<br></br>";



}


//var_dump($arr->getNewArr());

$time2=time();
$time3 = $time2-$time1;
echo "time: $time3";
echo "<br></br>";
printMem();

exit();
unset($arrFromXml);

$getXlsx = new DownloadXlsx($xlsxFilePath, $dirName, $fileDate);
$getXlsx->downloadFile();
unset($getXlsx);


$ArrayFromXlsx = new XlsxToArray($inputFileName, $inputFileType, $sheetname, []);
 $ArrayFromXlsx->mergeArrays();
 $ArrayFromXlsx->generator();
 $r=$ArrayFromXlsx->create();
//dump($r);

// unset($ArrayFromXlsx);

//  foreach($arr->getNewArr() as $element){

//  }
// $toCompare=[];
//  foreach($arr->getNewArr() as $element){
// if(isset($element["GTIN"])&& strlen($element["GTIN"])>1){
// $toCompare[]=$element["GTIN"];
// }


//  }
 

// dump(array_diff($valueFromXlsx,$toCompare));







