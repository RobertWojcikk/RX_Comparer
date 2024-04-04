<?php
declare(strict_types=1);

//namespace RX_Comparer;

require_once '/RX_Comparer/api/Model/FileNameGenerator.php';
require_once '/RX_Comparer/api/Config/config.php';
require_once '/RX_Comparer/api/Model/Model.php';
require_once '/RX_Comparer/api/Utils/debug.php';
require_once '/RX_Comparer/api/Utils/memCheck.php';
require_once '/RX_Comparer/api/Model/DownloadXML.php';

class XmlToArray{




  // $countIx=0;

  // $xml = new XMLReader();
  // $xml->open('/Application/api/Database/2024-03-28.xml');
  
  
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

}

