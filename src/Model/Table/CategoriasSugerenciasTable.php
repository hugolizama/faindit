<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class CategoriasSugerenciasTable extends Table{
  public function initialize(array $config) {
    //parent::initialize($config);
    
    $this->primaryKey('id');
    
    $this->belongsTo('Usuarios', [
      'className'=>'Usuarios',
      'foreignKey'=>'usuario_id'
    ]);
  }
}