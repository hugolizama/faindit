<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ConfigsTable extends Table{
  
  public function initialize(array $config) {    
    $this->primaryKey('id');
  }
  
  public function validationDefault(Validator $validator) {
    
    $validator
      /*index*/
      ->notEmpty('sitio_nombre')
      ->notEmpty('sitio_nombre_secundario')      
      ->notEmpty('correo_administrador')
      ->notEmpty('sitio_formato_fecha')
      
      /*correo*/
      ->notEmpty('correo_smtp_servidor')      
      ->notEmpty('correo_smtp_puerto')
      ->notEmpty('correo_smtp_usuario')
      ->notEmpty('correo_smtp_contrasena')
      
      ;
    
    return $validator;
  }
}