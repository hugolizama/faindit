<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use \Cake\Core\Configure;

class Sucursal extends Entity{  

  //funcion para guardar el id del usuario en la sucursal
  //la funcion se dispara a traves del campo nombre
  protected function _setNombre($nombre){    
    if(!Configure::check('cookieUsuarioAdmin')){
      $this->set('usuario_id', Configure::read('cookieUsuario')['id']); //agregar id del usuario     
    }
    
    return $nombre;
  }
}
