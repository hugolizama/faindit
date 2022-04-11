<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class UsuariosTable extends Table{
  
  public function initialize(array $config) {
    $this->primaryKey('id');
    
    $this->hasMany('Negocios', [
      'className'=>'Negocios',
      'foreignKey'=>'usuario_id',
      'dependent'=>true,
      'cascadeCallbacks'=>true
    ]);
    
    $this->belongsTo('Roles', [
      'className'=>'Roles',
      'foreignKey' => 'rol_id'
    ]);
    
    
    $this->hasOne('NotificacionesUsuarios', [
      'className' => 'NotificacionesUsuarios',
      'foreignKey' => 'usuario_id',
      'dependent'=>true,
      'cascadeCallbacks'=>true
    ]);
  }
  
      
  /*validar registro en el sitio web*/
  public function validationDefault(Validator $validator) {  
    $usuarios_usuario_min_car = Configure::read('config')['usuarios_usuario_min_car'];
    $usuarios_usuario_max_car = Configure::read('config')['usuarios_usuario_max_car'];
    
    $usuarios_contrasena_min_car = Configure::read('config')['usuarios_contrasena_min_car'];
    $usuarios_contrasena_max_car = Configure::read('config')['usuarios_contrasena_max_car'];
    
    $validator
      //usuario
      ->notEmpty('usuario',__('El nombre de usuario es requerido'))
      ->add('usuario',[
        'lenght'=>[
          'rule'=>['lengthBetween' , $usuarios_usuario_min_car, $usuarios_usuario_max_car],
          'message'=>__('El nombre de usuario debe ser entre '.$usuarios_usuario_min_car.' y '.$usuarios_usuario_max_car.' caracteres.')
        ],
        'unique'=>[
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('El nombre de usuario ya se encuentra utilizado')
        ],
        'numLetras'=>[
          'rule'=>['custom','/^[a-z0-9A-Z_-]*$/i'],
          'message' => __('Solo se permite letras, números y guíon (-_). Sin espacios.')
        ],
        'usuarioExcluido' => [
          'rule'=>['excluido', 3],
          'message'=>__('Este nombre de usuario no puede ser utilizado'),
          'provider'=>'table'
        ]
      ])
      
      //correo
      ->notEmpty('correo',__('El correo electrónico es requerido'))
      ->add('correo', [
        'valid-email'=>[
          'rule' => 'email',
          'message'=>__('Debe introducir un correo electrónico válido')
        ],
        'unico'=>[
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('Este correo electrónico ya se encuentra utilizado')
        ],
        'correoExcluido' => [
          'rule'=>['excluido', 1],
          'message'=>__('Este correo electrónico no puede ser utilizado'),
          'provider'=>'table'
        ]
      ])
      
      //confirmar correo
      ->notEmpty('confirmarcorreo',__('La confirmación del correo electrónico es requerido'))
      ->add('confirmarcorreo', [
        'comparar'=>[
          'rule'=>['compareWith', 'correo'],
          'message' => __('Los correos electrónicos no coinciden')
        ]
      ])
      
      //contrasena
      ->notEmpty('contrasena',__('La contraseña es requerida'))
      ->add('contrasena', [
        'lenght' => [
          'rule'=>['lengthBetween' , $usuarios_contrasena_min_car, $usuarios_contrasena_max_car],
          'message'=>__('La contraseña debe ser entre '.$usuarios_contrasena_min_car.' y '.$usuarios_contrasena_max_car.' caracteres.')
        ]
      ])
      
      //confirmar contrasena
      ->notEmpty('confirmar',__('La confirmación de contraseña es requerida'))
      ->add('confirmar', [
        'comparar'=>[
          'rule'=>['compareWith', 'contrasena'],
          'message' => __('Las contraseñas no coinciden')
        ]
      ])      
      
      //contrasena_actual para formulario de cambio de contrasena y eliminacion de cuenta
      ->notEmpty('contrasena_actual', __('La contraseña actual es requerida'))
      ->add('contrasena_actual', [
        'verifContrasenaActual' => [
          'rule' => ['verifContrasenaActual'],
          'message' => __('Contraseña actual incorrecta'),
          'provider'=>'table'
        ]
      ])
      ;
    
    return $validator;
  }
  
  /*funcion para impedir el registro de un nombre de usuario excluido*/
  public function excluido($value, $tipo_id){
    $exclusionesTable = TableRegistry::get('Exclusiones');
    $exclusion = $exclusionesTable->find('all', [
      'conditions'=>[
        'tipo_id'=>$tipo_id
      ]
    ])->toArray();
    
    foreach ($exclusion as $item){
      $itemLenght = strlen($item['valor']);
      
      if(substr($item['valor'], 0, 1)==='*' && substr($item['valor'], ($itemLenght - 1), 1)==='*'){ //* al ininio y al final
        $newItem = str_replace('*', '', $item['valor']); //quitar * del item
        
        $pos = stripos($value, $newItem); //encontrar posicion si hay coindicencia
        
        if($pos !==false){ //si existe posicion en cualquier lado
          return false;
        }
        
      }elseif(substr($item['valor'], 0, 1)==='*'){ //* al inicio
        $newItem = str_replace('*', '', $item['valor']); //quitar * del item
        
        //verificar si el item se encuentra en $value tomando los ultimos caracteres
        $pos = strripos($value, $newItem);
        
        if($pos !== false){ //si no es falso es porque se encuentra en $newItem
          return false;
        }
        
      }elseif(substr($item['valor'], ($itemLenght - 1), 1)==='*'){ //* al final
        $newItem = str_replace('*', '', $item['valor']); //quitar * del item
        
        $pos = stripos($value, $newItem); //encontrar posicion si hay coindicencia
        
        if($pos===0){ //la coincidencia debe ser desde el inicio
          return false;
        }
        
      }else{
        if($item['valor']===$value){
          return false;
        }
      }
    }
    
    return true;
  }
  
  
  /*funcion para validar registros de usuarios desde el administrador*/
  public function validationAdmin(Validator $validator){
    $usuarios_usuario_min_car = Configure::read('config')['usuarios_usuario_min_car'];
    $usuarios_usuario_max_car = Configure::read('config')['usuarios_usuario_max_car'];
    
    $usuarios_contrasena_min_car = Configure::read('config')['usuarios_contrasena_min_car'];
    $usuarios_contrasena_max_car = Configure::read('config')['usuarios_contrasena_max_car'];
    
    $validator
      //usuario
      ->notEmpty('usuario',__('El nombre de usuario es requerido'))      
      ->add('usuario',[
        'lenght'=>[
          'rule'=>['lengthBetween' , $usuarios_usuario_min_car, $usuarios_usuario_max_car],
          'message'=>__('El nombre de usuario debe ser entre '.$usuarios_usuario_min_car.' y '.$usuarios_usuario_max_car.' caracteres.')
        ],
        'unique'=>[
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('El nombre de usuario ya se encuentra utilizado')
        ],
        'numLetras'=>[
          'rule'=>['custom','/^[a-z0-9A-Z_-]*$/i'],
          'message' => __('Solo se permite letras, números y guíon (-_). Sin espacios.')
        ]        
      ])
      
      //correo
      ->notEmpty('correo',__('El correo electrónico es requerido'))   
      ->add('correo', [
        'valid-email'=>[
          'rule' => 'email',
          'message'=>__('Debe introducir un correo electrónico válido')
        ],
        'unico'=>[
          'rule'=>'validateUnique',
          'provider'=>'table',
          'message' => __('Este correo ya se encuentra utilizado')
        ]
      ])
      
      //contrasena
      ->notEmpty('contrasena',__('La contraseña es requerida'), ['on'=>'create'])
      ->add('contrasena', 'length',[
        'rule'=>['lengthBetween' , $usuarios_contrasena_min_car, $usuarios_contrasena_max_car],
        'message'=>__('La contraseña debe ser entre '.$usuarios_contrasena_min_car.' y '.$usuarios_contrasena_max_car.' caracteres.')
      ])
      ->notEmpty('rol_id',__('El rol es requerido'))
      ;

    return $validator;
  }
  
  
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
  
  
  /*verificar que la cuenta de correo no se encuentre ya activada*/
  public function verifActivada($valor, $tabla, $campo){
    
    $table = TableRegistry::get($tabla);
    
    $busqueda = $table->find('all',[
      'fields'=>['estado'],
      'conditions' => [
        $campo => $valor
      ]      
    ])->toArray();
        
    if($busqueda[0]['estado']!=0){
      return false;
    }
    
    return true;
  }
  
  /*funcion para validar reenvio de correo de confirmacion*/
  public function validationReenvioActivacion(Validator $validator){
    $validator
      ->notEmpty('correo',__('El correo electrónico es requerido')) 
      ->add('correo', [
        'valid-email'=>[
          'rule' => 'email',
          'message'=>__('Debe introducir un correo electrónico válido'),
          'last'=>true,
        ],
        'existe'=>[
          'rule'=>['verifExiste', 'Usuarios', 'correo'],
          'provider'=>'table',
          'last'=>true,
          'message' => __('Este correo electrónico no se encuentra registrado')
        ],
        'activada'=>[
          'rule'=>['verifActivada', 'Usuarios', 'correo'],
          'provider'=>'table',
          'message' => __('La cuenta asignada a este correo ya se encuentra activada')
        ]
      ]);
    
    return $validator;
  }
  
  /*funcion para validar login*/
  public function validationLoginSite(Validator $validator){
    $validator
      ->notEmpty('usuario',__('El usuario es requerido')) 
      ->notEmpty('contrasena',__('La contraseña es requerida'));
    
    return $validator;
  }
  
  public function verifContrasenaActual($value){
    $cookieUsuario = Configure::read('cookieUsuario');
    
    $usuariosTable = TableRegistry::get('Usuarios');
    $usuario = $usuariosTable->find('all', [
      'fields' => ['contrasena'],
      'conditions' => [
        'id' => $cookieUsuario['id']
      ]
    ])->first();
    
    if(\Cake\Utility\Security::hash($value, 'sha256', true) === $usuario['contrasena']){
      return true;
    }
    
    return false;
  }
}