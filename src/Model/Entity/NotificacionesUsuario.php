<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use \Cake\Core\Configure;

class NotificacionesUsuario extends Entity {

  //campos virtuales disponibles para utilizar
  protected $_virtual = ['fechaInsertFormat', 'fechaRegistroFormat'];

  /* Fecha de registro con formato */
  protected function _getFechaInsertFormat() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    return date_format($this->_properties['fecha_insert'], $formatoFecha);
  }
  
  /* Fecha de registro con formato */
  protected function _getFechaRegistroFormat() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    return date_format($this->_properties['fecha_registro'], $formatoFecha);
  }
}
