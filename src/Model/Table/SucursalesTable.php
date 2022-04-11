<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use \Cake\Validation\Validator;
use Cake\Core\Configure;

class SucursalesTable extends Table{
  
  public function initialize(array $config) {
    //parent::initialize($config);
    
    $this->primaryKey('id');
    
    $this->belongsTo('Usuarios', [
      'className'=>'Usuarios',
      'foreignKey'=>'usuario_id'
    ]);
    
    $this->belongsTo('Negocios', [
      'className'=>'Negocios',
      'foreignKey'=>'negocio_id'
    ]);
    
    $this->belongsTo('Paises', [
      'className'=>'Paises',
      'foreignKey'=>'pais_id'
    ]);
    
    $this->belongsTo('Departamentos', [
      'className'=>'Departamentos',
      'foreignKey'=>'departamento_id'
    ]);
    
    $this->belongsTo('Municipios', [
      'className'=>'Municipios',
      'foreignKey'=>'municipio_id'
    ]);
    
    $this->hasMany('Telefonos', [
      'className'=>'Telefonos',
      'foreignKey'=>'sucursal_id',
      'dependent' => true
    ]);
    
    $this->hasMany('Imagenes', [
      'className'=>'Imagenes',
      'foreignKey'=>'sucursal_id',
      'dependent' => true,
      'sort'=> [
        'Imagenes.orden'=>'asc',
        'Imagenes.id'=>'asc'
      ]
    ]);
  }
  
  
  public function validationDefault(Validator $validator) {
    $validator
      ->requirePresence('nombre', true, __('El nombre de sucursal es requerido'))
      ->notEmpty('nombre', __('El nombre de la sucursal es requerido'))
      
      ->allowEmpty('correo')
      ->add('correo', [
        'valid-email'=>[
          'rule' => 'email',
          'message'=>__('Debe introducir un correo electrónico válido')
        ]
      ])
      
      ->requirePresence('pais_id', true, __('El país es requerido'))
      ->notEmpty('pais_id', __('El país es requerido'))
      
      ->requirePresence('departamento_id', true, __('El departamento es requerido'))
      ->notEmpty('departamento_id', __('El departamento es requerido'))
      
      ->requirePresence('municipio_id', true, __('El municipio es requerido'))
      ->notEmpty('municipio_id', __('El municipio es requerido'))
      ;
    
    return $validator;
  }
  
  public function validationUbicacion(Validator $validator) {
    $validator      
      ->requirePresence('pais_id', true, __('El país es requerido'))
      ->notEmpty('pais_id', __('El país es requerido'))
      
      ->requirePresence('departamento_id', true, __('El departamento es requerido'))
      ->notEmpty('departamento_id', __('El departamento es requerido'))
      
      ->requirePresence('municipio_id', true, __('El municipio es requerido'))
      ->notEmpty('municipio_id', __('El municipio es requerido'))
      ;
    
    return $validator;
  }
}
