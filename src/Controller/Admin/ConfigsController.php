<?php

namespace App\Controller\Admin;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\ForbiddenException;

class ConfigsController extends AppController{
  public $layout = 'admin';
  public $menu = 'menu-configuraciones';  
         
  public function index(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_config']) || $this->perRol['ver_panel_config']==0 || !isset($this->perRol['cambiar_config_sitio']) || $this->perRol['cambiar_config_sitio']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('configuracion_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);    
    $this->set('configuracion_sidebar_activo', 1);
    
    $configsTable = TableRegistry::get('Configs'); //get tabla   
    $configsEntity = $configsTable->newEntity();
    
    if($this->request->is('post')){      
      //array de campos que no pueden quedar vacios
      $arrayNoVacio = ['sitio_nombre', 'sitio_nombre_secundario', 'correo_administrador','sitio_buscador_opciones',
        'sitio_buscador_opcion_defecto', 'sitio_fecha_inicio', 'sitio_descripcion'];
      $opciones=$this->request->data; //guardar datos en opciones      
            
      //componente para guardar las configuraciones
      $setConfigs = $this->ConfigsComp->setConfigs($configsTable,$configsEntity, $opciones, $arrayNoVacio);  
      
      if($setConfigs==true){ //guardado con exito
        $this->Flash->success(__('Ajustes actualizados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'Configs',
          'action' => 'index'
        ]);
      }
    }
    
