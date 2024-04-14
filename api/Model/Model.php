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
//var_dump($arr->getNewArr());
//$arr2= $arr->substance($arr1);
//var_dump($arr->);
//var_dump($arrFromXml);
// foreach($arr->getNewArr() as $el){
// dump($el);
// echo "<br></br>";

//  }
// dump(count($arr->getNewArr()));
// for($i=0;$i<36821; $i++){
//   if($arr->getNewArr()[$i]["ilość składników"]>9){
//     dump($arr->getNewArr()[$i]);
//   }
  

// }







 // foreach($arrFromXml as $element){
// if($element["nazwaProduktu"]==="Twinrix Adult")
// {
//   dump($element);
// }
// }
//var_dump($arrayFromXml);

//$newArr=[];


// $i=0;
// foreach($arrayFromXml as $y){

//   if(!array_key_exists("opakowanie", $y)){return;}
  

//   if(array_key_exists("@attributes", $y["opakowanie"])){
//     $newArr[$i]= array_slice($y,0,5);
//     $newArr[$i]["GTIN"]=$y["opakowanie"]["@attributes"]["kodGTIN"] ?? "";
//     $newArr[$i]["kategoriaDostepnosci"]=$y["opakowanie"]["@attributes"]["kategoriaDostepnosci"] ?? "";
   
//     $i++;
    
//    } 
//    else
//   {
//     for($j=0; $j<count($y["opakowanie"]);$j++){
//       $newArr[$i]= array_slice($y,0,5);
//       $newArr[$i]["GTIN"]=$y["opakowanie"][$j]["@attributes"]["kodGTIN"] ??"";
//       $newArr[$i]["kategoriaDostepnosci"]=$y["opakowanie"][$j]["@attributes"]["kategoriaDostepnosci"]?? "";
          
//       $i++;
      
//     }
//       }


// }


//var_dump("fff");
$time2=time();
$time3 = $time2-$time1;
echo "time: $time3";
echo "<br></br>";
printMem();


