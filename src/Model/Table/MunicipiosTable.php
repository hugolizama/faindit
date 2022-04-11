<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class MunicipiosTable extends Table{
  public function initialize(array $config) {
    $this->primaryKey('id');
    $this->displayField('nombre');
  }
}