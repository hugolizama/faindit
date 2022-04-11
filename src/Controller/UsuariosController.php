<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Model\Entity\Usuario;
use Cake\Routing\Router;
use \Cake\Datasource\Exception\RecordNotFoundException;
use \Cake\Filesystem\Folder;
use \Cake\Core\Configure;

class UsuariosController extends AppController{  
  
  /*registro de un nuevo usuario*/
  public function registro(){    
    if($this->siLogueadoNo()){ //redireccionar si la cookie esta establecida
      return $this->redirect($this->referer());
    }
    
    $formularioDesactivado = 0;
    if(isset($this->config['usuarios_deshabilitar_registros']) && $this->config['usuarios_deshabilitar_registros']==1){
      $formularioDesactivado = 1;
    }
    
    $this->loadComponent('Correo');
    $this->set('titulo',__('Registro'));
    $this->set('facebookPixelCode', 1); //Activar pixel de facebook
            
    $debug = 0;
    $data=null;
    $usuariosTable = TableRegistry::get('Usuarios');    
    $usuario = $usuariosTable->newEntity();
    
    if($this->request->is('post')){      
      $data = $this->request->data;
      
      $captcha = $data['g-recaptcha-response']; //contenido del captcha
      //verificar capacha
      $arrContextOptions = array(
        "ssl" => array(
          "verify_peer" => false,
          "verify_peer_name" => false,
        ),
      );

      $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeI6Q8TAAAAAD110uX7L6RICOk3ewBbxH8ifHTc&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR'], false, stream_context_create($arrContextOptions));
      $obj = json_decode($response);
      
      if($obj->success==true){ //captcha verificado con exito
        
        $data['rol_id'] = $this->config['usuarios_rol_id_defecto'];      

        $token = $this->generarToken(); //generar token
        $data = array_merge($data, $token); //agregar token a arreglo data

        $usuario = $usuariosTable->patchEntity($usuario, $data);

        if($usuariosTable->save($usuario)){
          $usuario_id = $usuario->id;

          //enviar correo de activacion
          $this->Correo->activarCuenta($usuario_id, $data['correo'], $data['token']);

          return $this->redirect([
            'prefix'=>false,
            'controller'=>'Usuarios',
            'action'=>'registroExito'
          ]);
        }
        
      }else{
        $this->Flash->error(__('El captcha no fue validado exitosamente.'));
      }
      
    }
    
    $this->set(compact('usuario','debug','data', 'formularioDesactivado'));     
  }
  
  /*mensaje de exito para registro*/
  public function registroExito(){
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
    
    //evitar entrar directamente en la pagina de registro exitoso
    if($this->referer()!=Router::url(array('action'=>'registro'), true)){
      return $this->redirect($this->referer());
    }    
    
    $this->set('titulo',__('Registro'));
    $this->set('facebookPixelCode', 1); //Activar pixel de facebook
  }
  
