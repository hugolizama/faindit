<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use \Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class NegociosTable extends Table{
  
  public function initialize(array $config) {
    //parent::initialize($config);
    
    $this->primaryKey('id');
    
    $this->belongsTo('Usuarios', [
      'className'=>'Usuarios',
      'foreignKey'=>'usuario_id'
    ]);
    
    $this->hasMany('Imagenes', [
      'className'=>'Imagenes',
      'foreignKey'=>'negocio_id',
      'dependent' => true,
      'sort'=> [
        'Imagenes.orden'=>'asc',
        'Imagenes.id'=>'asc'
      ]
    ]);
    
    $this->hasMany('Sucursales', [
      'className'=>'Sucursales',
      'foreignKey'=>'negocio_id',
      'dependent' => true,
      'cascadeCallbacks'=>true
    ]);
    
    $this->belongsToMany('Categorias', [
      'className'=>'Categorias',
      'targetForeignKey' => 'categoria_id',
      'foreignKey' => 'negocio_id',
      'joinTable' => 'negocios_categorias',
      'dependent'=>true
    ]);
  }
  
  
  public function validationDefault(Validator $validator) {
    $config = Configure::read('config');
    $negocios_logo_max_peso = $config['negocios_logo_max_peso'];
    
    $validator
      ->requirePresence('nombre', true, __('El nombre del negocio es requerido'))
      ->notEmpty('nombre', __('El nombre del negocio es requerido'))
      ->add('nombre',[        
        'unique'=>[
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('El nombre del negocio ya se encuentra utilizado')
        ]        
      ])
      ->requirePresence('descripcion', true, __('La descripción del negocio requerido'))
      ->notEmpty('descripcion', __('La descripción del negocio requerido'))
      
      ->allowEmpty('correo') //agregado para permitir negocio sin correo
      ->add('correo', [
        'valid-email'=>[
          'rule' => 'email',
          'message'=>__('Debe introducir un correo electrónico válido')
        ]
      ])
      
      ->allowEmpty('url')
      ->add('url', [
        'valid-url' => [
          'rule'=>['url'],
          'message'=>__('La dirección del sitio web no es válida')
        ]
      ])
      
      //->notEmpty('categorias',__('Debe introducir al menos una categoría')) //pendiende por arreglar, no habilitar
      ->allowEmpty('logo')
      ->add('logo', [
        'extension'=>[
          'rule' => ['extension', ['jpeg', 'png', 'jpg']],
          'message' => __('Este formato de imagen no es permitido. Sólo jpg y png.')
        ],
        'fileSize' => [
          'rule' => array('fileSize', '<=', $negocios_logo_max_peso.'KB'),
          'message' => __('El logo debe ser de un máximo de '.$negocios_logo_max_peso.'KB.'),
        ]
      ])
      
      /*Ya no se validan aqui sino en SucursalesTable.php*/
      /*->notEmpty('pais_id', __('El país es requerido'))
      ->notEmpty('departamento_id', __('El país es requerido'))
      ->notEmpty('municipio_id', __('El país es requerido'))*/
      ;
    
    return $validator;
  }
  
  /*funcion para verificar si existe un registro*/
  public function verifExiste($valor, $tabla, $campo){
    $table = TableRegistry::get($tabla);
    
    $busqueda = $table->exists([
      $campo => $valor
    ]);
        
    if(empty($busqueda)){
      return false;
    }
    
    return true;
  }
}
