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

  public function __construct($countIx, $xml)
  {
  $this->countIx=$countIx;
  $this->xml = $xml;     
  }

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
 
    $array[$this->countIx]=[
    ...$arrayFromXML["@attributes"],
    "substancjeCzynne"=>$arrayFromXML["substancjeCzynne"]["substancjaCzynna"]["@attributes"] ?? $arrayFromXML["substancjeCzynne"],
    ...$arrayFromXML["opakowania"]];
    $this->countIx++;
    $this->xml->next("produktLeczniczy");
    unset($element);
    unset($arrayFromXML);
  }

  return $array;
  }
  }
