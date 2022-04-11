<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class Telefono extends Entity{
  /*protected $_accessible = [
    '*' => false      
  ];*/
  
  public function _setSucursalId($sucursal_id){
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'fields'=>['negocio_id']
    ]);
    $this->set('negocio_id', $sucursal->negocio_id); //agregar id del negocio 
    
    return $sucursal_id;
  }

}
