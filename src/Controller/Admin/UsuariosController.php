<?php

namespace App\Controller\Admin;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use \Cake\Network\Exception\NotFoundException;
use \Cake\I18n\Time;
use \App\Model\Entity\Usuario;
use Cake\Routing\Router;
use Cake\Network\Exception\ForbiddenException;
use \Cake\Filesystem\Folder;

class UsuariosController extends AppController{
  public $layout = 'admin';
  public $menu = 'menu-usuarios';  
  
  public function login(){
    $this->layout = 'Admin/login';
    $this->set('titulo',__('Login administrador'));
    
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
                $this->Flash->error(__('Usuario no activado'), [
                  'key' => 'error',
                  'params'=>[
                    'html'=>true
                  ]
                ]);                

                return $this->redirect(['action'=>'login']);
                break;
              
              case 1://cuenta activa  
              
                $permisos = $this->getRol($datos['rol_id']); //obtener permisos del rol
                
                if($permisos['acceso_admin']==1){
                  //escribir cookie
                  $this->Cookie->configKey('UsuarioAdmin', 'expires' ,0); //al cerrar navegador
                  $this->Cookie->write('UsuarioAdmin', [
                    'id' => $datos['id'],
                    'rol_id' => $datos['rol_id'],
                    'usuario' => $datos['usuario']
                  ]);
                  
                  //si cookie de usuario normal no existe entonces escribirla                  
                  if(!$this->Cookie->check('Usuario')){                    
                    $this->Cookie->configKey('Usuario', 'expires' ,0); //al cerrar navegador
                    $this->Cookie->write('Usuario', [
                      'id' => $datos['id'],
                      'rol_id' => $datos['rol_id'],
                      'usuario' => $datos['usuario']
                    ]);
                  }


                  $url = Router::url([
                    'prefix' => 'admin',
                    'controller' => 'General',
                    'action'=>'index'
                  ],true);

                  /*url a la que intento entrar antes de estar logueado*/
                  if($this->Cookie->check('url')){
                    $url = $this->Cookie->read('url');
                    $this->Cookie->delete('url'); //borrar url guardada
                  }

                  return $this->redirect($url); 
                }else{
                  //mensaje de error
                  $this->Flash->error(__('Permisos insuficientes para acceder'), [
                    'key' => 'error',
                    /*'params'=>[
                      'html'=>true
                    ]*/
                  ]);                

                  return $this->redirect(['action'=>'login']);
                }
                               
                break; 
              
              case 2: //cuenta suspendida
                //mensaje de error
                $this->Flash->error(__('Usuario suspendido hasta el '.
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
          $this->Flash->error(__('Usuario no registrado'), [
            'key' => 'error'
          ]);
          
          return $this->redirect(['action'=>'login']);
        }
      }
    }
    
