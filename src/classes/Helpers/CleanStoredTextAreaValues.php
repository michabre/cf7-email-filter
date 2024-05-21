<?php

class CleanStoredTextAreaValues {
  private $values;

  public function __construct($values) {
    $this->values = $values;
  }

  public function getValues() {
    return $this->cleanValues();
  }

  private function cleanValues() {
    $values = preg_replace('!\s+!', ' ', $this->values);
    $cleanedValues = explode(' ', $values);
    return $cleanedValues;  
  }

}