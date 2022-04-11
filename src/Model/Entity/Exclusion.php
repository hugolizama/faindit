<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use \Cake\Core\Configure;

class Exclusion extends Entity{  

  //funcion para guardar el id del usuario en la exclusion
  //la funcion se dispara a traves del campo valor
  protected function _setValor($valor){    
    $this->set('usuario_id', Configure::read('cookieUsuario')['id']); //agregar id del usuario     
    return $valor;
  }
}