    $this->set(compact('configsEntity'));
  }
  
  public function deshabilitar(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_config']) || $this->perRol['ver_panel_config']==0 || !isset($this->perRol['deshabilitar_sitio']) || $this->perRol['deshabilitar_sitio']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('configuracion_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);    
    $this->set('deshabilitar_activo', 1);
    
    
    $configsTable = TableRegistry::get('Configs'); //get tabla   
    $configsEntity = $configsTable->newEntity();
    
    if($this->request->is('post')){      
      //array de campos que no pueden quedar vacios
      //$arrayNoVacio = ['sitio_nombre', 'sitio_nombre_secundario', 'correo_administrador'];
      $opciones=$this->request->data; //guardar datos en opciones      
            
      //componente para guardar las configuraciones
      $setConfigs = $this->ConfigsComp->setConfigs($configsTable,$configsEntity, $opciones);  
      
      if($setConfigs==true){ //guardado con exito
        $this->Flash->success(__('Ajustes actualizados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'Configs',
          'action' => 'deshabilitar'
        ]);
      }
    }
    
    $this->set(compact('configsEntity'));
  } 
  
  
  public function mantenimientoProgramado(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_config']) || $this->perRol['ver_panel_config']==0 || !isset($this->perRol['cambiar_config_sitio']) || $this->perRol['cambiar_config_sitio']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('configuracion_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);    
    $this->set('mantenimiento_activo', 1);
    
    $configsTable = TableRegistry::get('Configs'); //get tabla   
    $configsEntity = $configsTable->newEntity();
    
    if($this->request->is('post')){      
      //array de campos que no pueden quedar vacios
      $arrayNoVacio = ['mantenimiento_mensaje'];
      $opciones=$this->request->data; //guardar datos en opciones      
            
      //componente para guardar las configuraciones
      $setConfigs = $this->ConfigsComp->setConfigs($configsTable,$configsEntity, $opciones, $arrayNoVacio);  
      
      if($setConfigs==true){ //guardado con exito
        $this->Flash->success(__('Ajustes actualizados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'Configs',
          'action' => 'mantenimientoProgramado'
        ]);
      }
    }
    
    $this->set(compact('configsEntity'));
  }
  
  public function correo(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_config']) || $this->perRol['ver_panel_config']==0 || !isset($this->perRol['cambiar_ajustes_correo']) || $this->perRol['cambiar_ajustes_correo']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('configuracion_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);    
    $this->set('correo_activo', 1);
    
    
    $configsTable = TableRegistry::get('Configs'); //get tabla   
    $configsEntity = $configsTable->newEntity();
    
    if($this->request->is('post')){      
      //array de campos que no pueden quedar vacios
      $arrayNoVacio = ['correo_smtp_servidor', 'correo_smtp_puerto', 'correo_smtp_usuario','correo_smtp_contrasena'];
      $opciones=$this->request->data; //guardar datos en opciones        
      $opciones['correo_smtp_contrasena'] = base64_encode($opciones['correo_smtp_contrasena']);
            
      //componente para guardar las configuraciones
      $setConfigs = $this->ConfigsComp->setConfigs($configsTable,$configsEntity, $opciones, $arrayNoVacio);  
      
      if($setConfigs==true){ //guardado con exito
        $this->Flash->success(__('Ajustes actualizados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'Configs',
          'action' => 'correo'
        ]);
      }
    }
    
    $this->set(compact('configsEntity'));
  }
  
  
  public function usuarios(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_config']) || $this->perRol['ver_panel_config']==0 || !isset($this->perRol['cambiar_ajustes_usuario_registro']) || $this->perRol['cambiar_ajustes_usuario_registro']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('configuracion_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);    
    $this->set('usuarios_activo', 1);
    
    $configsTable = TableRegistry::get('Configs'); //get tabla   
    $configsEntity = $configsTable->newEntity();
    
    if($this->request->is('post')){      
      //array de campos que no pueden quedar vacios      
      $arrayNoVacio = ['usuarios_usuario_min_car', 'usuarios_usuario_max_car', 'usuarios_contrasena_min_car','usuarios_contrasena_max_car'];
      $opciones=$this->request->data; //guardar datos en opciones   
            
      //componente para guardar las configuraciones
      $setConfigs = $this->ConfigsComp->setConfigs($configsTable,$configsEntity, $opciones, $arrayNoVacio);  
      
      if($setConfigs==true){ //guardado con exito
        $this->Flash->success(__('Ajustes actualizados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'Configs',
          'action' => 'usuarios'
        ]);
      }
    }   
    
    //obtener lista de roles
    $rolesTable=  TableRegistry::get('Roles');
    $rol_id = $rolesTable->find('list', [
      'order'=>'Roles.nombre asc'
    ])->toArray();
    
    $this->set(compact('configsEntity','rol_id'));
  }
  
  
  public function administrador(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_config']) || $this->perRol['ver_panel_config']==0 || !isset($this->perRol['cambiar_ajustes_administrador']) || $this->perRol['cambiar_ajustes_administrador']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('configuracion_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);    
    $this->set('administrador_activo', 1);
    
    
    $configsTable = TableRegistry::get('Configs'); //get tabla   
    $configsEntity = $configsTable->newEntity();
    
    if($this->request->is('post')){      
      //array de campos que no pueden quedar vacios
      $arrayNoVacio = ['adm_usuarios_opciones_visibles', 'adm_usuarios_opcion_defecto', 'adm_categorias_opciones_visibles',
        'adm_categorias_opcion_defecto'];
      $opciones=$this->request->data; //guardar datos en opciones  
            
      //componente para guardar las configuraciones
      $setConfigs = $this->ConfigsComp->setConfigs($configsTable,$configsEntity, $opciones, $arrayNoVacio);  
      
      if($setConfigs==true){ //guardado con exito
        $this->Flash->success(__('Ajustes actualizados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'Configs',
          'action' => 'administrador'
        ]);
      }
    }
    
    $this->set(compact('configsEntity'));
  }
  
  
  public function negociosSucursales(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_config']) || $this->perRol['ver_panel_config']==0 || !isset($this->perRol['cambiar_ajustes_negocios_sucursales']) || $this->perRol['cambiar_ajustes_negocios_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('configuracion_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);    
    $this->set('negocios_sucursales_activo', 1);
    
    $configsTable = TableRegistry::get('Configs'); //get tabla   
    $configsEntity = $configsTable->newEntity();
    
    if($this->request->is('post')){      
      //array de campos que no pueden quedar vacios
      $arrayNoVacio = ['negocios_max_num_cat','negocios_cat_visibles', 'negocios_logo_max_altura', 
        'negocios_logo_max_ancho','negocios_logo_max_peso',
        'negocios_cant_imagenes', 'negocios_img_max_ancho','negocios_img_max_altura', 'negocios_img_max_peso',
        'negocios_sucursal_cant_imagenes','negocios_opciones_visibles', 'negocios_opcion_defecto',
        'negocios_url_facebook', 'negocios_url_twitter', 'negocios_url_google_plus', 'negocios_url_instagram',
        'negocios_zoom_marcador'];
      $opciones=$this->request->data; //guardar datos en opciones      
            
      //componente para guardar las configuraciones
      $setConfigs = $this->ConfigsComp->setConfigs($configsTable,$configsEntity, $opciones, $arrayNoVacio);  
      
      if($setConfigs==true){ //guardado con exito
        $this->Flash->success(__('Ajustes actualizados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'Configs',
          'action' => 'negociosSucursales'
        ]);
      }
    }
    
    $this->set(compact('configsEntity'));
  } 
  
  
  public function correoPrueba(){   
    $this->layout = 'ajax';
    $this->loadComponent('Correo');
    
    $resp = [
      'codigo'=>0,
      'mensaje'=>'En correo no pudo ser enviado.'
    ];
    if($this->request->is(['post', 'ajax'])){
      $correo = filter_input(INPUT_POST, 'correo');
      $configCorreo = $_POST['configs'];      
      
      $opciones = ['navegador' => $this->getNavegadorWeb(), 'ip'=>$this->request->clientIp()];
      
      if($this->Correo->correoPrueba($correo, $configCorreo, $opciones) == true){
        $resp = [
          'codigo'=>1,
          'mensaje' => 'El correo fue enviado.'
        ];
      }
      
    }
    
    $this->set(compact('resp'));    
  }
}
