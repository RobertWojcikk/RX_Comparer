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
  
  public function __construct($i,$newArr){
      $this->i=$i;
      $this->newArr = $newArr;
}

public function createArray($array):?array{
  foreach($array as $y){
  
  if(!array_key_exists("opakowanie", $y)){return [];}

  

  if(array_key_exists("@attributes", $y["opakowanie"])){
    array_key_exists("moc", $y) ? $this->newArr[$this->i]= array_slice($y,0,5) 
                                : $this->newArr[$this->i]= array_slice($y,0,4);
    
    //$this->newArr[$this->i]= array_slice($y,0,5);
    $this->newArr[$this->i]["GTIN"]=$y["opakowanie"]["@attributes"]["kodGTIN"] ?? "";
    $this->newArr[$this->i]["kategoriaDostepnosci"]=$y["opakowanie"]["@attributes"]["kategoriaDostepnosci"] ?? "";
    $this->i++;
    } 
     else
    {
      for($j=0; $j<count($y["opakowanie"]);$j++){
        
        array_key_exists("moc", $y) ? $this->newArr[$this->i]= array_slice($y,0,5) 
                                    : $this->newArr[$this->i]= array_slice($y,0,4);
        

        //$this->newArr[$this->i]= array_slice($y,0,5);
        $this->newArr[$this->i]["GTIN"]=$y["opakowanie"][$j]["@attributes"]["kodGTIN"] ??"";
        $this->newArr[$this->i]["kategoriaDostepnosci"]=$y["opakowanie"][$j]["@attributes"]["kategoriaDostepnosci"]?? "";
        $this->i++;
        }
    }
  }
  return $this->newArr;
}



public function substance($array):array{
$this->i =0;
foreach($array as $element){
  if(!array_key_exists("substancjaCzynna", $element["substancjeCzynne"])){
    $this->newArr[$this->i] =array_merge($this->newArr[$this->i],[...$element["substancjeCzynne"]]);
    //unset($element["substancjeCzynne"]);
    $this->i++;
  }else{
foreach($element["substancjeCzynne"]["substancjaCzynna"] as $subst){
  $this->newArr[$this->i]=array_merge($this->newArr[$this->i],[...$subst["@attributes"]]);
 // $this->newArr[$this->i][] =[...$subst["@attributes"]];
  //unset($subst); 
 $this->i++;  
}
  }
}  
return $this->newArr;
}
}