  /*funcion para iniciar sesion en el sitio*/
  public function login(){    
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
        
    $this->set('titulo',__('Iniciar Sesi&oacute;n'));
    $debug = 0;
       
    $usuariosTable = TableRegistry::get('Usuarios');
    $usuario = $usuariosTable->newEntity();
    
    $data = null;
    $datos=null;
    if($this->request->is('post')){
      $data = $this->request->data;
      $usuario = $usuariosTable->patchEntity($usuario, $data, ['validate' => 'loginSite']);
      
      if(!$usuario->errors()){
        //buscar datos en la base
        $datos = $usuariosTable->find('all', [
          'conditions'=> [
            'or' => [
              'usuario'=>$data['usuario'],
              'correo' =>$data['usuario']
            ]
          ]
        ])->first();
        
        
        if(!empty($datos)){
          
          //verificar contrasena
          if(Usuario::checkContrasena($data['contrasena'], $datos['contrasena'])){ //comparar contrasenas
            switch ($datos['estado']) { //verificar estado del usuario
              case 0: //cuenta no activada
                //mensaje de error
                $this->Flash->error(__('Esta cuenta no se encuentra activada. 
                    Puede solicitar un código de activación en el 
                    <a href="'.Router::url(['action'=>'reenviarActivacion']).'" style="color:white; text-decoration: underline">Reenv&iacute;o de confirmaci&oacute;n de cuenta</a>'), [
                  'key' => 'error',
                  'params'=>[
                    'html'=>true
                  ]
                ]);                

                return $this->redirect(['action'=>'login']);
                break;
              
              case 1://cuenta activa                
                
                if($data['recordar']==1){ //establecer tiempo de vida de la cookie                  
                  $this->Cookie->configKey('Usuario', 'expires' ,'+365 days');
                }else{
                  $this->Cookie->configKey('Usuario', 'expires' ,0); //al cerrar navegador
                }
                
                //escribir cookie
                $this->Cookie->write('Usuario', [
                  'id' => $datos['id'],
                  'rol_id' => $datos['rol_id'],
                  'usuario' => $datos['usuario']
                ]);
                
                
                $url = Router::url([
                  'prefix' => false,
                  'controller' => 'Usuarios',
                  'action'=>'perfil'
                ],true);
                
                /*url a la que intento entrar antes de estar logueado*/
                if($this->Cookie->check('url')){
                  $url = $this->Cookie->read('url');
                  $this->Cookie->delete('url'); //borrar url guardada
                }
                
                return $this->redirect($url);                
                break; 
              
              case 2: //cuenta suspendida
                //mensaje de error
                $this->Flash->error(__('Esta cuenta se encuentra suspendida hasta el '.
                    $datos['fechaTerminaSuspensionFormat'].'. Razón de suspensión: '.$datos['razon_suspension']), [
                  'key' => 'error',
                  /*'params'=>[
                    'html'=>true
                  ]*/
                ]);                

                return $this->redirect(['action'=>'login']);
                break;

              default:
                break;
            }
          }else{ //contrasena no coincide
            $this->Flash->error(__('La contraseña es incorrecta'), [
              'key' => 'error'
            ]);

            return $this->redirect(['action'=>'login']);
          }
          
        }else{ //no existe usuario
          $this->Flash->error(__('Este usuario no se encuentra en nuestros registros'), [
            'key' => 'error'
          ]);
          
          return $this->redirect(['action'=>'login']);
        }
      }
    }
    
    $this->set(compact('debug','data','usuario','datos'));
  }
  
  /*funcion para cerrar sesion en el sitio*/
  public function logout (){
    $this->autoRender = false;
    
    $this->Cookie->delete('Usuario'); //eliminar la cookie
    return $this->redirect([
      'prefix' => false,
      'controller' => 'Principal',
      'action' => 'index'
    ]);
  }
  
  /*funcion para solicitar restablecimiento de contrasena*/
  public function restablecerContrasena(){    
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
    
    $this->loadComponent('Correo');
    $this->set('titulo',__('Restablecer contrase&ntilde;a'));
    $debug = 0;
        
    $data = null;
    $usuarioDatos = null;
    $usuariosTable = TableRegistry::get('Usuarios');
    $usuario = $usuariosTable->newEntity();
    
    if($this->request->is('post')){
      $data = $this->request->data;
      
      //quitar validacion de cuenta activada
      $usuariosTable->validator('reenvioActivacion')->remove('correo', 'activada');
      
      //utilizar validacion de reenvioActivacion
      $usuario = $usuariosTable->patchEntity($usuario, $data, ['validate'=>'reenvioActivacion']);
      
      if(!$usuario->errors()){
        $usuarioDatos = $usuariosTable->find('all', [
          'fields' =>['id'],
          'conditions' => [
            'correo' => $data['correo']
          ]
        ])->first();
        
        $token = $this->generarToken();
        
        //actualizar registro con token de verificacion
        $a = $usuariosTable->updateAll($token, [
          'id' => $usuarioDatos['id']
        ]);
        
        if($a==true){  //enviar correo de recuperacion de contrasena                    
          $opciones = ['navegador' => $this->getNavegadorWeb(), 'ip'=>$this->request->clientIp()];
          $this->Correo->restablecerContrasena($usuarioDatos['id'], $data['correo'], $token['token'], $opciones);
          
          return $this->redirect([
            'prefix' => false,
            'controller' => 'Usuarios',
            'action' => 'restablecerContrasenaExito'
          ]);
        }
      }
    }
    
    $this->set(compact('data','debug', 'usuario', 'usuarioDatos'));
  }
  
  /*menssaje de correo enviado para restablecer contrasena*/
  public function restablecerContrasenaExito(){
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
    
    $this->set('titulo',__('Restablecer contrase&ntilde;a enviada'));
  }
  
