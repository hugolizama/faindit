<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class NotificacionesUsuariosTable extends Table{
  public function initialize(array $config) {
    $this->primaryKey('id');   
    
    $this->belongsTo('Usuarios', [
      'className'=>'Usuarios',
      'foreignKey' => 'usuario_id'
    ]);
  }
}