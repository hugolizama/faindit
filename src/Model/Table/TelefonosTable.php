<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TelefonosTable extends Table{
  
  public function initialize(array $config) {  
    $this->primaryKey('id');
    
    $this->belongsTo('Negocios', [
      'className'=>'Negocios',
      'foreignKey' => 'negocio_id'
    ]);
  }
}