  /*funcion para solicitar correo de activacion de cuenta*/
  public function reenviarActivacion(){    
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
    
    $this->loadComponent('Correo');
    $this->set('titulo',__('Reenviar activaci&oacute;n'));
    $debug = 0;
    
    $data = null;
    $usuariosTable = TableRegistry::get('Usuarios');
    $usuario = $usuariosTable->newEntity();
    
    if($this->request->is('post')){
      $data = $this->request->data;
      
      $usuario = $usuariosTable->patchEntity($usuario, $data, ['validate' => 'reenvioActivacion']);
      
      if(!$usuario->errors()){ //validar por errores        
        //buscar id del usuario
        $datos = $usuariosTable->find('all', [
          'fields'=>['id'],
          'conditions'=>[
            'correo'=>$data['correo']
          ]
        ])->toArray();
        
        $usuario_id = $datos[0]['id'];
                        
        $token = $this->generarToken();
        
        //actualizar token en la base
        $u = $usuariosTable->updateAll($token, [
          'id' => $usuario_id
        ]);
        
        if($u == true){ //si se actualiza mandar correo para activar cuenta
          $this->Correo->activarCuenta($usuario_id, $data['correo'], $token['token']);
          
          return $this->redirect([
            'prefix' => false,
            'controller' => 'Usuarios',
            'action' => 'reenviarActivacionExito'
          ]);
        }
      }
      
    }
    
    $this->set(compact('data','debug', 'usuario'));
  }
  
  /*funcion de mensaje de exito*/
  public function reenviarActivacionExito(){
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
    
    $this->set('titulo',__('Confirmaci&oacute;n enviada'));
  }
  
