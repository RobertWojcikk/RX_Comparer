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
$time1=time();
//echo "$time1";
//echo "<br></br>";
$fileDate = new FileNameGenerator();
$fileDate=$fileDate->getDate();
$xmlFile = new DownloadXML($xmlFilePath, $xmlInputFilePath, $fileDate);
$xmlFile->downloadFile();
printMem();
$arrayFromXml = new XmlToArray(0,null);
$arrayFromXml = $arrayFromXml->transformXMLToAssocArr();


foreach($arrayFromXml as $y){
dump($y);

}
// var_dump($arrayFromXml);


$time2=time();
$time3 = $time2-$time1;
echo "$time3";
echo "<br></br>";
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