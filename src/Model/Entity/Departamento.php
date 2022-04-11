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
 * CakePHP Departamento
 * @author hugo lizama
 */
class Departamento extends Entity {
  
  //campos virtuales disponibles para utilizar
  protected $_virtual = ['nombreSinAcento'];
  
  /* nombre del departamento sin acento */
  protected function _getNombreSinAcento() {    
    return Inflector::slug(mb_strtolower($this->_properties['nombre']), ' ');
  }
}
