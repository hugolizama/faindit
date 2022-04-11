<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class DepartamentosTable extends Table{
  public function initialize(array $config) {
    $this->primaryKey('id');
    $this->displayField('nombre');
  }
}