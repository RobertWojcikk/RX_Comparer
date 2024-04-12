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

class XmlToArray{

  private int $countIx;
  private ?object $xml;
  private ?string $string;
  public function __construct($countIx, $xml,$string)
  {
  $this->countIx=$countIx;
  $this->xml = $xml;
  $this->string = $string;     
  }

public function flatten($array){
  $this->string      = http_build_query($array);
  $this->string      = urldecode($this->string);
  $this->string      = str_replace(
                      array('[',']', "@attributes", "substancjaCzynna"),
                      array('','', '', '') , 
                      $this->string
                  );
   parse_str($this->string, $flat_array);
   return $flat_array;
}


// public function flatten($array, $prefix = '') {
//   $toReplace = ["@attributes"];
  
//   $result = array();
//   foreach($array as $key=>$value) {
//   if($value===$toReplace){
//     $value ="";
//   }  
//       if(is_array($value)) {

//           $result = $result + $this->flatten($value, $prefix . $key . '.');
      
//         }
//       else {
//           $result[$prefix . $key] = $value;
//       }
//   }
//   return $result;
// }



  public function transformXMLToAssocArr():array{

  $this->xml = new XMLReader();
  $this->xml->open('/RX_Comparer/api/Database/dowloadedXML2024-04-05.xml');
    
  $array=[];
  while($this->xml->read() && $this->xml->name != 'produktLeczniczy')
  {
    ;
  }
  
  while($this->xml->name =="produktLeczniczy"){
    $element = simplexml_load_string($this->xml->readOuterXML());
    $arrayFromXML = json_decode(json_encode($element), true);
    unset(
      $arrayFromXML["kodyATC"], 
      $arrayFromXML["drogiPodania"], 
      $arrayFromXML["daneOWytworcy"],
      $arrayFromXML["@attributes"]["rodzajPreparatu"],  
      $arrayFromXML["@attributes"]["nazwaPoprzedniaProduktu"],  
      $arrayFromXML["@attributes"]["podmiotOdpowiedzialny"],  
      $arrayFromXML["@attributes"]["typProcedury"],  
      $arrayFromXML["@attributes"]["numerPozwolenia"],  
      $arrayFromXML["@attributes"]["waznoscPozwolenia"],  
      $arrayFromXML["@attributes"]["ulotka"],  
      $arrayFromXML["@attributes"]["charakterystyka"], 
      $arrayFromXML["@attributes"]["id"],
      $arrayFromXML["substancjeCzynne"]["substancjaCzynna"]["@attributes"]["innyOpisIlosci"]
        );
  
      $count = array_key_exists("substancjaCzynna", $arrayFromXML["substancjeCzynne"]) 
      ? count($arrayFromXML["substancjeCzynne"]["substancjaCzynna"])
      : 1;

      $array[$this->countIx]=
      [
        ...$arrayFromXML["@attributes"],
        ...self::flatten($arrayFromXML["substancjeCzynne"]),
        "ilość składników" => $count,
        ...$arrayFromXML["opakowania"]
      ];
      //$array[$this->countIx]["suma"]= [...self::countTotalValueOfSubstance($array[$this->countIx], $count)];

    $this->countIx++;
    $this->xml->next("produktLeczniczy");
    unset($element);
    unset($arrayFromXML);
    
  }

  return $array;
  }
  }
