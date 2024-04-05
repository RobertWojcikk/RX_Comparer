<?php
declare(strict_types=1);
ini_set('memory_limit','2048M');

//namespace RX_Comparer;

require_once '/RX_Comparer/api/Model/FileNameGenerator.php';
require_once '/RX_Comparer/api/Config/config.php';
require_once '/RX_Comparer/api/Model/Model.php';
require_once '/RX_Comparer/api/Utils/debug.php';
require_once '/RX_Comparer/api/Utils/memCheck.php';
require_once '/RX_Comparer/api/Model/DownloadXML.php';
require_once '/RX_Comparer/api/Model/XmlToArray.php';

$fileDate = new FileNameGenerator();
$fileDate=$fileDate->getDate();
$xmlFile = new DownloadXML($xmlFilePath, $xmlInputFilePath, $fileDate);
$xmlFile->downloadFile();
printMem();
$arrayFromXml = new XmlToArray(0,null);
$arrayFromXml1 = $arrayFromXml->transformXMLToAssocArr();
dump($arrayFromXml1[0]);
printMem();


// $countIx=0;

// $xml = new XMLReader();
// $xml->open('/RX_Comparer/api/Database/dowloadedXML2024-04-05.xml');


// $array=[];
// while($xml->read() && $xml->name != 'produktLeczniczy')
// {
//   ;
// }

// while($xml->name =="produktLeczniczy"){
//   $element = simplexml_load_string($xml->readOuterXML());
//   $associateArray = json_decode(json_encode($element), true);	
//   $array[$countIx]=$associateArray;
//   $countIx++;
//   $xml->next("produktLeczniczy");
//   unset($element);
//   unset($associateArray);
// }
// dump($array);