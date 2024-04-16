<?php
declare(strict_types=1);
ini_set('memory_limit','1024M');

//namespace RX_Comparer;

require_once '/RX_Comparer/api/Model/FileNameGenerator.php';
require_once '/RX_Comparer/api/Config/config.php';
require_once '/RX_Comparer/api/Model/Model.php';
require_once '/RX_Comparer/api/Utils/debug.php';
require_once '/RX_Comparer/api/Utils/memCheck.php';
require_once '/RX_Comparer/api/Model/DownloadXML.php';
require_once '/RX_Comparer/api/Model/XmlToArray.php';

class PrepareForDatabase{

  private int $i;
  private array $newArr;
 // private string $string;
// public $arrr;
  
  public function __construct($i,$newArr){
      $this->i=$i;
      $this->newArr = $newArr;
      
    //  $this->string = $string;
}

public function countTotalValueOfSubstances($array){
  $result =array();
  //if(!array_key_exists("jednostka", $array)){return [];}
  
  if($array["jednostka"]==="tabl." || $array["jednostka"]==="kaps."){
     $unitOfWeight=1;
 
  for($g=0;$g<$array["ilość składników"];$g++){

    if(!array_key_exists("iloscSubstancji_" . $g, $array)){return [null];}
    $array["iloscSubstancji_".$g] = str_replace(",",".",$array["iloscSubstancji_".$g]);  
   
    if($array["jednostkaMiaryIlosciSubstancji_" . $g] ==="mg"){
    $unitOfWeight=1000;
    }elseif (
    $array["jednostkaMiaryIlosciSubstancji_" . $g] ==="mcg" || 
    $array["jednostkaMiaryIlosciSubstancji_" . $g] ==="µg"
    ){
    $unitOfWeight=1000000;
    }

   $result["sumaSubst_".$g] = (intval($array["pojemność"]) * floatval($array["iloscSubstancji_" . $g]))/$unitOfWeight;
  
    }
  } 
return $result;
}

private function countTotalValueOfOneSubstance($array){
  if(
    !array_key_exists("jednostka", $array) ||
    !array_key_exists("iloscSubstancji", $array) ||
    !array_key_exists("jednostkaMiaryIlosciSubstancji", $array)
    ){return [0];}
    $array["iloscSubstancji"] = str_replace(",",".",$array["iloscSubstancji"]);
    $result =[];
    $unitOfWeight = 1;    
    if($array["jednostkaMiaryIlosciSubstancji"] ==="mg"){
    $unitOfWeight=1000;
    }elseif (
    $array["jednostkaMiaryIlosciSubstancji"] ==="mcg" || 
    $array["jednostkaMiaryIlosciSubstancji"] ==="µg"
    ){
    $unitOfWeight=1000000;
    }

    $flag = ($array["jednostka"]==="tabl."|| $array["jednostka"]==="kaps.");

    if($flag){
      $result = (intval($array["pojemność"]) * floatval($array["iloscSubstancji"]))/$unitOfWeight;
      return $result; 

    }
}


public function createArray($array):?array{


  foreach($array as $y){


    if(array_key_exists("opakowanie", $y) && array_key_exists("@attributes", $y["opakowanie"])){
    $this->newArr[$this->i]=[...$y];
    $this->newArr[$this->i]["GTIN"]=$y["opakowanie"]["@attributes"]["kodGTIN"] ?? "";
    $this->newArr[$this->i]["kategoriaDostepnosci"]=$y["opakowanie"]["@attributes"]["kategoriaDostepnosci"] ?? "";
    $this->newArr[$this->i]["liczbaOpakowań"] =$y["opakowanie"]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["liczbaOpakowan"] ?? "";
    $this->newArr[$this->i]["pojemność"]=$y["opakowanie"]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["pojemnosc"] ??"";  
    $this->newArr[$this->i]["jednostka"]=$y["opakowanie"]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["jednostkaPojemnosci"]??"";
    $this->newArr[$this->i]["ilość składników"]===1 
      ? $this->newArr[$this->i]["sumaSubst_0"] = self::countTotalValueOfOneSubstance($this->newArr[$this->i]) 
      : null;
      $this->newArr[$this->i]["ilość składników"]>1
      ? $this->newArr[$this->i] =array_merge($this->newArr[$this->i],self::countTotalValueOfSubstances($this->newArr[$this->i]))
      : null; 
     
      unset($this->newArr[$this->i]["opakowanie"]);
     // unset($y);
      $this->i++;
    } 
     elseif(array_key_exists("opakowanie", $y) &&  array_key_exists(0, $y["opakowanie"]))
    {
      for($j=0; $j<count($y["opakowanie"]);$j++){
        $this->newArr[$this->i]=[...$y]; 
        $this->newArr[$this->i]["GTIN"]=$y["opakowanie"][$j]["@attributes"]["kodGTIN"] ??"";
        $this->newArr[$this->i]["kategoriaDostepnosci"]=$y["opakowanie"][$j]["@attributes"]["kategoriaDostepnosci"]?? "";
        $this->newArr[$this->i]["liczbaOpakowań"] =$y["opakowanie"][$j]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["liczbaOpakowan"] ?? "";
        $this->newArr[$this->i]["pojemność"]=$y["opakowanie"][$j]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["pojemnosc"] ??"";  
        $this->newArr[$this->i]["jednostka"]=$y["opakowanie"][$j]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["jednostkaPojemnosci"]??"";
        $this->newArr[$this->i]["ilość składników"]===1 
        ? $this->newArr[$this->i]["sumaSubst_0"] = self::countTotalValueOfOneSubstance($this->newArr[$this->i]) 
        : null; 
        $this->newArr[$this->i]["ilość składników"]>1
        ? $this->newArr[$this->i] =array_merge($this->newArr[$this->i],self::countTotalValueOfSubstances($this->newArr[$this->i]))
        : null;
    
        unset($this->newArr[$this->i]["opakowanie"]);
        //unset(...$y);
        $this->i++;
 
 
      }
    }
    else
    {
      $this->newArr[$this->i]=[...$y];
      $this->i++;


    }

 unset($y); }
 unset($array);
  return $this->newArr;
}
public function getNewArr(){
  return $this->newArr;
}

  













// public function substance($array):array{
// $this->i =0;
// foreach($array as $element){
//   if(!array_key_exists("substancjaCzynna", $element["substancjeCzynne"])){
//     $this->newArr[$this->i] =array_merge($this->newArr[$this->i],[...$element["substancjeCzynne"]]);
//     //unset($element["substancjeCzynne"]);
//     $this->i++;
//   }else{
// foreach($element["substancjeCzynne"]["substancjaCzynna"] as $subst){
//   $this->newArr[$this->i]=array_merge($this->newArr[$this->i],[...$subst["@attributes"]]);
//  // $this->newArr[$this->i][] =[...$subst["@attributes"]];
//   //unset($subst); 
//  $this->i++;  
// }
//   }
// }  
// return $this->newArr;
// }

}
