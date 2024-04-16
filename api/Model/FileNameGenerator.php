<?php
declare(strict_types=1);

//namespace RX_Comparer;

class FileNameGenerator {

  private string $fileDate;

  public function __construct(string $fileDate='') {
    $this->fileDate=$fileDate;
  }

  public function getDate() {
    $currentTime = time();
    $this->fileDate = date('Y-m-d', $currentTime);
    return $this->fileDate;
  }
 
}