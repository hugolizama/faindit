<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ExclusionesTable extends Table{
  
  public function initialize(array $config) {  
    $this->primaryKey('id');
    
    /*$this->belongsTo('Usuarios', [
      'className'=>'Usuarios',
      'foreignKey' => 'usuario_id'
    ]);*/
  }
  
  public function validationDefault(Validator $validator) {
    $validator
      ->notEmpty('valor',__('El campo no puede estar vacío'))
      ->add('valor',[        
        'unique'=>[
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('La exclusión se encuentra repetida')
        ]       
      ]);
    
    return $validator;
  }
  
}
