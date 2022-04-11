<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use \Cake\Core\Configure;

class CategoriasSugerencia extends Entity {

  //campos virtuales disponibles para utilizar
  protected $_virtual = ['fechaCreacionFormat'];

  /* Fecha de registro con formato */
  protected function _getFechaCreacionFormat() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    return date_format($this->_properties['fecha_creacion'], $formatoFecha);
  }

}
