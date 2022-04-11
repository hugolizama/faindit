<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Inflector;

/**
 * CakePHP Municipio
 * @author hugo lizama
 */
class Municipio extends Entity {
  
  //campos virtuales disponibles para utilizar
  protected $_virtual = ['nombreSinAcento'];
  
  /* nombre del municipio sin acento */
  protected function _getNombreSinAcento() {    
    return Inflector::slug(mb_strtolower($this->_properties['nombre']), ' ');
  }
}
