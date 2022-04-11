<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Inflector;
//use \Cake\Core\Configure;

class Categoria extends Entity{
  
  //campos virtuales disponibles para utilizar
  protected $_virtual = ['nombreSinAcento'];

  //funcion para convertir el nombre del negocio en slug
  protected function _setNombre($nombre){
    $this->set('nombre_slug', Inflector::slug(mb_strtolower($nombre),'-')); //convierte nombre a slug
    //$this->set('nombre_sin_acento', Inflector::slug(mb_strtolower($nombre),' ')); //convierte nombre sin acentos
    return $nombre;
  }
  
  /* nombre sin acento */
  protected function _getNombreSinAcento() {    
    return Inflector::slug(mb_strtolower($this->_properties['nombre']), ' ');
  }
}
