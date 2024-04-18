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
use BenTools\RewindableGenerator;

class XmlToArray{

  public int $countIx;
  private ?object $xml;
  private ?string $string;
  private string $filename;
  private generator $generator;
  private generator $generator2;

  public function __construct($countIx, $xml,$string, $filename, $generator, $generator2)
  {
  $this->countIx=$countIx;
  $this->xml = $xml;
  $this->string = $string; 
 // $this->$filename = $filename;   
  $this->generator = $generator; 
  $this->generator2 = $generator2; 
  }


public function flatten($array){
  $result=[];
  $this->string      = http_build_query($array);
  $this->string      = urldecode($this->string);
  $this->string      = str_replace(
                      array('[',']', "@attributes", "substancjaCzynna"),
                      array('','', '', '') , 
                      $this->string
                  );
  
   parse_str($this->string, $flat_array);

   foreach($flat_array as $key=>$value){
    if(is_numeric($key[0]) && is_numeric($key[1])){
      $result[substr($key,2) . "_" . $key[0] . $key[1]] =$value;   
    }
    if(is_numeric($key[0])){
      $result[substr($key,1) . "_" . $key[0]] =$value;   
    }
    else {
      return $flat_array;
    }
  }

   return $result;
}


public function transformXMLToAssocArr($filename){

  $this->xml = new XMLReader();
  $this->xml->open($filename);

  while($this->xml->read() && $this->xml->name != 'produktLeczniczy')
  {
    ;
  }
  
  while($this->xml->name =="produktLeczniczy")
  {

    $arrayFromXML = json_decode(
                          json_encode(
                          simplexml_load_string(
                          $this->xml->readOuterXML())),
                          true);
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
         
         yield $arrayFromXML;
        //  array_key_exists("substancjaCzynna", $arrayFromXML["substancjeCzynne"]) 
        //  ? $count =  count($arrayFromXML["substancjeCzynne"]["substancjaCzynna"])
        //  : $count =  1;
    
      
        //    $array[$this->countIx]=
        //   [
        //     ...$arrayFromXML["@attributes"],
        //     ...self::flatten($arrayFromXML["substancjeCzynne"]),
        //     "ilość składników" => $count,
    
        //    ...$arrayFromXML["opakowania"] 
        //   ];
        //   unset($arrayFromXML);
        //   yield $array[$this->countIx];
        //  $this->countIx++;
        
        $this->xml->next("produktLeczniczy");
  }
                       
                        }
//  public function getGenerator($filename){

//   yield $this->generator = self::transformXMLToAssocArr($filename);

//  }  
 
 



function create($filename){

foreach(self::transformXMLToAssocArr($filename) as $row){

       array_key_exists("substancjaCzynna", $row["substancjeCzynne"]) 
     ? $count =  count($row["substancjeCzynne"]["substancjaCzynna"])
     : $count =  1;

  
       $array[$this->countIx]=
      [
        ...$row["@attributes"],
        ...self::flatten($row["substancjeCzynne"]),
        "ilość składników" => $count,

       ...$row["opakowania"] 
      ];
  yield $array[$this->countIx];
  unset($array[$this->countIx]);
      $this->countIx++;
  
}
return $array;
}
// public function getGenerator2($filename){

//   return $this->generator2 = self::create($filename);

//  }  

      
      
      


  
//       array_key_exists("substancjaCzynna", $arrayFromXML["substancjeCzynne"]) 
//       ? $count =  count($arrayFromXML["substancjeCzynne"]["substancjaCzynna"])
//       : $count =  1;

//        $array[$this->countIx]=
//       [
//         ...$arrayFromXML["@attributes"],
//         ...self::flatten($arrayFromXML["substancjeCzynne"]),
//         "ilość składników" => $count,

//        ...$arrayFromXML["opakowania"] 
//       ];
    
//     $this->countIx++;
//     $this->xml->next("produktLeczniczy");

//     unset($element);
//     unset($arrayFromXML);
//     unset($count);
    
//   }
// unset($this->xml);

// return $array;
//   }





//   public function transformXMLToAssocArr():array{

//   $this->xml = new XMLReader();
//   $this->xml->open('/RX_Comparer/api/Database/dowloadedXML2024-04-13.xml');
    
//   $array=[];
//   while($this->xml->read() && $this->xml->name != 'produktLeczniczy')
//   {
//     ;
//   }
  
//   while($this->xml->name =="produktLeczniczy"){
//    // $element = simplexml_load_string($this->xml->readOuterXML());
//    // $arrayFromXML = json_decode(json_encode($element), true);
//    $arrayFromXML = json_decode(json_encode(simplexml_load_string($this->xml->readOuterXML())), true);


//     unset(
//       $arrayFromXML["kodyATC"], 
//       $arrayFromXML["drogiPodania"], 
//       $arrayFromXML["daneOWytworcy"],
//       $arrayFromXML["@attributes"]["rodzajPreparatu"],  
//       $arrayFromXML["@attributes"]["nazwaPoprzedniaProduktu"],  
//       $arrayFromXML["@attributes"]["podmiotOdpowiedzialny"],  
//       $arrayFromXML["@attributes"]["typProcedury"],  
//       $arrayFromXML["@attributes"]["numerPozwolenia"],  
//       $arrayFromXML["@attributes"]["waznoscPozwolenia"],  
//       $arrayFromXML["@attributes"]["ulotka"],  
//       $arrayFromXML["@attributes"]["charakterystyka"], 
//       $arrayFromXML["@attributes"]["id"],
//       $arrayFromXML["substancjeCzynne"]["substancjaCzynna"]["@attributes"]["innyOpisIlosci"]
//         );
  
//       array_key_exists("substancjaCzynna", $arrayFromXML["substancjeCzynne"]) 
//       ? $count =  count($arrayFromXML["substancjeCzynne"]["substancjaCzynna"])
//       : $count =  1;

//        $array[$this->countIx]=
//       [
//         ...$arrayFromXML["@attributes"],
//         ...self::flatten($arrayFromXML["substancjeCzynne"]),
//         "ilość składników" => $count,

//        ...$arrayFromXML["opakowania"] 
//       ];
    
//     $this->countIx++;
//     $this->xml->next("produktLeczniczy");

//     unset($element);
//     unset($arrayFromXML);
//     unset($count);
    
//   }
// unset($this->xml);

// return $array;
//   }



  }