    $this->set(compact('debug','data','usuario','datos'));
  }
         
  //funcion para ver la lista de usuarios
  public function index($limite = null, $rol_id = -1, $txtUsuario = null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_usuarios']) || $this->perRol['admin_usuarios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    $this->set('jquery_ui',1);
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('usuarios_lista_activo', 1);
    
    /*evitar limite cero o letras*/    
    if  ($limite != null) {
      $limite = (int) $limite;
      if ($limite<1) {
        return $this->redirect([
          'action' => 'index'
        ]);
      }
    }
    /*fin limite cero o letras*/
    
                
    $usuariosTable = TableRegistry::get('Usuarios');   
    $data=array();    
    if($this->request->is('post')){
      $data = $this->request->data;  
      $limite = $data['limite']; //limite de usuarios a mostrar
      
      /****************************inicio suspender o eliminar usuarios*********************************/
      $aplicarAccion = 0; //bandera que determina si se ejecuta una accion
      
      if(isset($data['btnAplicarAccion'])){//aplicar una accion de boton superior
        $aplicarAccion=1;
        
      }elseif(isset($data['btnAplicarAccion2'])){ //aplicar una accion de boton inferior
        $data['accion'] = $data['accion2'];
        $aplicarAccion=1;
        
      }elseif(isset($data['btnAplicarAccion3'])){ //boton de suspension
        $aplicarAccion=1;
        if(isset($data['accion2']) && $data['accion2']>0){
          $data['accion'] = $data['accion2'];
        }
      }
      
      $dataSuspension = array();
      //en caso de suspension crear arreglo de datos
      if(isset($data['btnAplicarAccion3'])){
        
        $dataSuspension= [
          'estado' => $data['accion'],
          'fecha_suspension' => new \DateTime('now'),
          'razon_suspension' => $data['razon_suspension'],
          'fecha_termina_suspension' => new \DateTime($data['fecha_termina_suspension'])
        ];
      }else{ //si no es suspension eliminar los datos de $data
        unset($data['razon_suspension']);
        unset($data['fecha_termina_suspension']);
      }
            
      if($aplicarAccion==1){ //aplicar una accion
        
        if(isset($data['seleccion'])){ //verificar si hay seleccion de registros 
                
          $seleccion = (isset($data['seleccion'])) ? $data['seleccion']: array(); //seleccion de registros

          if($data['accion']==2){ //suspender usuarios            
            //actualizar los datos  
            $a = $usuariosTable->updateAll($dataSuspension, [
              'id in' =>$seleccion,
              'puede_eliminarse'=>1
            ]);          

          }elseif($data['accion']==3){ //accion de eliminar usuarios
            
            $usuarios = $usuariosTable->find('all', [
              'conditions'=>[
                'Usuarios.id in'=>$seleccion,
                'Usuarios.puede_eliminarse'=>1
              ],
              'contain'=>['Negocios']
            ])->toArray();
            
            
            foreach ($usuarios as $usuario){
              //eliminar las carpetas de las imagenes de los negocios
              foreach ($usuario->negocios as $negocio) {
                $negocio_id = $negocio->id;
                $folder = new Folder();
                $folder->delete('img/neg/' . $negocio_id);
              }
              
              if($usuariosTable->delete($usuario)){ //eliminar usuario
                $a = true;
              }else{
                $a = false;
              }
            }
            
          }

          if ($a == true) { //evaluar si fue aplicado
            $this->Flash->success(__('La acción ha sido aplicada'), [
              'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);

          } else { //no se pudo eliminar ningun usuario
            $this->Flash->error(__('La acción no ha sido aplicada'), [
              //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);
          }        
        }else{ //sin registros seleccionados
          $this->Flash->error(__('No ha seleccionado registros'), [
            //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }
      }
      /******************************fin suspender o eliminar usuarios*************************************/
      
      
      /*****************************inicio cambiar usuario de rol*****************************************/      
      if(isset($data['btnAplicarRol'])){
        if (isset($data['seleccion'])){
          $seleccion = (isset($data['seleccion'])) ? $data['seleccion']: array(); //seleccion de registros

          $a = $usuariosTable->updateAll([ //actualizar el rol en los registros
              'rol_id' => $data['rol_id']
            ], [
              'id in' => $seleccion,
              'puede_eliminarse' => 1
          ]);

          if ($a == true) { //evaluar si fue aplicado
            $this->Flash->success(__('El rol ha sido aplicado'), [
              'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);

          } else { //no se pudo aplicar rol a ningun usuario
            $this->Flash->error(__('El rol no ha sido aplicado'), [
              //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);
          }
        }else{ //no ha seleccionado registros
          $this->Flash->error(__('No ha seleccionado registros'), [
            //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }
      }      
      /********************************fin cambiar usuario de rol*****************************************/
      
      /*******************************inicio buscar usuario************************************************/
      
      if(isset($data['btnBuscarUsuario'])){
        $txtUsuario = $data['txtUsuario'];    
        return $this->redirect([
          'action' => 'index',
          $limite, $rol_id, $txtUsuario
        ]);
      }
      
      /*******************************fin buscar usuario***************************************************/
                  
      //redireccionar pagina con limite, rol y otros parametros de paginacion
      if(isset($data['pag1'])){
        $this->request->query['page'] = $data['pag1'];
      }else{
        $this->request->query['page'] = $data['pag2'];
      }
      
      //deshabilitar para pruebas
      return $this->redirect([        
        'action'=>'index',
        $limite, $rol_id, $txtUsuario,
        '?' => $this->request->query
      ]);
    } /****************fin post******************/
    
    //sustituir limite por opcion por defecto si es null
    if($limite==null){
      $limite = $this->config['adm_usuarios_opcion_defecto'];
    }
    
    /*condiciones para consulta*/
    $condiciones = array();    
    
    if(is_numeric($rol_id) && $rol_id>0){ //condicion para filtrar resultados por tipo de rol    
      $condiciones[] = ['Usuarios.rol_id'=>$rol_id];
    }
    
    if($txtUsuario!=null){ //si en la url existe el parametro para buscar usuario se agrega la condicion a paginacion
      $condiciones[] = [
        'OR' =>[
          'Usuarios.usuario like '=>'%'.$txtUsuario.'%',
          'Usuarios.correo like '=>'%'.$txtUsuario.'%']];
    }
    
    //opciones de paginacion
    $this->paginate = [
      'fields'=>['Usuarios.id', 'Usuarios.rol_id', 'Usuarios.usuario', 'Usuarios.correo', 'Usuarios.estado', 
        'Usuarios.fecha_registro', 'Roles.nombre'],
      'conditions'=>[
        'Usuarios.estado'=>1,
        $condiciones
      ],
      'order'=>['Usuarios.fecha_registro'=>'desc'],
      'sortWhitelist'=>['Usuarios.usuario','Usuarios.correo','Roles.nombre','Usuarios.fecha_registro'],
      'limit'=>$limite
    ];
    
    try {
      //buscar los datos
      $usuarios = $this->paginate($usuariosTable->find('all')->contain(['Roles', 'Negocios']))->toArray();  
      
      
    } catch (NotFoundException $ex) {
      /* si los datos no existen para esa pagina intentar buscar en la pagina anterior */
      $query = $this->request->query;
      $query['page'] = $query['page'] -1;
      
      //redirigir a pagina anterior
      return $this->redirect([        
        'action'=>'index',
        $limite, $rol_id, $txtUsuario,
        '?' => $query
      ]);
    }
    
    $condicionRoles=array(); //condicion para contador de usuarios por roles
    if($txtUsuario!=null){
      //condicion para contador de roles
      $condicionRoles = [
        'OR' =>[
          'Usuarios.usuario like '=>'%'.$txtUsuario.'%',
          'Usuarios.correo like '=>'%'.$txtUsuario.'%']];
    }
    
    //usuarios totales
    $usuariosTotal = $usuariosTable->find('all',[
      'conditions'=>[
        'Usuarios.estado'=>1,
        $condicionRoles
      ]
    ])->count();
    
    //lista de roles para select
    $rolesTable = TableRegistry::get('Roles'); 
    $listaRoles = $rolesTable->find('list',['order'=>'Roles.nombre asc']);
    
        
    //contador de roles
    $countRoles = $rolesTable->find('all', [      
      'order'=>'Roles.nombre asc'
    ])->contain([
      'Usuarios'=> function($q) use ($condicionRoles){ //retornar solo usuarios activos
        return $q
          ->select(['Usuarios.rol_id', 'Usuarios.fecha_registro', 'Usuarios.fecha_suspension', 'Usuarios.fecha_termina_suspension'])
          ->where([
            'Usuarios.estado'=>1,
            $condicionRoles
        ]);
      }
    ])->toArray();
            
    //opciones seleccionables de paginacion en la vista
    $verOpciones = $this->getVerOpciones($this->config['adm_usuarios_opciones_visibles']); 
    $this->set(compact('usuarios','debug','limite','rol_id','data','verOpciones','usuariosTotal','listaRoles','countRoles', 'txtUsuario'));
  }
  
  /*funcion para agregar un nuevo usuario*/
  public function nuevo(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_usuarios']) || $this->perRol['admin_usuarios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    $this->loadComponent('Correo');
    $this->set('usuarios_lista_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('usuarios_activo', 1);
    
    $data=null;
    $usuariosTable = TableRegistry::get('Usuarios');    
    $usuario = $usuariosTable->newEntity();
    
    if($this->request->is('post')){ //inicio post
      $data = $this->request->data;     
      
      if($data['estado']==0){
        $token = $this->generarToken(); //generar token
        $data = array_merge($data, $token); //agregar token a arreglo data
      }else{
        $data['token'] = null;
      }      
            
      $usuario=$usuariosTable->patchEntity($usuario, $data, ['validate'=>'admin']);
      
      if($usuariosTable->save($usuario)){
        $this->Flash->success(_('El usuario ha sido guardado'),[
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
        
        if($data['notificar_correo']==1){ //bandera para reconocer si se enviara notificacion al usuario de su cuenta creada
          //enviar correo de activacion
          $usuario_id = $usuario->id;
          $this->Correo->notificarCreacionUsuarioDesdeAdmin($usuario_id, $data['correo'], $data['token'], 
            $data['usuario'], $data['contrasena'], $data['estado']
          );
        }        
        
        return $this->redirect(['action'=>'nuevo']);
      }else{
        $this->Flash->error(_('El usuario no ha sido guardado'));
      }
      
    }    
    /***************fin post****************/
    
    //lista de roles para select
    $rolesTable = TableRegistry::get('Roles'); 
    $listaRoles = $rolesTable->find('list',['order'=>'Roles.nombre asc']);
    
    $this->set(compact('usuario','debug','data','listaRoles'));
    
  }
  
  /*funcion para editar un usuario*/
  public function editar($usuario_id=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_usuarios']) || $this->perRol['admin_usuarios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    if($usuario_id==null){ //sin rol id disparar error
      throw new NotFoundException($this->getMensajeError(404));
    }
    
    $debug = 0;
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('usuarios_activo', 1);
    
    $usuariosTable = TableRegistry::get('Usuarios');
    
    //quitar regla de contrasena no vacia
    $usuariosTable->validator('default')->allowEmpty('contrasena');
    
    try{
      $usuario = $usuariosTable->get($usuario_id, [
        'contain'=>[
          'Negocios'
        ]
      ]); //buscar los datos del usuario_id  
      //$usuario = $usuariosEntity->toArray(); //convetir datos a un arreglo     
    } catch (RecordNotFoundException $ex) {
      throw new NotFoundException($this->getMensajeError(404)); //excepcion
    }
    
    //menu lateral a marcar como activo
    switch ($usuario->estado) {
      case 0:
        $this->set('usuarios_inactivos_activo', 1);
        break;
      case 1:
        $this->set('usuarios_lista_activo', 1);
        break;
      case 2:
        $this->set('usuarios_suspendidos_activo', 1);
        break;
      default:
        break;
    }
    
    $data=null;
    if($this->request->is(['post','put'])){
      $data = $this->request->data;
      
      if($usuario->estado == 1){ //si es un usuario habilitado
        if(!isset($data['estado'])){ //verificar si se va a suspender
          unset($data['razon_suspension']);
          unset($data['fecha_termina_suspension']);
        }else{
          $data['fecha_suspension'] = new \DateTime('now'); //agregar fecha de suspension
          $data['fecha_termina_suspension'] = new \DateTime($data['fecha_termina_suspension']);
        }
      }elseif($usuario->estado==2){ //si es un usuario suspendido
        if($data['fecha_termina_suspension']==''){ //si no cambia la fecha en que termina la suspension
          unset($data['fecha_termina_suspension']);
        }else{
          $data['fecha_termina_suspension'] = new \DateTime($data['fecha_termina_suspension']);
        }
        
        if(isset($data['estado'])){ //limpiar fecha y razon de suspension en caso de habilitar (checkbox marcado)
          $data['razon_suspension']=NULL;
          $data['fecha_termina_suspension']=NULL;
          $data['fecha_suspension']=NULL;
        }
      }
      
      if($data['contrasena']==''){ //unset contrasena si esta vacia
        unset($data['contrasena']);
      }
            
      $usuario = $usuariosTable->patchEntity($usuario, $data,['validate'=>'admin']);
      if($usuariosTable->save($usuario)){
        $this->Flash->success(_('El usuario ha sido actualizado'),[
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
        
        return $this->redirect(['action'=>'editar',$usuario_id]);
      }else{
        $this->Flash->error(_('El usuario no ha sido actualizado'));
      }
    }
    
    //lista de roles para select
    $rolesTable = TableRegistry::get('Roles'); 
    $listaRoles = $rolesTable->find('list',['order'=>'Roles.nombre asc']);
    
    $this->set(compact('debug','data','usuario','listaRoles'));
  }
  
  /*funcion para listar los usuarios inactivos*/
  public function inactivos($limite = null, $txtUsuario = null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_usuarios']) || $this->perRol['admin_usuarios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('usuarios_inactivos_activo', 1);
    
    /*evitar limite cero o letras*/    
    if  ($limite != null) {
      $limite = (int) $limite;
      if ($limite<1) {
        return $this->redirect([
          'action' => 'index'
        ]);
      }
    }
    /*fin limite cero o letras*/
    
                
    $usuariosTable = TableRegistry::get('Usuarios');   
    $data=array();    
    if($this->request->is('post')){
      $data = $this->request->data;  
      $limite = $data['limite']; //limite de usuarios a mostrar
      
      /****************************inicio activar o eliminar usuarios*********************************/
      $aplicarAccion = 0; //bandera que determina si se aplico una accion
      if(isset($data['btnAplicarAccion'])){ //aplicar una accion de boton superior
        $aplicarAccion=1;
      }elseif(isset($data['btnAplicarAccion2'])){ //aplicar una accion de boton inferior
        $data['accion'] = $data['accion2'];
        $aplicarAccion=1;
      }
            
      if($aplicarAccion==1){ //aplicar una accion        
        if(isset($data['seleccion'])){        
          $seleccion = (isset($data['seleccion'])) ? $data['seleccion']: array(); //seleccion de registros

          if($data['accion']==1){ //activar usuarios
            $a = $usuariosTable->updateAll([ //actualizar los datos
              'estado' => $data['accion']            
            ], [
              'id in' =>$seleccion,
              'puede_eliminarse'=>1
            ]);          

          }elseif($data['accion']==3){ //accion de eliminar            
            
            $usuarios = $usuariosTable->find('all', [
              'conditions'=>[
                'Usuarios.id in'=>$seleccion,
                'Usuarios.puede_eliminarse'=>1
              ],
              'contain'=>['Negocios']
            ])->toArray();
            
            foreach ($usuarios as $usuario){              
              if($usuariosTable->delete($usuario)){ //eliminar usuario
                $a = true;
              }else{
                $a = false;
              }
            }
            
          }

          if ($a == true) { //evaluar si fue aplicado
            $this->Flash->success(__('La acción ha sido aplicada'), [
              'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);

          } else { //la accion no fue aplicada
            $this->Flash->error(__('La acción no ha sido aplicada'), [
              //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);
          }        
        }else{ //no se seleccionaron registros
          $this->Flash->error(__('No ha seleccionado registros'), [
            //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }        
      }
      /******************************fin activar o eliminar usuarios*************************************/  
            
      /*******************************inicio buscar usuario************************************************/
      
      if(isset($data['btnBuscarUsuario'])){ //accion de buscar usuarios
        $txtUsuario = $data['txtUsuario'];
        return $this->redirect([
          'action' => 'inactivos',
          $limite, $txtUsuario
        ]);
      }
      
      /*******************************fin buscar usuario***************************************************/
                  
      //redireccionar pagina con limite, rol y otros parametros de paginacion
      if(isset($data['pag1'])){
        $this->request->query['page'] = $data['pag1'];
      }else{
        $this->request->query['page'] = $data['pag2'];
      }
      
      //comentar para pruebas
      return $this->redirect([        
        'action'=>'inactivos',
        $limite, $txtUsuario,
        '?' => $this->request->query
      ]);
    } /****************fin post******************/
    
    //sustituir limite por opcion por defecto si es null
    if($limite==null){
      $limite = $this->config['adm_usuarios_opcion_defecto'];
    }
    
    /*condiciones para consulta con busqueda de usuarios*/
    $condiciones = array();      
    if($txtUsuario!=null){
      $condiciones[] = [
        'OR' =>[
          'Usuarios.usuario like '=>'%'.$txtUsuario.'%',
          'Usuarios.correo like '=>'%'.$txtUsuario.'%']];
    }
    
    //opciones de paginacion
    $this->paginate = [
      'fields'=>['Usuarios.id', 'Usuarios.rol_id', 'Usuarios.usuario', 'Usuarios.correo', 'Usuarios.estado', 
        'Usuarios.fecha_registro'],
      'conditions'=>[
        'Usuarios.estado'=>0,
        $condiciones
      ],
      'order'=>['Usuarios.fecha_registro'=>'desc'],
      'sortWhitelist'=>['Usuarios.usuario','Usuarios.correo','Roles.nombre','Usuarios.fecha_registro'],
      'limit'=>$limite
    ];
    
    try {
      //buscar los datos
      $usuarios = $this->paginate($usuariosTable->find('all'))->toArray(); 
      
      //contador
      $countUsuarios = $usuariosTable->find('all', [
        'conditions'=>[
          'Usuarios.estado'=>0,
          $condiciones
        ]
      ])->count();
      
    } catch (NotFoundException $ex) {
      /* si los datos no existen para esa pagina intentar buscar en la pagina anterior */
      $query = $this->request->query;
      $query['page'] = $query['page'] -1;
      
      //redirigir a pagina anterior
      return $this->redirect([        
        'action'=>'index',
        $limite, $txtUsuario,
        '?' => $query
      ]);
    }
    
    //opciones seleccionables de paginacion o limite en la vista
    $verOpciones = $this->getVerOpciones($this->config['adm_usuarios_opciones_visibles']);  
    $this->set(compact('usuarios','debug','limite','data','verOpciones','usuariosTotal','txtUsuario', 'countUsuarios'));
  }
  
  
  //lista de usuarios suspendidos
  public function suspendidos($limite = null, $txtUsuario = null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_usuarios']) || $this->perRol['admin_usuarios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('usuarios_suspendidos_activo', 1);
    
    /*evitar limite cero o letras*/    
    if  ($limite != null) {
      $limite = (int) $limite;
      if ($limite<1) {
        return $this->redirect([
          'action' => 'index'
        ]);
      }
    }
    /*fin limite cero o letras*/
    
                
    $usuariosTable = TableRegistry::get('Usuarios');   
    $data=array();    
    if($this->request->is('post')){
      $data = $this->request->data;  
      $limite = $data['limite']; //limite de usuarios a mostrar
      
      /****************************inicio activar o eliminar usuarios*********************************/
      $aplicarAccion = 0;
      if(isset($data['btnAplicarAccion'])){ //aplicar una accion de boton superior
        $aplicarAccion=1;
      }elseif(isset($data['btnAplicarAccion2'])){ //aplicar una accion de boton inferior
        $data['accion'] = $data['accion2'];
        $aplicarAccion=1;
      }
            
      if($aplicarAccion==1){ //aplicar una accion
        
        if(isset($data['seleccion'])){        
          $seleccion = (isset($data['seleccion'])) ? $data['seleccion']: array(); //seleccion de registros

          if($data['accion']==1){ //habilitar usuarios
            $a = $usuariosTable->updateAll([ //actualizar los datos
              'estado' => $data['accion'],  
              'fecha_suspension'=>NULL,
              'razon_suspension'=>NULL,
              'fecha_termina_suspension'=>NULL
            ], [
              'id in' =>$seleccion,
              'puede_eliminarse'=>1
            ]);          

          }elseif($data['accion']==3){ //accion de eliminar
            
            $usuarios = $usuariosTable->find('all', [
              'conditions'=>[
                'Usuarios.id in'=>$seleccion,
                'Usuarios.puede_eliminarse'=>1
              ],
              'contain'=>['Negocios']
            ])->toArray();
            
            
            foreach ($usuarios as $usuario){
              //eliminar las carpetas de las imagenes de los negocios
              foreach ($usuario->negocios as $negocio) {
                $negocio_id = $negocio->id;
                $folder = new Folder();
                $folder->delete('img/neg/' . $negocio_id);
              }
              
              if($usuariosTable->delete($usuario)){ //eliminar usuario
                $a = true;
              }else{
                $a = false;
              }
            }
          }

          if ($a == true) { //evaluar si fue aplicado
            $this->Flash->success(__('La acción ha sido aplicada'), [
              'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);

          } else { //error
            $this->Flash->error(__('La acción no ha sido aplicada'), [
              //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);
          }
        }
      }
      /******************************fin activar o eliminar usuarios*************************************/
            
      /*******************************inicio buscar usuario************************************************/
      
      if(isset($data['btnBuscarUsuario'])){ //accion para buscar usuarios
        $txtUsuario = $data['txtUsuario'];    
        return $this->redirect([
          'action' => 'suspendidos',
          $limite, $txtUsuario
        ]);
      }
      
      /*******************************fin buscar usuario***************************************************/
                  
      //redireccionar pagina con limite, rol y otros parametros de paginacion
      if(isset($data['pag1'])){
        $this->request->query['page'] = $data['pag1'];
      }else{
        $this->request->query['page'] = $data['pag2'];
      }
      
      //comentar para pruebas
      return $this->redirect([        
        'action'=>'suspendidos',
        $limite, $txtUsuario,
        '?' => $this->request->query
      ]);
    } /****************fin post******************/
    
    //sustituir limite por pocion por defecto si es null
    if($limite==null){
      $limite = $this->config['adm_usuarios_opcion_defecto'];
    }
    
    /*condiciones para consulta*/
    $condiciones = array();      
    if($txtUsuario!=null){ //condicion en busqueda de usuarios
      $condiciones[] = [
        'OR' =>[
          'Usuarios.usuario like '=>'%'.$txtUsuario.'%',
          'Usuarios.correo like '=>'%'.$txtUsuario.'%']];
    }
    
    //opciones de paginacion
    $this->paginate = [
      'fields'=>['Usuarios.id', 'Usuarios.rol_id', 'Usuarios.usuario', 'Usuarios.correo', 'Usuarios.estado', 
        'Usuarios.fecha_suspension','Usuarios.razon_suspension', 'Usuarios.fecha_termina_suspension'],
      'conditions'=>[
        'Usuarios.estado'=>2,
        $condiciones
      ],
      'order'=>['Usuarios.usuario'=>'asc'],
      'sortWhitelist'=>[
        'Usuarios.usuario','Usuarios.correo','Usuarios.fecha_suspension','Usuarios.razon_suspension',
        'Usuarios.fecha_termina_suspension'
      ],
      'limit'=>$limite
    ];
    
    try {
      //buscar los datos
      $usuarios = $this->paginate($usuariosTable->find('all'))->toArray();  
      
      //contador
      $countUsuarios = $usuariosTable->find('all', [
        'conditions'=>[
          'Usuarios.estado'=>2,
          $condiciones
        ]
      ])->count();
      
    } catch (NotFoundException $ex) {
      /* si los datos no existen para esa pagina intentar buscar en la pagina anterior */
      $query = $this->request->query;
      $query['page'] = $query['page'] -1;
      
      //redirigir a pagina anterior
      return $this->redirect([        
        'action'=>'index',
        $limite, $txtUsuario,
        '?' => $query
      ]);
    }
    
    //opciones seleccionables de paginacion en la vista
    $verOpciones = $this->getVerOpciones($this->config['adm_usuarios_opciones_visibles']);  
    $this->set(compact('usuarios','debug','limite','data','verOpciones','usuariosTotal','txtUsuario', 'countUsuarios'));
  }
  
  //funcion para excluir email para utilizar en registros nuevos
  public function excluirEmail(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_exclu_correo']) || $this->perRol['admin_exclu_correo']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('excluir_email_activo', 1);
    
    $exclusionesTable = TableRegistry::get('Exclusiones');    
    $exclusion = $exclusionesTable->newEntity();
    
    $data=null;
    if($this->request->is('post')){
      $data = $this->request->data;
      $data['tipo_id']=1; //exclusion de correo
      
      $exclusion = $exclusionesTable->patchEntity($exclusion, $data);
      
      if ($exclusionesTable->save($exclusion)) {
        $this->Flash->success(_('La exclusión ha sido guardada'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect(['action' => 'excluirEmail']);
        
      } else {
        $this->Flash->error(_('La exclusión no ha sido guardada'));
      }
    }
    
    $exclusiones = $exclusionesTable->find('all', [
      'conditions'=>[
        'Exclusiones.tipo_id'=>1
      ]
    ]);

    $this->set(compact('exclusion','debug','data','exclusiones'));
  }
  
  //eliminar exclusiones de la base de datos
  public function eliminarExclusion($exclusion_id=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    $this->layout = 'ajax';
    $this->autoRender = false;
    
    if($this->request->is('post')){
      $exclusionesTable = TableRegistry::get('Exclusiones');    
      $exclusion = $exclusionesTable->get($exclusion_id);
      
      if($exclusionesTable->delete($exclusion)){
        $this->Flash->success(_('La exclusión ha sido eliminada'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
      }else{
        $this->Flash->error(_('La exclusión no ha sido eliminada'));
      }
    }
    
    return $this->redirect($this->referer());
  }
  
  //funcion para excluir direcciones ip
  public function excluirIp(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_exclu_ip']) || $this->perRol['admin_exclu_ip']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('excluir_ip_activo', 1);
    
    $exclusionesTable = TableRegistry::get('Exclusiones');    
    $exclusion = $exclusionesTable->newEntity();
    
    $data=null;
    if($this->request->is('post')){
      $data = $this->request->data;
      $data['tipo_id']=2; //exclusion de ip
      
      $exclusion = $exclusionesTable->patchEntity($exclusion, $data);
      
      if ($exclusionesTable->save($exclusion)) {
        $this->Flash->success(_('La exclusión ha sido guardada'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect(['action' => 'excluirIp']);
        
      } else {
        $this->Flash->error(_('La exclusión no ha sido guardada'));
      }
    }
    
    $exclusiones = $exclusionesTable->find('all', [
      'conditions'=>[
        'Exclusiones.tipo_id'=>2
      ]
    ]);

    $this->set(compact('exclusion','debug','data','exclusiones'));
  }
  
  //funcion para excluir nombres de usuario
  public function excluirUsuarios(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_exclu_nom_usua']) || $this->perRol['admin_exclu_nom_usua']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $debug = 0;
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('excluir_usuarios_activo', 1);
    
    $exclusionesTable = TableRegistry::get('Exclusiones');    
    $exclusion = $exclusionesTable->newEntity();
    
    $data=null;
    if($this->request->is('post')){
      $data = $this->request->data;
      $data['tipo_id']=3; //exclusion de usuarios
      
      $exclusion = $exclusionesTable->patchEntity($exclusion, $data);
      
      if ($exclusionesTable->save($exclusion)) {
        $this->Flash->success(_('La exclusión ha sido guardada'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

        return $this->redirect(['action' => 'excluirUsuarios']);
        
      } else {
        $this->Flash->error(_('La exclusión no ha sido guardada'));
      }
    }
    
    $exclusiones = $exclusionesTable->find('all', [
      'conditions'=>[
        'Exclusiones.tipo_id'=>3
      ]
    ]);

    $this->set(compact('exclusion','debug','data','exclusiones'));
  }
  
  //function para obtener el select con las fechas para hacer una suspension
  public function getFechasSuspension($estado=null){
    $this->layout = 'ajax';
    
    /*opciones de fecha*/
    $opcionesFecha = [
      (string) date_format(new Time('+1 day'),'Y-m-d H:i') => __('+1 d&iacute;a (').date_format(new Time('+1 day'),'d-m-Y H:i').')',
      (string) date_format(new Time('+2 day'),'Y-m-d H:i') => __('+2 d&iacute;as (').date_format(new Time('+2 day'),'d-m-Y H:i').')',
      (string) date_format(new Time('+3 day'),'Y-m-d H:i') => __('+3 d&iacute;as (').date_format(new Time('+3 day'),'d-m-Y H:i').')',      
      (string) date_format(new Time('+1 week'),'Y-m-d H:i') => __('+1 semana (').date_format(new Time('+1 week'),'d-m-Y H:i').')',
      (string) date_format(new Time('+2 week'),'Y-m-d H:i') => __('+2 semanas (').date_format(new Time('+2 week'),'d-m-Y H:i').')',
      (string) date_format(new Time('+1 month'),'Y-m-d H:i') => __('+1 mes (').date_format(new Time('+1 month'),'d-m-Y H:i').')',
      (string) date_format(new Time('+2 month'),'Y-m-d H:i') => __('+2 meses (').date_format(new Time('+2 month'),'d-m-Y H:i').')',
      (string) date_format(new Time('+1 year'),'Y-m-d H:i') => __('+1 a&ntilde;o (').date_format(new Time('+1 year'),'d-m-Y H:i').')',
    ];
    
    
    if($estado==2){ //estado de suspendido, agrega opcion para no modificar la actual fecha de suspension
      $opcionesFecha=[''=>__('Sin modificar')] + $opcionesFecha;
    }
    
    $this->set(compact('opcionesFecha'));
  }
  
  /*funcion para cerrar sesion en el sitio*/
  public function logout (){
    $this->autoRender = false;
    
    $this->Cookie->delete('UsuarioAdmin'); //eliminar la cookie
    return $this->redirect([
      'prefix' => false,
      'controller' => 'Principal',
      'action' => 'index'
    ]);
  }
  
  
  //funcion para ver la lista de usuarios ingresados en la tabla de notificaciones
  public function notificacionesUsuarios(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_usua_roles']) || $this->perRol['ver_panel_usua_roles']==0 || !isset($this->perRol['admin_usuarios']) || $this->perRol['admin_usuarios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
            
    $debug = 0;
    //$this->set('jquery_ui',1);
    $this->set('usuarios_activo', 1);
    //sidebar
    $this->set('menu_admin', $this->menu);
    $this->set('usuarios_notificaciones_activo', 1);
    
    $notificacionesUsuariosTable = TableRegistry::get('NotificacionesUsuarios');
    
    //opciones de paginacion
    $this->paginate = [      
      'order'=>['fecha_insert'=>'desc'],
      'sortWhitelist'=>['Usuarios.usuario', 'tipo_notificacion','Usuarios.correo','fecha_registro','fecha_insert'],
      'limit'=>1000
    ];
    
    $notificacionesUsuarios = $this->paginate(
      $notificacionesUsuariosTable->find('all', ['contain'=>['Usuarios']])
    )->toArray();
    
    /*Contadores*/
    $countNotificacionesUsuarios = count($notificacionesUsuarios);
    
    $countUsuariosSinNegocio = $notificacionesUsuariosTable->find('all', [
      'conditions'=>[
        'tipo_notificacion'=>'usuario-sin-negocio'
      ]
    ])->count();
    
    $countUsuariosSinActivar = $notificacionesUsuariosTable->find('all', [
      'conditions'=>[
        'tipo_notificacion'=>'usuario-sin-activar'
      ]
    ])->count();
    
    /*FIN contadores*/
    
    $this->set(compact('debug', 'notificacionesUsuarios', 'countNotificacionesUsuarios', 'countUsuariosSinNegocio', 
      'countUsuariosSinActivar'));
    
  }
}

