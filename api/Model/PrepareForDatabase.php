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

class PrepareForDatabase{

  private int $i;
  public array $newArr;
  public string $string;
  
  public function __construct($i,$newArr,$string){
      $this->i=$i;
      $this->newArr = $newArr;
      $this->string =$string;
}

public function countTotalValueOfSubstance($array,$count):?array{
  
  if(
    !array_key_exists("jednostka", $array) ||
    !array_key_exists("iloscSubstancji", $array) ||
    !array_key_exists("jednostkaMiaryIlosciSubstancji", $array)
    ){return [0];}
    $array["iloscSubstancji"] = str_replace(",",".",$array["iloscSubstancji"]);
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
  
    if($count ===1 && $flag){
      $result = [(intval($array["pojemność"]) * floatval($array["iloscSubstancji"]))/$unitOfWeight];
      return [...$result]; 
    }
    elseif($count>1){
      // for($i=0;$i<=$count;$i++){
      //   $result = 0;
      //   $result = 
      // }
    return [];
  }
}





public function createArray($array):?array{
  foreach($array as $y){
    if(!array_key_exists("opakowanie", $y)){return [];}

    if(array_key_exists("@attributes", $y["opakowanie"])){
    // array_key_exists("moc", $y) ? $this->newArr[$this->i]= array_slice($y,0,5) 
    //                             : $this->newArr[$this->i]= array_slice($y,0,4);
    $this->newArr[$this->i]=[...$y];
    $this->newArr[$this->i]["GTIN"]=$y["opakowanie"]["@attributes"]["kodGTIN"] ?? "";
    $this->newArr[$this->i]["kategoriaDostepnosci"]=$y["opakowanie"]["@attributes"]["kategoriaDostepnosci"] ?? "";
    $this->newArr[$this->i]["liczbaOpakowań"] =$y["opakowanie"]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["liczbaOpakowan"] ?? "";
    $this->newArr[$this->i]["pojemność"]=$y["opakowanie"]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["pojemnosc"] ??"";  
    $this->newArr[$this->i]["jednostka"]=$y["opakowanie"]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["jednostkaPojemnosci"]??"";
    $this->newArr[$this->i]["sumaGram"] = [...self::countTotalValueOfSubstance($this->newArr[$this->i], $this->newArr[$this->i]["ilość składników"])] ?? null;
    
    unset($this->newArr[$this->i]["opakowanie"]);
    $this->i++;
    } 
     else
    {
      for($j=0; $j<count($y["opakowanie"]);$j++){
        
        // array_key_exists("moc", $y) ? $this->newArr[$this->i]= array_slice($y,0,5) 
        //                             : $this->newArr[$this->i]= array_slice($y,0,4);
        
        $this->newArr[$this->i]=[...$y];
        //$this->newArr[$this->i]= array_slice($y,0,5);
        $this->newArr[$this->i]["GTIN"]=$y["opakowanie"][$j]["@attributes"]["kodGTIN"] ??"";
        $this->newArr[$this->i]["kategoriaDostepnosci"]=$y["opakowanie"][$j]["@attributes"]["kategoriaDostepnosci"]?? "";
        $this->newArr[$this->i]["liczbaOpakowań"] =$y["opakowanie"][$j]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["liczbaOpakowan"] ?? "";
        $this->newArr[$this->i]["pojemność"]=$y["opakowanie"][$j]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["pojemnosc"] ??"";  
        $this->newArr[$this->i]["jednostka"]=$y["opakowanie"][$j]["jednostkiOpakowania"]["jednostkaOpakowania"]["@attributes"]["jednostkaPojemnosci"]??"";
        $this->newArr[$this->i]["sumaGram"] = self::countTotalValueOfSubstance($this->newArr[$this->i], $this->newArr[$this->i]["ilość składników"])[0] ?? null;
        unset($this->newArr[$this->i]["opakowanie"]);
        
        $this->i++;
        }
    }
  }
  return $this->newArr;
}
public function getNewArr(){
  return $this->newArr;
}

// public function flatten($array, $prefix = '') {
//   $result = array();
//   foreach($array as $key=>$value) {
//       if(is_array($value)) {
//           $result = $result + $this->flatten($value, $prefix . $key . '.');
//       }
//       else {
//           $result[$prefix . $key] = $value;
//       }
//   }
//   return $result;
// }
// public function substance($array):array{

// $this->i=0;
//   foreach($array as $element){
//     $x=$this->newArr[$this->i];
//     $this->newArr2[$this->i]= array_merge($x, $this->flatten($element["substancjeCzynne"]));
//   $this->i++;
//   }
// return $this->newArr2;
// }


// public function substance($array):array{
//   $this->i =0;
//   foreach($array as $element){
//     if(!array_key_exists("substancjaCzynna", $element["substancjeCzynne"])){
//       $this->string ="";
//       $flat_array=[];
//       $this->string = http_build_query($element["substancjeCzynne"]);
//       $this->string = urldecode($this->string);
//       $this->string =str_replace(array('[',']'),
//       array('_','') , $this->string);
//   parse_str($this->string, $flat_array);
//       $this->newArr[$this->i][] = $flat_array;
//       $this->newArr[$this->i]["i"]=$this->i;
//        }else{
//   foreach($element["substancjeCzynne"]["substancjaCzynna"] as $subst){
//     $this->string ="";
//     $flat_array=[];
//     $this->string = http_build_query($subst["@attributes"]);
// $this->string = urldecode($this->string);
// $this->string =str_replace(array('[',']'),array('_','') , 
// $this->string);
// parse_str($this->string, $flat_array);
    
    
//     $this->newArr[$this->i][]=$flat_array;
//     $this->newArr[$this->i]["i"]=$this->i;
//     //array_merge($this->newArr[$this->i],[...$subst["@attributes"]]);
//    // $this->newArr[$this->i][] =[...$subst["@attributes"]];
//     //unset($subst); 
//     //unset($string,$flat_array);
//     // $this->i++;  

//   }
//     }
//     $this->i++;
//   }  
//   return $this->newArr;
//   }
  















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