  /*funcion para visualizar el perfil general del usuario*/
  public function perfil(){    
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    $this->set('titulo',__('Perfil de usuario'));
    $debug = 0;
    
    $usuariosTable = TableRegistry::get('Usuarios');
    $usuario = $usuariosTable->get($this->cookieUsuario['id']);
         
    $data = null;
    if($this->request->is(['post', 'put'])){
      $data = $this->request->data;
      
      $usuario = $usuariosTable->patchEntity($usuario, $data); //validar datos
      
      /*Los formulario de modificar datos generales del perfil y cambio de contrasena son independientes
       * por lo que es seguro hace un solo save y luego verificar que boton fue el que ejecuta el post
       */
      if ($usuariosTable->save($usuario)) { //guardar datos
        if (isset($data['btnGeneral'])) { //guardar datos generales   
          $this->Flash->success(__('Datos actualizados'), [
            'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        } elseif (isset($data['btnContrasena'])) { //cambio de contrasena  
          $this->Flash->success(__('Contraseña actualizada'), [
            'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }

        return $this->redirect([
          'prefix' => false,
          'controller' => 'Usuarios',
          'action' => 'perfil'
        ]);
      }
    }
    
   
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para el menu lateral
    $tokenFalso = $this->generarTokenFalso(); //genera un token falso  
    
    $this->set(compact('debug','data', 'usuario', 'listaNegocios','tokenFalso'));
  }
  
  /*funcion para activar una cuenta desde el enlace del correo electronico*/
  public function activarCuenta($usuario_id = null, $token = null){
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
    
    $this->set('titulo',__('Activaci&oacute;n de cuenta'));
    $debug = 0;
    $activacion = 0; //activacion invalida
    
    $usuariosTable = TableRegistry::get('Usuarios');
    
    try{
      //busca los datos del id
      $datos = $usuariosTable->find('all', [
        'fields'=>['estado', 'token'],
        'conditions'=>[
          'id'=>$usuario_id
        ]
      ])->toArray();
      
      
      if(!empty($datos)){ //verificar si hay datos
        $datos = $datos[0];
        
        if($datos['estado']==0){ //estado 0 es cuenta no activada
          if($datos['token'] == $token){ //comparar token
            
            $a = $usuariosTable->updateAll([ //actualizar estado y limpiar token
              'estado'=>1,
              'fecha_token'=>NULL,
              'token'=>NULL
            ], [
              'id'=>$usuario_id
            ]);
            
            if($a == true){
              $activacion = 1; //cuenta lista para habilitar
            }else{
              //problema con la actualizacion de datos, mostrar mensaje de error
              $activacion = -1; 
              $this->Flash->error(__('Su cuenta no ha sido activada, por favor intente nuevamente haciendo clic en el siguiente enlace: '));
            }
            
            
          }
        }else{
          $activacion = 2; //cuenta ya verificada
        }
      }
      
    } catch (RecordNotFoundException $ex) {
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    }    
    $this->set(compact('activacion', 'debug'));
  }
  
  
  /*funcion de la accion para restablecer la contrasena del usuario desde el enlace de correo electronico*/
  public function restableciendoContrasena($usuario_id=null, $token = null){
    if($this->siLogueadoNo()){
      return $this->redirect($this->referer());
    }
    
    $this->set('titulo',__('Restableciendo contrase&ntilde;a'));
    $debug = 0;
        
    $restablecer = 0; //codigo invalido
    
    $usuariosTable = TableRegistry::get('Usuarios');    
    try{
      $usuario = $usuariosTable->get($usuario_id);
      
    } catch (RecordNotFoundException $ex) {
      $usuario = $usuariosTable->newEntity();
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } 
    
    $data=null;
    if($this->request->is(['post','put'])){
      $data = $this->request->data;          
      $data['fecha_token'] = NULL;
      $data['token'] = NULL;
      
      $usuario = $usuariosTable->patchEntity($usuario, $data);   
      
      if($usuariosTable->save($usuario)){
        $this->Flash->success(__('Su contraseña ha sido cambiada con éxito.'));
        return $this->redirect([
          'prefix' => false,
          'controller' => 'Usuarios',
          'action' => 'login'
        ]);
      }else{
        $usuario['fecha_token']=$usuario->getOriginal('fecha_token');
        $usuario['token']=$usuario->getOriginal('token');
      }
    }    
    
    if($usuario['token']==$token){
      //evaluar diferencia de fechas
      $fecha_hoy = strtotime('now');
      $fecha_obj = \DateTime::createFromFormat('d/m/Y H:i:s', date_format($usuario['fecha_token'], 'd/m/Y H:i:s'));
      $fecha_token = strtotime($fecha_obj->format('Y-m-d H:i:s')); 
      $fecha_resta = ($fecha_hoy - $fecha_token) / 60 / 60 ; //resultado en horas

      //verificar reglas. Datos no vacio. Tiempo de token menos de 24 horas. Token coinciden
      if(!is_null($usuario) && $fecha_resta < 24){  
       $restablecer = 1; //mostrar formulario
      }    
    }
    
    $this->set(compact('debug','data','datos', 'usuario', 'restablecer'));
  }
  
  //funcion para eliminar una cuenta de usuario
  public function eliminarCuenta(){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    $this->set('titulo',__('Eliminar mi cuenta'));
    $debug = 0;
    
    $usuariosTable = TableRegistry::get('Usuarios');
    $usuario = $usuariosTable->get($this->cookieUsuario['id'], [
      'contain'=>['Negocios']
    ]);
    
    $data=null;
    if ($usuario->puede_eliminarse == 1) { //verificar si el usuario es eliminable
      if ($this->request->is(['post', 'put'])) {
        $data = $this->request->data;

        $usuario = $usuariosTable->patchEntity($usuario, $data);

        if (!$usuario->errors()) {
          //recorrer cada uno de los negocios para eliminar su carpeta de imagenes
          foreach ($usuario->negocios as $negocio) {
            $negocio_id = $negocio->id;
            $folder = new Folder();
            $folder->delete('img/neg/' . $negocio_id);
          }
          
          //eliminar usuario y toda su informacion asociada: negocios, sucursales, imagenes, etc.
          if($usuariosTable->delete($usuario)){
            $this->Cookie->delete('Usuario'); //eliminar cookie
            
            return $this->redirect([
              'prefix' => false,
              'controller' => 'Usuarios',
              'action' => 'eliminarCuentaExito'
            ]);
          }
        }
      }
    } else {
      $this->Flash->error(__('Este usuario no puede ser eliminado'));
    }
    $this->set(compact('debug', 'data', 'usuario'));
  }
  
  /*funcion para mensaje de cuenta eliminada*/
  public function eliminarCuentaExito(){
    $this->set('titulo',__('Cuenta eliminada'));
  }

}

