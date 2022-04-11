<?php

namespace App\Controller\Admin;
use App\Controller\AppController;
use \Cake\ORM\TableRegistry;
use \Cake\Network\Exception\NotFoundException;
use \Cake\Datasource\Exception\RecordNotFoundException;
use \Cake\Network\Exception\ForbiddenException;

class RolesController extends AppController{
  public $layout = 'admin';
  public $menu = 'menu-usuarios';
  
  /*funcion para ver la lista de todos los roles*/
  public function index(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['administrar_roles']) || $this->perRol['administrar_roles']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('roles_activo', 1);
    
    $rolesTable = TableRegistry::get('Roles');    
    $roles = $rolesTable->find('all')->toArray();
    
    $dataEliminar = array();
    if($this->request->is('post')){ //solo existe la opcion de eliminar
      $dataEliminar = $this->request->data['seleccion']; //obtener lista seleccionada de roles
      
      $e = $rolesTable->deleteAll([ //eliminar seleccionados
        'id in '=>$dataEliminar,
        'puede_eliminarse' => 1
      ]);
                  
      if($e==true){ //evaluar si se eliminaros los registros
        $this->Flash->success(__('Los roles seleccionado han sido eliminados'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'roles',
          'action' => 'index'
        ]);
      }else{
        $this->Flash->error(__('Los roles seleccionados no han sido eliminados'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
      }
    }
    
    $this->set(compact('roles','debug','dataEliminar'));
    
  }
  
  //funcion para crear un nuevo rol
  public function nuevo(){    
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['administrar_roles']) || $this->perRol['administrar_roles']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('jquery_ui', 1);
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('roles_activo', 1);
    $debug = 0;
    
    $rolesTable = TableRegistry::get('Roles'); //iniciar tabla
    $rolesEntity = $rolesTable->newEntity(); //iniciar entidad
    
    $data=null;
    if($this->request->is('post')){
      $data = $this->request->data; //recuperar datos
      $data['permisos'] = serialize($data['permisos']); //serializar permisos
      
      $rolesEntity=$rolesTable->patchEntity($rolesEntity, $data); //convertir datos a entidad
      
      if($rolesTable->save($rolesEntity)){ //guardar
        $this->Flash->success(__('El rol ha sido guardado'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
      }else{
        $this->Flash->error(__('El rol no ha sido guardado'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
      }
      
      return $this->redirect([
        'prefix' => 'admin',
        'controller' => 'roles',
        'action' => 'index'
      ]);
    }
    
    $permisos_array = $this->permisos_array;
    $this->set(compact('rolesEntity','data','debug', 'permisos_array'));    
  }
  
  /*funcion para editar un rol*/
  public function editar($rol_id=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['administrar_roles']) || $this->perRol['administrar_roles']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    if($rol_id==null){ //sin rol id disparar error
      throw new NotFoundException($this->getMensajeError(404));
    }
    $this->set('usuarios_activo', 1);
    $debug = 0;
    $this->set('jquery_ui', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('roles_activo', 1);
    
    
    $rolesTable = TableRegistry::get('Roles'); //iniciar tabla   
    
    try{
      $rolesEntity = $rolesTable->get($rol_id); //buscar los datos del rol_id  
      $rol = $rolesEntity->toArray(); //convetir datos a un arreglo     
    } catch (RecordNotFoundException $ex) {
      throw new NotFoundException($this->getMensajeError(404)); //excepcion
    }
    
    $dataSave=null; //variable para guardar los datos a la base
    $data=array(); //variable para enviar datos al formulario en caso de error
    if($this->request->is(['post','put'])){
      $data = $dataSave = $this->request->data; //recuperar datos
      $dataSave['id'] = $rol_id; //asignar rol id      
      $dataSave['permisos'] = serialize($dataSave['permisos']); //serializar permisos   
      
      $rolesEntity = $rolesTable->patchEntity($rolesEntity, $dataSave); //convertir a entidad
      
      if($rolesTable->save($rolesEntity)){ //guardar
        $this->Flash->success(__('El rol ha sido actualizado'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        //comentado solo en pruebas
        /*return $this->redirect([
          'prefix' => 'admin',
          'controller' => 'roles',
          'action' => 'index'
        ]);*/
        
        return $this->redirect([
          'action'=>'editar',
          $rol_id
        ]);
        
      }else{
        $this->Flash->error(__('El rol no ha sido actualizado'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
      }
    }
    
    $permisos_array = $this->permisos_array;
    $rol = $this->deserializarRol($rol); //deserializar arreglo de permisos
    $this->set(compact('rolesEntity','rol','data','debug', 'permisos_array'));
  }
  
  
  /*funcion para ver los permisos de un rol*/
  public function ver($rol_id=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['administrar_roles']) || $this->perRol['administrar_roles']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    if($rol_id==null){ //sin rol id disparar error
      throw new NotFoundException($this->getMensajeError(404));
    }
    $debug = 0;
    $this->set('usuarios_activo', 1);
    $this->set('jquery_ui', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('roles_activo', 1);
    
    
    $rolesTable = TableRegistry::get('Roles'); //iniciar tabla
        
    
    try{
      $rol = $rolesTable->get($rol_id)->toArray();
      $rol = $this->deserializarRol($rol);
    } catch (RecordNotFoundException $ex) {
      throw new NotFoundException($this->getMensajeError(404));
    }
    
    $permisos_array = $this->permisos_array;
    $this->set(compact('rolesEntity','rol','debug', 'permisos_array', 'rol_id'));
  }
}

