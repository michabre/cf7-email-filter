<?php

class BuildList {
  private $type;
  private $items;

  public function __construct($items) {
    $this->type = gettype($items);
    $this->items = $items;
  }
  
  public function buildList() {
    if ($this->type === 'string') {
      return $this->buildListFromString();
    } elseif ($this->type === 'array') {
      return $this->buildListFromArray();
    }
  }

  private function buildListFromArray() {
    $array = $this->items;
    sort($array);
    $list = '';
    foreach ($array as $item) {
      $list .= esc_html($item) . '&#13;&#10;';
    }
    return $list;
  }

  private function buildListFromString() {
    $array = explode(' ', $this->items);
    sort($array);
    $list = '';
    foreach ($array as $item) {
      $list .= esc_html($item) . '&#13;&#10;';
    }
    return $list;
  }
}