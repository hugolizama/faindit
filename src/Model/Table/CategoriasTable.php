<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use \Cake\Validation\Validator;

class CategoriasTable extends Table{
  
  public function initialize(array $config) {  
    $this->primaryKey('id');
    
    $this->belongsToMany('Negocios', [
      'className'=>'Negocios',
      'targetForeignKey' => 'negocio_id',
      'foreignKey' => 'categoria_id',
      'joinTable' => 'negocios_categorias',
      'dependent'=>true
    ]);
    
    
    $this->hasMany('NegociosCategorias', [
      'className'=>'NegociosCategorias',          
      'foreignKey' => 'categoria_id',
    ]);
  }
  
  
  public function validationDefault(Validator $validator) {
    $validator
      ->notEmpty('nombre',__('Requerido'))
      ->add('nombre', [
        'unique'=>[
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('Categoría ya registrada')
        ]
      ]);
    
    return $validator;
  }
}