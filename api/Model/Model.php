<?php
declare(strict_types=1);
ini_set('memory_limit','512M');

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
//echo "$time1";
//echo "<br></br>";
$fileDate = new FileNameGenerator();
$fileDate=$fileDate->getDate();
$xmlFile = new DownloadXML($xmlFilePath, $xmlInputFilePath, $fileDate);
$xmlFile->downloadFile();
unset($xmlFile);
printMem();
$arrayFromXml = new XmlToArray(0,null,"");
$arrFromXml= $arrayFromXml->transformXMLToAssocArr();
unset($arrayFromXml);
$arr = new PrepareForDatabase(0,[],"");
$arr->createArray($arrFromXml);
unset($arrFromXml);
//dump($arr->getNewArr());
$getXlsx = new DownloadXlsx($xlsxFilePath, $dirName, $fileDate);
$getXlsx->downloadFile();

unset($arrayFromXml,$arrFromXml,$xmlFile);



$ArrayFromXlsx = new XlsxToArray($inputFileName, $inputFileType, $sheetname);
 $valueFromXlsx = $ArrayFromXlsx->mergeArrays();

 dump($valueFromXlsx);
//var_dump($arr->getNewArr());

$time2=time();
$time3 = $time2-$time1;
echo "time: $time3";
echo "<br></br>";
printMem();


