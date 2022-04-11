<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RolesTable extends Table{
  
  public function initialize(array $config) {
    $this->primaryKey('id');
    $this->displayField('nombre');
    
    $this->hasMany('Usuarios', [
      'className'=>'Usuarios',
      'foreignKey'=>'rol_id'
    ]);
  }
  
  
  public function validationDefault(Validator $validator) {
    
    $validator
      ->notEmpty('nombre',__('El nombre de rol es requerido'))
      ->add('nombre', [
        'unico' => [        
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('El nombre de rol ya ha sido utilizado')
      ]])
      ->notEmpty('descripcion',__('La descripción del rol es requerido'));
    
    return $validator;
  }
}