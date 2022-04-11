<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Admin;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Filesystem\Folder;

/**
 * CakePHP NegociosSucursalesController
 * @author hugo lizama
 */
class NegociosSucursalesController extends AppController {
  public $layout = 'admin';
  public $menu = 'menu-negocios-sucursales';
  
  
  public function index($limite = null, $txtNegocio = 'null', $txtUsuario = 'null'){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);
    $debug = 0;
    
    $negociosTable = TableRegistry::get('Negocios');   
    $data=array();    
    if($this->request->is('post')){
      $data = $this->request->data;  
      $limite = $data['limite']; //limite de usuarios a mostrar
      
      
      /*proceso para aplicar accion en los negocios*/
      if(isset($data['btnAplicarAccion']) || isset($data['btnAplicarAccion2']) || isset($data['btnAplicarAccion3'])){
        if(isset($data['btnAplicarAccion2'])){ //asignar accion 2 a la accion principal
          $data['accion'] = $data['accion2'];
        }
        
        //verificar clic en boton para suspender y asignar accion
        if(isset($data['btnAplicarAccion3']) && $data['accion']=='-1'){
          $data['accion'] = $data['accion2'];
        }
        
        if(isset($data['seleccion'])){
          
          switch ($data['accion']) {
            case 0: //deshabilitar
              $this->loadComponent('Correo');
              
              $data['admin_deshabilitado_id'] = $this->cookieUsuarioAdmin['id'];
              $data['fecha_deshabilitado'] = new \DateTime('now');
              $data['admin_habilitado'] = $data['accion'];
              
              $entidades = $negociosTable->find('all', [
                'contain'=>['Usuarios'],
                'conditions'=>['Negocios.id in' => $data['seleccion']]
              ])->toArray();
              
                          
              foreach ($entidades as $entidad){
                $negociosTable->patchEntity($entidad, $data, ['validate'=>false]); //actualizar entidades  
                
                if($negociosTable->save($entidad)){
                  $this->Correo->notificarNegocioSuspendido(
                    $entidad['usuario']['usuario'], $entidad['usuario']['correo'], $this->generarTokenFalso(),
                    $entidad['id'], $entidad['nombre'], $entidad['razon_deshabilitado']
                  );
                }
              }  
              
              $this->Flash->success(__('La acción ha sido aplicada'), [
                'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
              ]);
              
              break;
              
            case 1: //habilitar
              $this->loadComponent('Correo');
              
              $data['admin_habilitado'] = $data['accion'];
              $data['razon_deshabilitado'] = NULL;
              $data['admin_deshabilitado_id'] = NULL;
              $data['fecha_deshabilitado'] = NULL;
              
              
              $entidades = $negociosTable->find('all', [
                'contain'=>['Usuarios'],
                'conditions'=>['Negocios.id in' => $data['seleccion']]
              ])->toArray();
              
              
              foreach ($entidades as $entidad){
                $negociosTable->patchEntity($entidad, $data, ['validate'=>false]); //actualizar entidades  
                
                if($negociosTable->save($entidad)){
                  $this->Correo->notificarNegocioRehabilitado(
                    $entidad['usuario']['usuario'], $entidad['usuario']['correo'], $this->generarTokenFalso(),
                    $entidad['id'], $entidad['nombre']
                  );
                }
              }  
              
              $this->Flash->success(__('La acción ha sido aplicada'), [
                'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
              ]);              
              
              break;
              
            case 2:
              //recorrer cada seleccion para eliminar individualmente
              
              $entidades = $negociosTable->find('all', [
                'conditions'=>['id in' => $data['seleccion']]
              ])->toArray();
              
              foreach ($entidades as $entidad){
                $folder = new Folder();              
                if($folder->delete('img/neg/'.$entidad->id)){
                  //elimina sucursal con todos los datos asociados                  
                  $e = $negociosTable->delete($entidad); 
                }
              }              
              
              if ($e == true) { //evaluar si fue aplicado
                $this->Flash->success(__('Los registros han sido eliminados'), [
                  'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                ]);
              } else { //error
                $this->Flash->error(__('Uno o varios registros no han sido eliminados'), [
                  //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                ]);
              }
              break;
          }
          
        }else{
          $this->Flash->error(__('No ha seleccionado registros para aplicar la acción.'));
        }
        
      }
      
      /*fin proceso para aplicar accion en los negocios*/
            
      
      /*******************************inicio buscar negocio************************************************/
      
      if(isset($data['btnBuscar'])){
        $txtNegocio = ($data['txtNegocio']=='') ? 'null':$data['txtNegocio'];   
        $txtUsuario = ($data['txtUsuario']=='') ? 'null':$data['txtUsuario'];
        
        return $this->redirect([
          'action' => 'index',
          $limite, $txtNegocio, $txtUsuario
        ]);
      }
      
      /*******************************fin buscar negocio***************************************************/
      
      
      //redireccionar pagina con limite, rol y otros parametros de paginacion
      if(isset($data['pag1'])){
        $this->request->query['page'] = $data['pag1'];
      }else{
        $this->request->query['page'] = $data['pag2'];
      }
      
      //deshabilitar para pruebas
      return $this->redirect([        
        'action'=>'index',
        $limite, $txtNegocio, $txtUsuario,
        '?' => $this->request->query
      ]);
    } //****************FINALIZA POST
    
    //sustituir limite por opcion por defecto si es null
    if($limite==null){
      $limite = $this->config['adm_negocios_opcion_defecto'];
    }    
    
    /*condiciones para consulta*/
    $condiciones = array();  
    $condCountNegocio = null;
    $condCountUsuario = null;
    
    //fitrado por busqueda de negocio
    $textoBuscarNegocio = '';
    if($txtNegocio!='null'){ //si en la url existe el parametro para buscar negocio se agrega la condicion
      $condiciones[] = [        
          'Negocios.nombre like '=>'%'.$txtNegocio.'%'
      ];
      $textoBuscarNegocio = $txtNegocio;
      $condCountNegocio = array('Negocios.nombre like '=>'%'.$txtNegocio.'%');
    }
    
    $textoBuscarUsuario = '';
    if($txtUsuario!='null'){ //si en la url existe el parametro para buscar usuario se agrega la condicion
      $condiciones[] = [
        'Usuarios.usuario like '=>'%'.$txtUsuario.'%'
      ];
      $condCountUsuario = array('Usuarios.usuario like '=>'%'.$txtUsuario.'%');
      $textoBuscarUsuario = $txtUsuario;
    }
    
    /*opciones de paginacion*/
    $this->paginate = [
      'sortWhitelist'=>['Negocios.nombre','Negocios.descripcion','Usuarios.usuario','Negocios.fecha_creacion', 
        'Negocios.count', 'Negocios.countCat'],
      'limit'=>$limite,
      'order'=>[
        'Negocios.fecha_creacion'=>'desc'
      ]
    ];
    
    try {      
      //buscar los datos
      $negociosQuery = $negociosTable->find()
        ->select(['Negocios.id', 'Negocios.usuario_id', 'Negocios.nombre', 'Negocios.nombre_slug', 
          'Negocios.descripcion', 
          'Negocios.fecha_creacion', 'Negocios.admin_habilitado', 'Usuarios.usuario', 'Sucursales.id',
          'count'=>'count(distinct SucursalesContador.id)', 'countCat'=>'count(distinct NegociosCategorias.id)'
        ])
        ->leftJoin(['Usuarios'=>'usuarios'], ['Negocios.usuario_id = Usuarios.id'])
        ->leftJoin(['Sucursales'=>'sucursales'], [
          'Negocios.id = Sucursales.negocio_id',
          'Sucursales.principal = 1'
        ])
        ->leftJoin(['SucursalesContador'=>'sucursales'], [
          'Negocios.id = SucursalesContador.negocio_id',
          'SucursalesContador.principal = 0'
        ])
        ->leftJoin(['NegociosCategorias'=>'negocios_categorias'], [
          'Negocios.id = NegociosCategorias.negocio_id'
        ])
        ->where([
          $condiciones
        ])
        ->group(['Negocios.id', 'Negocios.usuario_id', 'Negocios.nombre', 'Negocios.descripcion', 
          'Negocios.fecha_creacion', 'Negocios.admin_habilitado', 'Usuarios.usuario', 'Sucursales.id']);
      
      //paginar resultados
      $negocios = $this->paginate($negociosQuery)->toArray();   
      
      //contador
      /*$countNegocios = $negociosTable->find('all', [
        'conditions'=>[
          $condiciones
        ]
      ])->count();*/
      $countNegocios = $negociosTable->find()
        ->where([
          $condCountNegocio
        ])
        ->matching('Usuarios', function($w) use($condCountUsuario){
            return $w->where([
              $condCountUsuario
            ]);
          }
        )->count();
      
      
    } catch (NotFoundException $ex) {
      /* si los datos no existen para esa pagina intentar buscar en la pagina anterior */
      $query = $this->request->query;
      $query['page'] = $query['page'] -1;
      
      //redirigir a pagina anterior
      return $this->redirect([        
        'action'=>'index',
        $limite, 
        '?' => $query
      ]);
    }
    
    
    //opciones seleccionables de paginacion en la vista
    $verOpciones = $this->getVerOpciones($this->config['adm_negocios_opciones_visibles']); 
    $this->set(compact('debug', 'limite', 'verOpciones', 'negocios', 'textoBuscarNegocio', 'textoBuscarUsuario', 
      'countNegocios'));
    
  }
  
  
  /*
   * function para manejar las categorias del sitio
   */
  public function categorias($limite = null, $txtCategoria = null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_categorias']) || $this->perRol['admin_neg_categorias']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_categorias_activo', 1);
    $debug = 0;
    
    $categoriasTable = TableRegistry::get('Categorias');
    $categoria = $categoriasTable->newEntity();
    
    $data = null;
    if($this->request->is('post')){
      $data = $this->request->data;
      $limite = $data['limite']; //limite de usuarios a mostrar
      
      
      $aplicarAccion = 0; //bandera que determina si se ejecuta una accion
      if(isset($data['btnAgregarCategoria'])){ //guardar categoria
        $categoria = $categoriasTable->newEntity($data);
        
        if($categoriasTable->save($categoria)){
          $this->Flash->success(__('Categoría agregada'), [
            'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }else{
          $this->Flash->error(__('La categoría no fue agregada'), [
            //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }
        
      }elseif(isset($data['btnAplicarAccion'])){ //accion superior
        $aplicarAccion=1;
      }elseif(isset($data['btnAplicarAccion2'])){ //accion inferior
        $data['accion'] = $data['accion2'];
        $aplicarAccion=1;
      }
      
      
      if($aplicarAccion == 1){ //aplicar accion
        if(isset($data['seleccion'])){ //verificar si hay seleccion de registros 
          
          if($data['accion']==3){
            //buscar entidades de la seleccion
            $entidades = $categoriasTable->find('all', [
              'conditions'=>['id in' => $data['seleccion']]
            ])->toArray();
            
            foreach ($entidades as $entidad){ //eliminar cada entidad
              $categoriasTable->delete($entidad);
            }
            
            $this->Flash->success(__('Acción aplicada'), [
              'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);
          }
          
        }else{
          $this->Flash->error(__('No ha seleccionado registros'));
        }
      }
      
      
      /*******************************inicio buscar categoria********************************************/
      
      if(isset($data['btnBuscarCategoria'])){
        $txtCategoria = $data['txtCategoria'];    
        return $this->redirect([
          'prefix'=>'admin', 'controller'=>'NegociosSucursales',
          'action' => 'categorias',
          $limite, $txtCategoria
        ]);
      }
      
      /*******************************fin buscar categoria***********************************************/
      
      
      //redireccionar pagina con limite, rol y otros parametros de paginacion
      if(isset($data['pag1'])){
        $this->request->query['page'] = $data['pag1'];
      }else{
        $this->request->query['page'] = $data['pag2'];
      }
      
      //deshabilitar para pruebas
      if(!$categoria->errors()){
        return $this->redirect([   
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'categorias',
          $limite, $txtCategoria,
          '?' => $this->request->query
        ]);
      }
      
    }//*********************fin de post**************
    
    //sustituir limite por opcion por defecto si es null
    if($limite==null){
      $limite = $this->config['adm_categorias_opcion_defecto'];
    }
    
    
    /*condiciones para consulta*/
    $condiciones = array();      
    if($txtCategoria!=null){ //si en la url existe el parametro para buscar categoria se agrega la condicion a paginacion
      $condiciones[] = [        
        'Categorias.nombre like '=>'%'.$txtCategoria.'%'
      ];
    }
    
    //opciones de paginacion
    $this->paginate = [      
      'order'=>['Categorias.nombre'=>'asc'],
      'sortWhitelist'=>['Categorias.nombre', 'Categorias.count'],
      'limit'=>$limite,
      'conditions'=> $condiciones      
    ];      
       
    try {      
      //buscar los datos (nuevo con contador de categorias asignadas)
      $categoriasQuery = $categoriasTable->find()
      ->select(['Categorias.id', 'Categorias.nombre', 'count'=>'count(NegociosCategorias.id)'])
      ->leftJoin(['NegociosCategorias'=>'negocios_categorias'], [
        'Categorias.id = NegociosCategorias.categoria_id'
      ])
      ->where([
        $condiciones
      ])
      ->group(['Categorias.id', 'Categorias.nombre']);
    
      $categorias = $this->paginate($categoriasQuery)->toArray();
      
      /*total de categorias*/
      $totalCategorias = $categoriasTable->find('all', [
        'conditions'=> $condiciones      
      ])->count();
      
    } catch (NotFoundException $ex) {
      /* si los datos no existen para esa pagina intentar buscar en la pagina anterior */
      $query = $this->request->query;
      $query['page'] = $query['page'] -1;
      
      //redirigir a pagina anterior
      return $this->redirect([        
        'action'=>'index',
        $limite, $txtCategoria,
        '?' => $query
      ]);
    }
    
    //opciones seleccionables de paginacion en la vista
    $verOpciones = $this->getVerOpciones($this->config['adm_categorias_opciones_visibles']); 
    $this->set(compact('debug', 'categoria','data','limite', 'verOpciones', 'txtCategoria', 'categorias',
      'totalCategorias'));
  }
  
  public function getCategoriaEditar($categoria_id=null){
    $this->layout='ajax';
    $this->autoRender=false;
    
    $categoriasTable = TableRegistry::get('Categorias');
    $categoria = $categoriasTable->get($categoria_id)->toArray();
    
    echo json_encode($categoria);
  }
  
  public function editarCategoria(){
    $this->layout='ajax';
    $this->autoRender=false;
    
    if($this->request->is(['ajax', 'post'])){
      $id = filter_input(INPUT_POST, 'id');
      //$data['id'] = $id;
      $data['nombre'] = filter_input(INPUT_POST, 'nombre');
      
      $categoriasTable = TableRegistry::get('Categorias');
      $entidad = $categoriasTable->get($id);
      //$categoria = $categoriasTable->newEntity($data);      
      $categoria = $categoriasTable->patchEntity($entidad, $data);
      
      $guardo = 0;
      if($categoriasTable->save($categoria)){
        $guardo = 1;
      }
      
      echo $guardo;
    }
  }
  
  public function sugerenciaCategorias($limite = null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_categorias']) || $this->perRol['admin_neg_categorias']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_sugerencia_categorias_activo', 1);
    $debug = 0;
    
    $sugerenciaCategoriasTable = TableRegistry::get('CategoriasSugerencias');
    $sugerenciaCategoria = $sugerenciaCategoriasTable->newEntity();
    
    
    $data = null;
    if($this->request->is('post')){
      $data = $this->request->data;
      $limite = $data['limite']; //limite de usuarios a mostrar
      
      
      $aplicarAccion = 0; //bandera que determina si se ejecuta una accion
      if(isset($data['btnAplicarAccion'])){ //accion superior
        $aplicarAccion=1;
      }
      
      if($aplicarAccion == 1){ //aplicar accion
        if(isset($data['seleccion'])){ //verificar si hay seleccion de registros 
          
          
          switch ($data['accion']) {
            case 1: //Aprobada
              $this->loadComponent('Correo');
              
              //buscar entidades de la seleccion
              $entidades = $sugerenciaCategoriasTable->find('all', [
                'conditions'=>[
                  'CategoriasSugerencias.id in' => $data['seleccion']
                ],
                'contain'=>'Usuarios'
              ])->toArray();
              
              $categoriasInsertTable = TableRegistry::get('Categorias');

              foreach ($entidades as $entidad){ 
                /*insertar sugerencia en tabla de categorias*/
                $dataInsert=null;
                $dataInsert['nombre']=$entidad['nombre'];
                $categoriasInsert = $categoriasInsertTable->newEntity($dataInsert);
                
                if($categoriasInsertTable->save($categoriasInsert)){
                  //actualizar estado de sugerencia y enviar notificacio si esta aprobada
                  $dataUpdate = null;
                  $dataUpdate['estado']=1;
                  $entidad = $sugerenciaCategoriasTable->patchEntity($entidad, $dataUpdate);

                  if($sugerenciaCategoriasTable->save($entidad)){    
                    $this->Correo->notificarSugerenciaCategoria($entidad['usuario']['correo'], $entidad['usuario']['usuario'], 1, $entidad['nombre']);
                  }
                  
                  $this->Flash->success(__('Acción aplicada'), [
                    'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                  ]);
                }else{
                  $this->Flash->error(__('La categoría no ha sido guardada'));
                }
                //fin insertar
                 
              }
              
              break;
            case 2: //Rechazada
              //buscar entidades de la seleccion
              $entidades = $sugerenciaCategoriasTable->find('all', [
                'conditions'=>[
                  'CategoriasSugerencias.id in' => $data['seleccion']
                ],
                'contain'=>'Usuarios'
              ])->toArray();
              
              foreach ($entidades as $entidad){ 
                $dataUpdate = null;
                $dataUpdate['estado']=2;
                $entidad = $sugerenciaCategoriasTable->patchEntity($entidad, $dataUpdate);
                
                if($sugerenciaCategoriasTable->save($entidad)){    
                  $this->Flash->success(__('Acción aplicada'), [
                    'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                  ]);
                }else{
                  $this->Flash->error(__('La categoría no ha sido guardada'));
                }
              }
              
              break;
            case 3: //Eliminar
              //buscar entidades de la seleccion
              $entidades = $sugerenciaCategoriasTable->find('all', [
                'conditions'=>['id in' => $data['seleccion']]
              ])->toArray();

              foreach ($entidades as $entidad){ //eliminar cada entidad
                $sugerenciaCategoriasTable->delete($entidad);
              }

              $this->Flash->success(__('Acción aplicada'), [
                'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
              ]);

              break;

            default:
              break;
          }
          
        }else{
          $this->Flash->error(__('No ha seleccionado registros'));
        }
      }
      
      
      
      //deshabilitar para pruebas      
      return $this->redirect([   
        'prefix'=>'admin',
        'controller'=>'NegociosSucursales',
        'action'=>'sugerenciaCategorias',
        $limite, 
        '?' => $this->request->query
      ]);
     
    }//fin post
    
    
    
    //sustituir limite por opcion por defecto si es null
    if($limite==null){
      $limite = $this->config['adm_categorias_opcion_defecto'];
    }
      
    
    //opciones de paginacion
    $this->paginate = [      
      'order'=>['CategoriasSugerencias.estado'=>'asc', 'CategoriasSugerencias.fecha_creacion'=>'asc'],
      'sortWhitelist'=>['CategoriasSugerencias.nombre', 'Usuarios.usuario', 'CategoriasSugerencias.fecha_creacion', 
        'CategoriasSugerencias.estado'],
      'limit'=>$limite  
    ];
    
    try{
      //buscar los datos
      $categorias = $this->paginate($sugerenciaCategoriasTable->find('all', ['contain'=>'Usuarios']))->toArray();  
        
        
    } catch (NotFoundException $ex) {
      /* si los datos no existen para esa pagina intentar buscar en la pagina anterior */
      $query = $this->request->query;
      $query['page'] = $query['page'] -1;
      
      //redirigir a pagina anterior
      return $this->redirect([        
        'action'=>'sugerenciaCategorias',
        $limite,
        '?' => $query
      ]);
    }
    
    //opciones seleccionables de paginacion en la vista
    $verOpciones = $this->getVerOpciones($this->config['adm_categorias_opciones_visibles']); 
    $this->set(compact('debug', 'limite', 'verOpciones','sugerenciaCategoria','categorias'));
  }
  
  //editar sugerencia de categoria
  public function getSugerenciaCategoriaEditar($categoria_id=null){
    $this->layout='ajax';
    $this->autoRender=false;
    
    $categoriasTable = TableRegistry::get('CategoriasSugerencias');
    $categoria = $categoriasTable->get($categoria_id)->toArray();
    
    echo json_encode($categoria);
  }
  
  //Editar sugerencia de cateoria
  public function editarSugerenciaCategoria(){
    $this->layout='ajax';
    $this->autoRender=false;
    
    if($this->request->is(['ajax', 'post'])){
      $id = filter_input(INPUT_POST, 'id');
      //$data['id'] = $id;
      $data['nombre'] = filter_input(INPUT_POST, 'nombre');
      
      $categoriasTable = TableRegistry::get('CategoriasSugerencias');
      $entidad = $categoriasTable->get($id);
      //$categoria = $categoriasTable->newEntity($data);      
      $categoria = $categoriasTable->patchEntity($entidad, $data);
      
      $guardo = 0;
      if($categoriasTable->save($categoria)){
        $guardo = 1;
      }
      
      echo $guardo;
    }
  }
  
  
  public function agregarNegocio(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->loadComponent('Ubicacion');
    $this->loadComponent('Archivos');
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);
    $this->set('jquery_tags',1); //categorias
    $this->set('jquery_mask',1); //mascara de campos
    $this->set('jquery_ui', 1);
    $this->set('mapa_nuevo',1); //css de mapa
    $debug = 0; 
    
    $negociosTable = TableRegistry::get('Negocios');    
    $negocio=$negociosTable->newEntity();   
    
    $data = null;
    if($this->request->is('post')){
      $data = $this->limpiarRedesSociales($this->request->data);  
      
      //array de datos para sucursales
      $data['sucursales'][0]['usuario_id']=$data['usuario_id'];
      $data['sucursales'][0]['nombre']=$data['nombre'];
      $data['sucursales'][0]['correo']=$data['correo'];
      $data['sucursales'][0]['facebook']=$data['facebook'];
      $data['sucursales'][0]['twitter']=$data['twitter'];
      $data['sucursales'][0]['google_plus']=$data['google_plus'];
      $data['sucursales'][0]['instagram']=$data['instagram'];
      $data['sucursales'][0]['principal']=1;
      
      /*eliminar telefonos vacios*/
      if (isset($data['sucursales'][0]['telefonos'])){
        foreach($data['sucursales'][0]['telefonos'] as $key=>$telefono){
          if ($telefono['numero'] == '') {
            unset($data['sucursales'][0]['telefonos'][$key]);
          } 
        }
      }
      
      /*eliminar items vacios de data*/
      foreach ($data as $llave=>$valor){
        if($valor==''){
          unset($data[$llave]);
        }
      }         
      foreach ($data['sucursales'][0] as $llave=>$valor){
        if($valor==''){
          unset($data['sucursales'][0][$llave]);
        }
      } 
      
      /*Agregar validacion de nombre de usuario requerido y existente*/      
      $negociosTable->validator()->requirePresence('usuario', true, __('El nombre de usuario es requerido'));
      $negociosTable->validator()
        ->add('usuario', 'existe', [
          'rule'=>['verifExiste', 'Usuarios', 'usuario'],
          'provider'=>'table',
          'last'=>true,
          'message' => __('Este nombre de usuario no se encuentra registrado')
        ]);
      
      $negocio=$negociosTable->newEntity($data, [
        'associated' => [ //especificar las asociaciones para tambien guadar sus datos
          'Categorias', 
          'Sucursales' => [
            'validate'=>'Ubicacion',
            'accessibleFields' => [
              'nombre' => true
            ],
            'associated'=>[
              'Telefonos' => [
                'accessibleFields' => [
                  'sucursal_id' => true
                ]
              ]
            ]
          ]
        ] 
      ]);
      
      if($negociosTable->save($negocio)){ //guardar negocio
        
        //crear directorio para imagenes   
        $ruta = WWW_ROOT. DS ."img". DS ."neg". DS .$negocio->id; //ruta de guardado  
        if(!file_exists($ruta)){
          $folder = new Folder();
          $folder->create($ruta, 0777);
        }        
        
        /*copiar logo cargado a carpeta si no hay error*/
        if($data['logo']['error']==0){
          $this->Archivos->cargarLogo($data['logo'], $negocio->id);
        }
        
        //No publicar facebook o twitter cuando sea prueba en localhost
        $thisUrl = \Cake\Routing\Router::url(null, true);
        if(!preg_match('/localhost/i', $thisUrl) && Configure::read('debug')!=true){
          //Publicar nuevo negocio a la fan page en Facebook        
          $this->loadComponent("Facebook");
          $this->Facebook->publicarNuevoNegocio($data, $negocio->id, $negocio['sucursales'][0]['id']);
          //fin publicar a face

          //Publicar nuevo negocio en el twitter
          $this->loadComponent('Twitter');
          $this->Twitter->publicarNuevoNegocio($data, $negocio->id, $negocio['sucursales'][0]['id']);
          //fin publicar en twitter
        }        
        
        $this->Flash->success(__('El negocio ha sido publicado.'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);        
        
        return $this->redirect([
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'editarNegocio',
          $negocio->id
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }
        
    $paises = $this->Ubicacion->getPaises();        
    $this->set(compact('negocio', 'debug', 'data','paises'));
    
  }
  
  
  /*funcion para obtener texto predictivo de los usuarios*/
  public function getUsuario(){
    $this->layout = 'ajax';
    $this->autoRender = false;
    
    $term = filter_input(INPUT_GET,'term');
    
    /*obtener usuarios*/
    $usuariosTable = TableRegistry::get('Usuarios');
    $usuarios = $usuariosTable->find('all', [
      'conditions'=>[
        'usuario like'=>'%'.$term.'%'        
      ],
      'order'=>[
        'usuario'=>'asc'
      ]
    ])->toArray();
    
    //convertir a formato esperado por jquery ui
    $usuariosJson = array();
    foreach ($usuarios as $usuario){
      $usuariosJson[]=['label'=>$usuario['usuario'], 'id'=>$usuario['id'], 'correo'=>$usuario['correo']];
    }
    /*fin obtener categorias*/
    
        
    echo json_encode($usuariosJson);
  }
  
  
  
  /*funcion para editar un negocio*/
  public function editarNegocio($negocio_id=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);
    $this->set('me_general', 1);
    $this->set('jquery_tags',1); //categorias
    $this->set('jquery_mask',1); //mascara de campos
    $debug = 0;
    
    
    $this->loadComponent('Archivos');
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id, [ //obtener la informacion del negocio
      'contain'=>[        
        'Categorias', 'Usuarios', 'Sucursales' => function($q){
          return $q
            ->where(['Sucursales.principal'=>1])
            ->contain(['Telefonos']);
        }
      ]      
    ]);
      
    //bandera auxiliar para saber si el negocio esta deshabilitado
    $negocio_estado = $negocio['admin_habilitado'];
      
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
      
    $sucursalPrincipal = $negocio['sucursales'][0]['id'];  
    
    $data = null;
    if($this->request->is(['post', 'put'])){
      $data = $this->limpiarRedesSociales($this->request->data);  
            
      //array de datos para sucursales
      $data['sucursales'][0]['id']=$negocio['sucursales'][0]['id'];
      $data['sucursales'][0]['nombre']=$data['nombre'];
      $data['sucursales'][0]['correo']=$data['correo'];
      $data['sucursales'][0]['facebook']=$data['facebook'];
      $data['sucursales'][0]['twitter']=$data['twitter'];
      $data['sucursales'][0]['google_plus']=$data['google_plus'];
      $data['sucursales'][0]['instagram']=$data['instagram'];
      $data['sucursales'][0]['fecha_modificacion'] = new \DateTime('now');
      
      /*eliminar telefonos vacios*/       
      if (isset($data['sucursales'][0]['telefonos'])){
        foreach($data['sucursales'][0]['telefonos'] as $key=>$telefono){
          if ((!isset($telefono['id']) || $telefono['id']=='') && $telefono['numero'] == '') {
            unset($data['sucursales'][0]['telefonos'][$key]);
          } 
        }
      }
      
      /*eliminar items vacios de data*/
      foreach ($data as $llave=>$valor){
        if($valor==''){
          $data[$llave]=NULL;
        }
      }         
      
      foreach ($data['sucursales'][0] as $llave=>$valor){
        if($valor==''){
          $data['sucursales'][0][$llave]=NULL;
        }
      } 
      
      /*verificar si se deshabilita el negocio*/
      $data['admin_deshabilitado_id'] = $this->cookieUsuarioAdmin['id'];
      $data['fecha_deshabilitado'] = new \DateTime('now');
      
      if($data['admin_habilitado']==1){ //variables para habilitado        
        $data['razon_deshabilitado'] = NULL;
        $data['admin_deshabilitado_id'] = NULL;
        $data['fecha_deshabilitado'] = NULL;
      }
      /*fin verificar si se deshabilita el negocio*/
      
      //agregar fecha de modificacion      
      $data['fecha_modificacion'] = new \DateTime('now');
      $negocio = $negociosTable->patchEntity($negocio, $data, [
        'associated' => [ //especificar las asociaciones para tambien guadar sus datos          
          'Categorias', 
          'Sucursales' => [
            'validate'=>false,
            'accessibleFields' => [
              'nombre' => true
            ],
            'associated'=>[
              'Telefonos' => [
                'accessibleFields' => [                  
                  'sucursal_id' => false
                ]
              ]
            ]
          ]
        ] 
      ]);      
            
      if($negociosTable->save($negocio)){ //guardar negocio
        
        //eliminar telefonos si hay id's especificados
        if(isset($data['borrarTelefonos'])){
          $telefonosTable = TableRegistry::get('Telefonos');
          $telefonosTable->deleteAll(['id in'=>  explode(',', $data['borrarTelefonos'])]);
        }
        
        //crear directorio para imagenes
        $ruta = WWW_ROOT. DS ."img". DS ."neg". DS .$negocio_id; //ruta de guardado         
        if(!file_exists($ruta)){          
          $folder = new Folder();
          $folder->create($ruta, 0777);
        }
        
        
        //eliminar logo personalizado
        if ($data['eliminar_logo'] && file_exists('img/neg/'.$negocio->id.'/logo.jpg')){    
          unlink('img/neg/'.$negocio->id.'/logo.jpg');          
        }
        
        //copiar logo cargado a carpeta si no hay error
        if($data['logo']['error']==0){
          $this->Archivos->cargarLogo($data['logo'], $negocio->id);
        }
        
        //mandar notificacion por correo cuando exista un cambio de estado en el negocio
        //de habilitado a deshabilitado 
        if($negocio_estado==1 && $data['admin_habilitado']==0){   
          $this->loadComponent('Correo');
          $this->Correo->notificarNegocioSuspendido(
            $negocio['usuario']['usuario'], $negocio['usuario']['correo'], $this->generarTokenFalso(),
            $negocio['id'], $negocio['nombre'], $data['razon_deshabilitado']
          );
          
        //de deshabilitado a habilitado  
        }elseif($negocio_estado==0 && $data['admin_habilitado']==1){
          $this->loadComponent('Correo');
          $this->Correo->notificarNegocioRehabilitado(
            $negocio['usuario']['usuario'], $negocio['usuario']['correo'], $this->generarTokenFalso(),
            $negocio['id'], $negocio['nombre']
          );
        }
        
        $this->Flash->success(__('El negocio ha sido actualizado.'), [
          'key'=>'habilitado_deshabilitado',
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
        
        $this->Flash->default(''); //utilizado para limpiar flash de warning
        
        return $this->redirect([
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'editarNegocio',
          $negocio->id
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      } //fin save
      
      
    } /***********************FIN DE POST***********************************/
    
    $this->set(compact('debug','negocio_id','data','negocio', 'sucursalPrincipal'));
  }
  
  
  public function editarNegocioMapa($negocio_id = null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->loadComponent('Ubicacion');
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);
    $this->set('me_mapa', 1);    
    $this->set('mapa_nuevo',1); //css de mapa  
    $debug = 0;
    
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id, [
      'contain'=>[
        'Sucursales' => [          
          'conditions'=> [
            'Sucursales.principal'=>1
          ]
        ]
      ]
    ]);
    $sucursalPrincipal = $negocio['sucursales'][0]['id'];  
      
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
    
    $data=null;
    if($this->request->is(['post','put'])){
      $data = $this->request->data;
      $data['sucursales'][0]['id'] = $negocio['sucursales'][0]['id'];
      $data['sucursales'][0]['fecha_modificacion'] = new \DateTime('now'); //agregar fecha de modificacion
      $data['fecha_modificacion'] = new \DateTime('now'); //agregar fecha de modificacion
      $negocio = $negociosTable->patchEntity($negocio, $data, [
        'validate'=>false,
        'associated'=>[
          'Sucursales' => ['validate'=>'Ubicacion']
        ]
      ]);
      
      if($negociosTable->save($negocio)){
        
        $this->Flash->success(__('La ubicación de tu negocio ha sido actualizada.'), [
          'key'=>'habilitado_deshabilitado',
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
        
        $this->Flash->default(''); //utilizado para limpiar flash de warning
        
        
        return $this->redirect([
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'editarNegocioMapa',
          $negocio->id,
          $this->tokenFalso
        ]);
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }
    
    $paises = $this->Ubicacion->getPaises(); //lista de paises   
    $this->set(compact('debug','negocio_id','data','negocio','paises', 'sucursalPrincipal'));
    
  }
  
  //funcion para editar la galeria de imagenes de un negocio
  public function editarNegocioImg($negocio_id=null){  
    
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    
    //verificar si se esta viendo desde un telefono para cambiar en mensaje de las instrucciones en la vista
    $this->loadComponent('RequestHandler');    
    $isMobile = false;
    if ($this->RequestHandler->isMobile()){
      $isMobile = true;
    } 
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);
    $this->set('me_imagenes', 1);   
    $this->set('jsupload',1); //upload js
    $this->set('jquery_ui',1); //jquery ui js    
    $max_upload = $this->config['negocios_img_max_peso'] * 1024; //bytes
    $debug = 0;
    
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id, [
      'contain'=>[
        'Imagenes' => [
          'conditions' => [
            'Imagenes.sucursal_id' => 0
          ]
        ],
        'Sucursales' => [
          'conditions'=> [
            'Sucursales.principal'=>1
          ]
        ]
      ]
    ]);
    $sucursalPrincipal = $negocio['sucursales'][0]['id'];  
    
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
    
    if($this->request->is('post') && isset($this->request->data['btnEliminarImagenes']) && isset($this->request->data['chk'])){
      $ids = $this->request->data['chk']; //id's de las imagenes a eliminar
      
      $imagenesTable = TableRegistry::get('Imagenes');
      
      //recorrer cada id para eliminar las imagenes individualmente por si alguna no se borra de disco duro
      //no eliminarla de la base de datos
      foreach ($ids as $id){ 
        $imagen = $imagenesTable->get($id);
        $ruta_img = 'img/neg/'.$imagen->negocio_id.'/'.$imagen->sucursal_id.'/'.$imagen->nombre.'.jpg';
        
        if(file_exists($ruta_img)==false){
          $imagenesTable->deleteAll([
            'id'=>$imagen->id
          ]);
        }else{        
          if(unlink($ruta_img)){
            $imagenesTable->deleteAll([
              'id'=>$imagen->id
            ]);
          }
        }
      }
      
      $this->Flash->default(''); //utilizado para limpiar flash de warning
      
      $this->redirect([
        'prefix'=>'admin',
        'controller'=>'NegociosSucursales',
        'action'=>'editarNegocioImg',
        $negocio_id
      ]);
    }
    
    
    $this->set(compact('debug','negocio_id','negocio','max_upload','isMobile', 'sucursalPrincipal'));    
  }
  
  
  public function listaSucursales($negocio_id=null, $limite=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);
    $this->set('menu_index',1); //ver opciones en el menu de sucursales
    $this->set('ms_lista', 1); //lista de sucursales activo
    $debug = 0;
    
    
    $sucursalesTable = TableRegistry::get('Sucursales');  
    $sucursalEntity = $sucursalesTable->newEntity();
    $data = null;    
    if($this->request->is('post')){
      $data = $this->request->data; //captura de datos
      
      if(isset($data['btnAplicar'])){ //aplicar una accion
        if(isset($data['seleccion'])){
          switch ($data['accion']) {
            case 0: //habilitar
            case 1: //deshabilitar
              $a = $sucursalesTable->updateAll([
                'habilitado'=>$data['accion']
              ],[
                'id in' =>$data['seleccion']
              ]);
              
              if ($a == true) { //evaluar si fue aplicado
                $this->Flash->success(__('La acción ha sido aplicada'), [
                  'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                ]);
              } else { //error
                $this->Flash->error(__('La acción no ha sido aplicada'), [
                  //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                ]);
              }
              break;
            case 2: //eliminar
              //recorrer cada seleccion para eliminar individualmente
              
              $entidades = $sucursalesTable->find('all', [
                'conditions'=>['id in' => $data['seleccion']]
              ])->toArray();
              
              foreach ($entidades as $entidad){
                $folder = new Folder();              
                if($folder->delete('img/neg/'.$negocio_id.'/'.$entidad->id)){
                  //elimina sucursal con todos los datos asociados                  
                  $e = $sucursalesTable->delete($entidad); 
                }
              }              
              
              if ($e == true) { //evaluar si fue aplicado
                $this->Flash->success(__('Los registros han sido eliminados'), [
                  'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                ]);
              } else { //error
                $this->Flash->error(__('Uno o varios registros no han sido eliminados'), [
                  //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
                ]);
              }
              break;

            default:
              break;
          }
        }else{
          $this->Flash->error(__('No ha seleccionado registros para aplicar la acción.'));
        }
      }else{ //accion POST pero solo para redireccion por cambio en limite
        if(isset($data['limite'])){
          $limite = $data['limite'];
        }
      }      
      
      //En cada POST redireccionar pagina con limite y otros parametros de paginacion
      if(isset($data['pag1'])){
        $this->request->query['page'] = $data['pag1'];
      }else{
        $this->request->query['page'] = $data['pag2'];
      }
      
      $this->Flash->default(''); //utilizado para limpiar flash de warning
      
      //deshabilitar para pruebas
      return $this->redirect([    
        'prefix'=>'admin',
        'controller'=>'NegociosSucursales',
        'action'=>'listaSucursales',
        $negocio_id, $limite,
        '?' => $this->request->query
      ]);
      
    } //fin post
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id);
    
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
    
    //sustituir limite por opcion por defecto si es null
    if($limite==null){
      $limite = $this->config['adm_negocios_opcion_defecto'];
    }
    
    //opciones de paginacion
    $this->paginate = [
      'fields'=>[
        'Sucursales.id', 'Sucursales.nombre', 'Sucursales.habilitado', 'Paises.nombre', 'Departamentos.nombre', 
        'Municipios.nombre', 'Negocios.nombre_slug'
      ],
      'conditions'=>[
        'Sucursales.negocio_id'=>$negocio_id,  
        'Sucursales.principal'=>0
      ],
      'sortWhitelist'=>['Sucursales.id','Sucursales.nombre','Paises.nombre', 'Departamentos.nombre', 'Municipios.nombre'],
      'limit'=>$limite
    ];
    
    try {
      //obtener los registros
      $sucursales = $this
        ->paginate(
          $sucursalesTable
            ->find('all')
            ->contain(['Paises','Departamentos','Municipios', 'Negocios'])
        )->toArray();
      
    } catch (NotFoundException $ex) {
      /* si los datos no existen para esa pagina intentar buscar en la pagina anterior */
      $query = $this->request->query;
      $query['page'] = $query['page'] -1;
      
      //redirigir a pagina anterior
      return $this->redirect([    
        'prefix'=>'admin',
        'controller'=>'NegociosSucursales',
        'action'=>'index',
        $negocio_id, $limite,
        '?' => $query
      ]);
    }
    
    
    
    //opciones seleccionables de paginacion o limites en la vista
    $verOpciones = $this->getVerOpciones($this->config['adm_negocios_opciones_visibles']); 
    $this->set(compact('limite','data','debug','negocio_id','negocio','verOpciones','sucursales','sucursalEntity'));
  }
  
  
  public function agregarSucursal($negocio_id=null){
    
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->loadComponent('Ubicacion');
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);
    $this->set('mapa_nuevo',1); //css de mapa
    $this->set('jquery_mask',1); //mascara de campos
    $this->set('ms_agregar',1);
    $debug = 0;
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id);
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->newEntity();
    
    $data = null;    
    if($this->request->is('post')){
      $data = $this->limpiarRedesSociales($this->request->data); //captura de datos
      $data['negocio_id']=$negocio_id;
      $data['usuario_id']=$negocio['usuario_id'];
      
      /*eliminar telefonos vacios*/
      if (isset($data['telefonos'])){
        foreach($data['telefonos'] as $key=>$telefono){
          if ($telefono['numero'] == '') {
            unset($data['telefonos'][$key]);
          } else {
            $data['telefonos'][$key]['negocio_id'] = $negocio_id;
          }
        }
      }
      
      /*eliminar items vacios de data*/
      foreach ($data as $llave=>$valor){
        if($valor==''){
          unset($data[$llave]);
        }
      }
      
      $sucursal = $sucursalesTable->newEntity($data, [
        'associated' => ['Telefonos'] //especificar las asociaciones para tambien guadar sus datos
      ]);
      
      if($sucursalesTable->save($sucursal)){ //guardar sucursal
        
        //crear directorio para imagenes si no existe
        $ruta = WWW_ROOT. DS ."img". DS ."neg". DS .$negocio_id. DS .$sucursal->id; //ruta de guardado            
        if(!file_exists($ruta)){          
          $folder = new Folder();
          $folder->create($ruta, 0777);
        }
        
        //No publicar facebook o twitter cuando sea prueba en localhost
        $thisUrl = \Cake\Routing\Router::url(null, true);
        if(!preg_match('/localhost/i', $thisUrl) && Configure::read('debug')!=true){
          //Publicar nueva sucursal a la fan page en Facebook        
          $this->loadComponent("Facebook");
          $this->Facebook->publicarNuevaSucursal($negocio, $sucursal);
          //fin publicar a face  
          //Publicar nueva sucursal en twitter      
          $this->loadComponent("Twitter");
          $this->Twitter->publicarNuevaSucursal($negocio, $sucursal);
          //fin publicar en twitter  
        }
                
        $this->Flash->success(__('La sucursal ha sido guardada.'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);        
        
        return $this->redirect([
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'listaSucursales',
          $negocio_id
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }      
    }
    
    $paises = $this->Ubicacion->getPaises(); //lista de paises
    $this->set(compact('data','debug','listaNegocios','negocio_id','negocio','paises','sucursal'));
  }
  
  public function editarSucursal($sucursal_id=null){
    
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);    
    $this->set('jquery_mask',1); //mascara de campos
    $this->set('ms_general',1);
    $this->set('editar_sucursal',1); 
    $debug = 0;
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'contain'=>[
        'Telefonos', 'Negocios'
      ]
    ]);
    $negocio_id = $sucursal['negocio_id'];
    
    $data = null;    
    if($this->request->is(['post','put'])){
      $data = $this->limpiarRedesSociales($this->request->data);      
      
      /*eliminar telefonos vacios*/
      if (isset($data['telefonos'])){
        foreach($data['telefonos'] as $key=>$telefono){
          if ((!isset($telefono['id']) || $telefono['id']=='') && $telefono['numero'] == '') {
            unset($data['telefonos'][$key]);
          } else {
            $data['telefonos'][$key]['negocio_id'] = $negocio_id;
          }
        }
      }      
      
      /*eliminar items vacios de data*/
      foreach ($data as $llave=>$valor){
        if($valor==''){
          $data[$llave]=NULL;
        }
      }
      
      $data['fecha_modificacion'] = new \DateTime('now'); //fecha de modificacion
      
      //remover validaciones de pais, departamento y municipio para evitar conflictos
      $sucursalesTable->validator()
        ->remove('pais_id')
        ->remove('departamento_id')
        ->remove('municipio_id');   
      
      $sucursal = $sucursalesTable->patchEntity($sucursal, $data, [
        'associated' =>['Telefonos'] //asociar datos de los telefonos
      ]);
      
      if($sucursalesTable->save($sucursal)){ //guardar negocio
        
        //eliminar telefonos si hay id's especificados
        if($data['borrarTelefonos']!=''){
          $telefonosTable = TableRegistry::get('Telefonos');
          $telefonosTable->deleteAll(['id in'=>  explode(',', $data['borrarTelefonos'])]);  
        }
        
        $this->Flash->success(__('La sucursal ha sido actualizada.'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);        
        
        return $this->redirect([
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'editarSucursal',
          $sucursal->id
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }    
    
    
    $this->set(compact('debug','negocio_id','data','sucursal_id','sucursal'));    
  }
  
  
  public function editarSucursalMapa($sucursal_id=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->loadComponent('Ubicacion');
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);    
    $this->set('mapa_nuevo',1); //css de mapa  
    $this->set('ms_mapa',1);
    $this->set('editar_sucursal',1); 
    $debug = 0;
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'contain'=>'Negocios'
    ]);
    $negocio_id = $sucursal['negocio_id'];
    
    $data = null;    
    if($this->request->is(['post','put'])){
      $data = $this->request->data;
      $data['fecha_modificacion'] = new \DateTime('now'); //fecha de modificacion
      $sucursal = $sucursalesTable->patchEntity($sucursal, $data, [
        'validate'=>'Ubicacion'
      ]);
      
      if($sucursalesTable->save($sucursal)){
        $this->Flash->success(__('La ubicación de la sucursal ha sido actualizada.'), [
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);        
        
        return $this->redirect([
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'editarSucursalMapa',
          $sucursal_id
        ]);
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }
    
    $paises = $this->Ubicacion->getPaises(); //lista de paises   
    $this->set(compact('debug','negocio_id','data','sucursal_id','sucursal','paises'));
  }
  
  
  public function editarSucursalImg($sucursal_id=null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    //permiso
    if(!isset($this->perRol['ver_panel_neg_sucursales']) || $this->perRol['ver_panel_neg_sucursales']==0 || !isset($this->perRol['admin_neg_sucursales']) || $this->perRol['admin_neg_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    //verificar si se esta viendo desde un telefono para cambiar en mensaje de las instrucciones en la vista
    $this->loadComponent('RequestHandler');    
    $isMobile = false;
    if ($this->RequestHandler->isMobile()){
      $isMobile = true;
    }
    
    $this->set('menu_admin', $this->menu);
    $this->set('negocios_activo', 1);
    $this->set('neg_negocios_activo', 1);    
    $this->set('jsupload',1); //upload js
    $this->set('jquery_ui',1); //jquery ui js
    $max_upload = $this->config['negocios_img_max_peso'] * 1024; //bytes    
    $this->set('ms_imagenes',1); //activo en imagenes
    $this->set('editar_sucursal',1); 
    $debug = 0;
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'contain'=>[
        'Imagenes', 'Negocios'
      ]
    ]);
    $negocio_id = $sucursal['negocio_id'];
    
    if($this->request->is('post') && isset($this->request->data['btnEliminarImagenes']) && isset($this->request->data['chk'])){      
      $ids = $this->request->data['chk']; //id's de las imagenes a eliminar
      
      $imagenesTable = TableRegistry::get('Imagenes'); 
      
      //recorrer cada id para eliminar las imagenes individualmente por si alguna no se borra de disco duro
      //no eliminarla de la base de datos
      foreach ($ids as $id){
        $imagen = $imagenesTable->get($id);
        $ruta_img = 'img/neg/'.$imagen->negocio_id.'/'.$imagen->sucursal_id.'/'.$imagen->nombre.'.jpg';
        
        if(file_exists($ruta_img)==false){
          $imagenesTable->deleteAll([
            'id'=>$imagen->id
          ]);
        }else{        
          if(unlink($ruta_img)){
            $imagenesTable->deleteAll([
              'id'=>$imagen->id
            ]);
          }
        }
      }
      
      $this->redirect([
        'prefix'=>'admin',
        'controller'=>'NegociosSucursales',
        'action'=>'editarSucursalImg',
        $sucursal_id
      ]);
    }
    
    $this->set(compact('debug','negocio_id','sucursal','max_upload','sucursal_id','isMobile'));
  }
}
