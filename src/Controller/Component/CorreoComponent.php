<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Email\Email;
use \Cake\Core\Configure;
use \Cake\Network\Exception\SocketException;

class CorreoComponent extends Component{
  
  public $components = ['Flash'];

  //funcion para obtener las configuraciones del correo electronico
  protected function emailConfig() {
    $emailConfig = array();
    $config = Configure::read('config');
    
    $emailConfig['host'] = $config['correo_smtp_servidor'];
    $emailConfig['port'] = (int)$config['correo_smtp_puerto'];
    $emailConfig['username']=$config['correo_smtp_usuario'];
    $emailConfig['password']= base64_decode($config['correo_smtp_contrasena']);
    $emailConfig['className']='Smtp'; //Cambiar entre Smtp para produccion o Debug para pruebas de depuracion
    $emailConfig['timeout'] = 30;
    
    
    //ssl - tls - nada
    if($config['correo_smtp_encript']=='ssl://'){
      $emailConfig['host'] =$config['correo_smtp_encript'].$emailConfig['host'];
      
    }elseif($config['correo_smtp_encript']=='tls://'){
      $emailConfig['tls'] = true;
    }
    
    /*Desactivar verificacion ssl*/
    $emailConfig['context']= [
      'ssl' => [
        'verify_peer' => false, 
        'verify_peer_name' => false, 
        'allow_self_signed' => true
      ]
    ];
        
    Email::configTransport('mail', $emailConfig); //configurar email
  }
  
  
  //funcion para obtener las configuraciones del correo electronico para PRUEBAS y DEBUG
  protected function emailConfigTest() {
    $emailConfig = array();
    $config = Configure::read('config');
    
    $emailConfig['host'] = $config['correo_smtp_servidor'];
    $emailConfig['port'] = (int)$config['correo_smtp_puerto'];
    $emailConfig['username']=$config['correo_smtp_usuario'];
    $emailConfig['password']= base64_decode($config['correo_smtp_contrasena']);
    $emailConfig['className']='Debug'; //Cambiar entre Smtp para produccion o Debug para pruebas de depuracion
    $emailConfig['timeout'] = 30;
    
    
    //ssl - tls - nada
    if($config['correo_smtp_encript']=='ssl://'){
      $emailConfig['host'] =$config['correo_smtp_encript'].$emailConfig['host'];
      
    }elseif($config['correo_smtp_encript']=='tls://'){
      $emailConfig['tls'] = true;
    }
    
    /*Desactivar verificacion ssl*/
    $emailConfig['context']= [
      'ssl' => [
        'verify_peer' => false, 
        'verify_peer_name' => false, 
        'allow_self_signed' => true
      ]
    ];
        
    Email::configTransport('mailTest', $emailConfig); //configurar email
  }
  
  
  /*correo para activacion de cuenta*/
  public function activarCuenta($usuario_id=null, $para=null, $token=null){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('activar_cuenta')
        ->emailFormat('html')
        ->to($para)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Activación de cuenta en ').$sitio_nombre_secundario)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'usuario_id'=>$usuario_id,
          'token'=>$token,
          'sitio_nombre' => $sitio_nombre
        ])        
        ->send();
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }    
  }
  
  
  /*correo para restablecer contrasena*/
  public function restablecerContrasena($usuario_id=null, $para=null, $token=null, $opciones=null){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('restablecer_contrasena')
        ->emailFormat('html')
        ->to($para)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Solicitud para restablecer contraseña en ').$sitio_nombre_secundario)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'usuario_id'=>$usuario_id,
          'token'=>$token,
          'navegador' => $opciones['navegador'],
          'ip' => $opciones['ip'],
          'sitio_nombre' => $sitio_nombre
        ])        
        ->send();
      
      
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }    
  }
  
  
  
  /*Funcion para enviar mensaje de correo a traves de los formularios de contacto*/
  public function contactoNegocio($correoPara=null, $nombreDe=null, $correoDe=null, $mensaje=null){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/    
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('contacto_negocio')
        ->emailFormat('html')
        ->to($correoPara)
        ->from($correoDe, $nombreDe)
        ->replyTo($correoDe, $nombreDe)
        ->subject(__('Mensaje de contacto desde ').$sitio_nombre_secundario)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,          
          'sitio_nombre' => $sitio_nombre,
          'nombreDe'=>$nombreDe,
          'correoDe'=>$correoDe,
          'mensaje'=>$mensaje
        ])        
        ->send();
      
    } catch(SocketException $ex){  
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
      return false;
      
    } catch (Exception $ex) {
      return false;
    }   
    
    return true;
  }
  
  
  /*Funcion para enviar mensaje de correo a traves de los formularios de contacto*/
  public function notificarSugerenciaCategoria($correoPara=null, $nombrePara=null, $tipoAprobacion=1, $categoria=null){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    }    
    
    $config = Configure::read('config');
    
    /*variables de correo*/    
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    $correoDe = $config['correo_smtp_usuario'];
    /*fin variables de correo*/
    
    if($tipoAprobacion==1){
      $template = 'sugerencia_categoria_aprobada';
    }
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template($template)
        ->emailFormat('html')
        ->to($correoPara)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Notificación de ').$sitio_nombre_secundario)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,          
          'sitio_nombre' => $sitio_nombre,
          'nombrePara'=>$nombrePara,
          'categoria'=>$categoria
        ])        
        ->send();
      
    } catch(SocketException $ex){   
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
      return false;
      
    } catch (Exception $ex) {
      return false;
    }   
    
    return true;
  }
  
  
  
  /*Funcion para enviar mensaje de correo a traves de los formularios de contacto*/
  public function contactoNosotros($nombreDe=null, $correoDe=null, $asunto=null, $mensaje=null){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/    
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    $sitio_correo_contacto = $config['sitio_correo_contacto'];
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('contacto_nosotros')
        ->emailFormat('html')
        ->to($sitio_correo_contacto)
        ->from($correoDe, $nombreDe)
        ->replyTo($correoDe, $nombreDe)
        ->subject('Contacto '.$sitio_nombre.": ".$asunto)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,          
          'sitio_nombre' => $sitio_nombre,
          'nombreDe'=>$nombreDe,
          'correoDe'=>$correoDe,
          'mensaje'=>$mensaje
        ])        
        ->send();
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
      return false;
      
    } catch (Exception $ex) {
      return false;
    }   
    
    return true;
  }
  
  
  /*Funcion para enviar comentarios del sitio web*/
  public function comentariosNosotros($nombreDe=null, $correoDe=null, $mensaje=null){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/    
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    //$sitio_correo_contacto = $config['sitio_correo_contacto'];
    $sitio_correo_contacto = $config['sitio_correo_administrador'];
    $sitio_correo_notificacion = $config['sitio_correo_notificacion'];
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('comentarios_nosotros')
        ->emailFormat('html')
        ->to($sitio_correo_contacto)
        ->from($sitio_correo_notificacion)
        //->replyTo($correoDe, $nombreDe)
        ->subject('Comentario '.$sitio_nombre)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,          
          'sitio_nombre' => $sitio_nombre,
          'nombreDe'=>$nombreDe,
          'correoDe'=>$correoDe,
          'mensaje'=>$mensaje
        ])        
        ->send();
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
      return false;
      
    } catch (Exception $ex) {
      return false;
    }   
    
    return true;
  }
  
  /*correo para a usuarios que aun no han registrado un negocio*/
  public function usuariosSinNegocio($para=null){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('usuarios_sin_negocio')
        ->emailFormat('html')
        ->bcc($para)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Recordatorio de ').$sitio_nombre_secundario)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'sitio_nombre' => $sitio_nombre
        ])
        ->send();
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }    
  }
  
  /*correo para a usuarios que aun no han registrado un negocio*/
  public function usuariosSinActivar($usuario_id=null, $usuario=null, $para=null, $token=null){
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('usuarios_sin_activar')
        ->emailFormat('html')
        ->to($para)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Activación de cuenta en ').$sitio_nombre_secundario)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'usuario'=>$usuario,
          'usuario_id'=>$usuario_id,
          'token'=>$token,
          'sitio_nombre' => $sitio_nombre
        ])        
        ->send();
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }   
  }
  
  
  
  /*correo para notificar la creacion de un usuario desde el administrador*/
  public function notificarCreacionUsuarioDesdeAdmin($usuario_id=null, $para=null, $token=null, $usuario=null, 
    $contrasena=null, $estado=null ){ 
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('usuario_creado_desde_admin')
        ->emailFormat('html')
        ->to($para)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Su usuario ha sido creado en ').$sitio_nombre_secundario)
        ->viewVars([
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'usuario_id'=>$usuario_id,
          'token'=>$token,
          'sitio_nombre' => $sitio_nombre,
          'usuario' => $usuario,
          'correo' => $para,
          'contrasena' => $contrasena,
          'estado' => $estado
        ])        
        ->send();
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }    
  }
  
  
  /*correo para notificar a un usuario que su negocio ha sido suspendido*/
  public function notificarNegocioSuspendido($usuario=null, $para=null, $token=null, $negocio_id=null, 
    $negocio_nombre=null, $razon_deshabilitado=null){
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $responderA = $config['sitio_correo_contacto'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('notificar_negocio_suspendido')
        ->emailFormat('html')
        ->to($para)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->replyTo($responderA)
        ->bcc('admin@faindit.com')
        ->subject(__('Notificación de ').$sitio_nombre_secundario)
        ->viewVars([          
          'usuario'=>$usuario,
          'token'=>$token,
          'negocio_id'=>$negocio_id,
          'negocio_nombre'=>$negocio_nombre,
          'razon_deshabilitado'=>$razon_deshabilitado,
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'sitio_nombre' => $sitio_nombre
        ])        
        ->send();
      
      //debug($correo);
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }   
  }
  
    
  
  /*correo para notificar a un usuario que su negocio ha sido rehabilitado*/
  public function notificarNegocioRehabilitado($usuario=null, $para=null, $token=null, $negocio_id=null, 
    $negocio_nombre=null){
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('notificar_negocio_rehabilitado')
        ->emailFormat('html')
        ->to($para)
        ->bcc('admin@faindit.com')
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Notificación de ').$sitio_nombre_secundario)
        ->viewVars([          
          'usuario'=>$usuario,
          'token'=>$token,
          'negocio_id'=>$negocio_id,
          'negocio_nombre'=>$negocio_nombre,
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'sitio_nombre' => $sitio_nombre
        ])        
        ->send();
      
      //debug($correo);
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }   
  }
  
   
  /*correo para notificar a un administrador que un negocio suspendido ha sido modificado para revision*/
  public function notificarAdminNegocioSuspendido($negocio_id=null, $negocio_nombre=null, $razon_deshabilitado=null){
    
    if(empty(Email::configTransport('mail'))){
      $this->emailConfig();
    } 
    
    $config = Configure::read('config');
    
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $para = $config['sitio_correo_administrador'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('mail')              
        ->template('admin/notificar_negocio_suspendido_modificado')
        ->emailFormat('html')
        ->to($para)
        ->from($correoDe, __('Notificación de '.$sitio_nombre))
        ->subject(__('Negocio deshabilitado modificado en ').$sitio_nombre_secundario)
        ->viewVars([          
          'negocio_id'=>$negocio_id,
          'negocio_nombre'=>$negocio_nombre,
          'razon_deshabilitado'=>$razon_deshabilitado,
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'sitio_nombre' => $sitio_nombre
        ])        
        ->send();
      
      //debug($correo);
      
    } catch(SocketException $ex){     
      if(Configure::read('debug')===true){
        $this->Flash->error($ex->getMessage());
      }
    } catch (Exception $ex) {

    }   
  }
  
  
  
  
  
  
  /********************************PRUEBAS**************************************/
  public function correoPrueba($para=null, $configCorreo = array(), $opciones = array()){ 
    
    /*Configuracion de correo para prueba*/
    $emailConfig = array();
    
    
    $emailConfig['host'] = $configCorreo['servidor'];
    $emailConfig['port'] = (int)$configCorreo['puerto'];
    $emailConfig['username']=$configCorreo['usuario'];
    $emailConfig['password']= $configCorreo['contrasena'];
    $emailConfig['className']='Smtp'; //Cambiar entre Smtp para produccion o Debug para pruebas de depuracion
    $emailConfig['timeout'] = 30;
    
    
    //ssl - tls - nada
    if($configCorreo['encript']=='ssl://'){
      $emailConfig['host'] =$configCorreo['encript'].$emailConfig['host'];
      
    }elseif($configCorreo['encript']=='tls://'){
      $emailConfig['tls'] = true;
    }
    
    /*Desactivar verificacion ssl*/
    $emailConfig['context']= [
      'ssl' => [
        'verify_peer' => false, 
        'verify_peer_name' => false, 
        'allow_self_signed' => true
      ]
    ];
    
            
    Email::configTransport('prueba_correo', $emailConfig); //configurar email
    /*fin configuracion correo prueba*/
    
    
    
    $config = Configure::read('config');
    /*variables de correo*/
    $correoDe = $config['correo_smtp_usuario'];
    $sitio_nombre_secundario = $config['sitio_nombre_secundario'];    
    $sitio_nombre = $config['sitio_nombre'];  
    /*fin variables de correo*/
    
    
    /*enviar correo*/  
    try{
      $correo = new Email();
      $correo
        ->transport('prueba_correo')              
        ->template('correo_prueba')
        ->emailFormat('html')
        ->to($para)
        ->from($correoDe, __($sitio_nombre))
        ->subject(__('Correo de prueba de ').$sitio_nombre_secundario)
        ->viewVars([          
          'sitio_nombre_secundario'=>$sitio_nombre_secundario,
          'sitio_nombre' => $sitio_nombre,
          'ops'=>$opciones
        ])        
        ->send();
      
    } catch(SocketException $ex){     
      return false;
    } catch (Exception $ex) {
      return false;
    }   
    
    return true;
  }
  
}
