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

var_dump($arrayFromXml);

//$newArr=[];


$i=0;
foreach($arrayFromXml as $y){

  if(!array_key_exists("opakowanie", $y)){return;}
  

  if(array_key_exists("@attributes", $y["opakowanie"])){
    $newArr[$i]= array_slice($y,0,5);
    $newArr[$i]["GTIN"]=$y["opakowanie"]["@attributes"]["kodGTIN"] ?? "";
    $newArr[$i]["kategoriaDostepnosci"]=$y["opakowanie"]["@attributes"]["kategoriaDostepnosci"] ?? "";
   //dump($newArr[$i]);
    $i++;
    
   } 
   else
  {
    for($j=0; $j<count($y["opakowanie"]);$j++){
      $newArr[$i]= array_slice($y,0,5);
      $newArr[$i]["GTIN"]=$y["opakowanie"][$j]["@attributes"]["kodGTIN"] ??"";
      $newArr[$i]["kategoriaDostepnosci"]=$y["opakowanie"][$j]["@attributes"]["kategoriaDostepnosci"]?? "";
    
      $i++;
    }
      }


}




$time2=time();
$time3 = $time2-$time1;
echo "$time3";
echo "<br></br>";
printMem();


