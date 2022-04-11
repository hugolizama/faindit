<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class ImagenesTable extends Table{
  public function initialize(array $config) {
    $this->primaryKey('id');
  }
}