<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use \Cake\Utility\Security;
use \Cake\Core\Configure;

class Estadistica extends Entity {

  //campos virtuales disponibles para utilizar
  protected $_virtual = ['FechaDiasFormat', 'FechaMesesFormat'];

  /* Fecha de registro con formato */
  protected function _getFechaDiasFormat() {
    return date_format($this->_properties['fecha'], 'd-m-Y');
  }
  
  protected function _getFechaMesesFormat() {
    return date_format($this->_properties['fecha'], 'm-Y');
  }
}
