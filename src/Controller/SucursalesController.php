<?php

namespace App\Controller;
use Cake\ORM\TableRegistry;
use \Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\Filesystem\Folder;
use Cake\Core\Configure;

class SucursalesController extends AppController{  
  public $tokenFalso = null;
  
  //evitar llamada beforeFilter a las llamadas ajax
  public function beforeFilter(\Cake\Event\Event $event) {
    if(!$this->request->is(['ajax']) || $this->request->action == 'cargarImagenes'){
      parent::beforeFilter($event);      
            
      /*genera un token falso para la direccion web*/
      $tokenFalso = $this->generarTokenFalso();
      $this->tokenFalso = $tokenFalso;
      $this->set('tokenFalso',$tokenFalso);
    }
  } 
  
  //funcion para mostrar la lista de sucursales del negocio
  public function index($negocio_id=null, $limite=null){    
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    //verificar permiso de crear sucursales
    if(!isset($this->perRol['crear_sucursales']) || $this->perRol['crear_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id);
    
    //verificar que el negocio que intenta ver le pertenezca
    if($negocio->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
    
    $this->loadComponent('Archivos');
    $this->set('titulo',__('Sucursales'));     
    $this->set('ms_lista',1);
    $this->set('menu_index',1); //ver opciones en el menu de sucursales
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
      
      //deshabilitar para pruebas
      return $this->redirect([        
        'action'=>'index',
        $negocio_id, $limite, $this->tokenFalso,
        '?' => $this->request->query
      ]);
      
    } //fin post
    
    //sustituir limite por opcion por defecto si es null
    if($limite==null){
      $limite = $this->config['negocios_opcion_defecto'];
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
        'action'=>'index',
        $negocio_id, $limite, $this->tokenFalso,
        '?' => $query
      ]);
    }
    
    
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para el menu
    //opciones seleccionables de paginacion o limites en la vista
    $verOpciones = $this->getVerOpciones($this->config['negocios_opciones_visibles']); 
    $this->set(compact('limite','data','debug','listaNegocios','negocio_id','negocio','verOpciones','sucursales','sucursalEntity'));
  }
  
  //funcion para agregar una nueva sucursal
  function agregar($negocio_id=null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    $this->loadComponent('Ubicacion');
    
    //verificar permiso para crear sucursales
    if(!isset($this->perRol['crear_sucursales']) || $this->perRol['crear_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id);
    
    //verificar que el negocio que intenta ver le pertenezca
    if($negocio->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->set('titulo',__('Agregar sucursal'));  
    $this->set('jquery_mask',1); //mascara de campos
    $this->set('ms_agregar',1);
    $this->set('mapa_nuevo',1); //css de mapa
    $debug = 0;
    
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->newEntity();
    
    $data = null;    
    if($this->request->is('post')){
      $data = $this->limpiarRedesSociales($this->request->data); //captura de datos
      $data['negocio_id']=$negocio_id;      
      
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
      
      if($sucursalesTable->save($sucursal)){ //guardar negocio
        
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
                
        $this->Flash->success(__('La sucursal ha sido guardada. En esta sección puedes modificar la información así como administrar las imágenes que quieres que aparezcan.'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);        
        
        return $this->redirect([
          'prefix'=>false,
          'controller'=>'Sucursales',
          'action'=>'editar',
          $sucursal->id,
          $this->tokenFalso
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
      
    }
    
    $paises = $this->Ubicacion->getPaises(); //lista de paises
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para el menu lateral
    $this->set(compact('data','debug','listaNegocios','negocio_id','negocio','paises','sucursal'));
  }
  
  //funcion para agregar caja de texto dinamicamente a los formularios de agregar y editar
  public function addTelToForm($index=null){
    $this->layout='ajax';
    $this->set(compact('index'));
  }
  
  //funcion para obtener la lista de departamentos mediante ajax
  public function getDepartamentos($pais_id=null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    $this->layout = 'ajax';
    $this->loadComponent('Ubicacion');
    
    $departamentos = $this->Ubicacion->getDepartamentos($pais_id);
            
    $this->set(compact('departamentos'));
  }
  
  //funcion para obtener la lista de municipios mediante ajax
  public function getMunicipios($departamento_id=null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    $this->layout = 'ajax';
    $this->loadComponent('Ubicacion');
    
    $municipios = $this->Ubicacion->getMunicipios($departamento_id);
            
    $this->set(compact('municipios'));
  }
  
  //funcion para editar la informacion de una sucursal
  function editar($sucursal_id=null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    //verificar permiso de crear sucursales
    if(!isset($this->perRol['crear_sucursales']) || $this->perRol['crear_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'contain'=>[
        'Telefonos', 'Negocios'
      ]
    ]);
    $negocio_id = $sucursal['negocio_id'];
    
    //verificar que la sucursal que intenta ver le pertenezca
    if($sucursal->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
        
    $this->set('titulo',__('Editar sucursal'));  
    $this->set('jquery_mask',1); //mascara de campos 
    $this->set('ms_general',1);
    $this->set('editar_sucursal',1); 
    $debug = 0;
    
    $data = null;
    
    if($this->request->is(['post','put'])){
      $data = $this->limpiarRedesSociales($this->request->data); //captura de datos
      
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
          'prefix'=>false,
          'controller'=>'Sucursales',
          'action'=>'editar',
          $sucursal->id,
          $this->tokenFalso
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }    
    
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para el menu lateral
    $this->set(compact('debug','listaNegocios','negocio_id','data','sucursal_id','sucursal'));
  }
  
  //funcion para editar la ubicacion de la sucursal
  public function editarMapa($sucursal_id=null){
    
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    //verificar permiso para crear sucursales
    if(!isset($this->perRol['crear_sucursales']) || $this->perRol['crear_sucursales']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'contain'=>'Negocios'
    ]);
    $negocio_id = $sucursal['negocio_id'];
    
    //verificar que la sucursal que intenta ver le pertenezca
    if($sucursal->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->loadComponent('Ubicacion');
    $this->set('titulo',__('Editar mapa/ubicaci&oacute;n'));
    $this->set('editar_sucursal',1); 
    $this->set('ms_mapa',1); //activo editar general
    $this->set('mapa_nuevo',1); //css de mapa      
    $debug = 0;
    
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
          'prefix'=>false,
          'controller'=>'Sucursales',
          'action'=>'editarMapa',
          $sucursal_id,
          $this->tokenFalso
        ]);
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }
    
    $paises = $this->Ubicacion->getPaises(); //lista de paises   
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para menu lateral
    $this->set(compact('debug','listaNegocios','negocio_id','data','sucursal_id','sucursal','paises'));
  }
  
  //editar galeria de imagenes para la sucursal
  public function editarImg($sucursal_id=null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    //verificar permiso para crear sucursales y cargar imagenes
    if((!isset($this->perRol['crear_sucursales']) || $this->perRol['crear_sucursales']==0) || 
      (!isset($this->perRol['cargar_elim_imagenes']) || $this->perRol['cargar_elim_imagenes']==0)){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $sucursalesTable = TableRegistry::get('Sucursales');
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'contain'=>[
        'Imagenes', 'Negocios'
      ]
    ]);
    $negocio_id = $sucursal['negocio_id'];
    
    //verificar que la sucursal que intenta ver le pertenezca
    if($sucursal->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    //verificar si se esta viendo desde un telefono para cambiar en mensaje de las instrucciones en la vista
    $this->loadComponent('RequestHandler');    
    $isMobile = false;
    if ($this->RequestHandler->isMobile()){
      $isMobile = true;
    }
    
    $this->set('titulo',__('Im&aacute;genes'));  
    $this->set('editar_sucursal',1); 
    $this->set('ms_imagenes',1); //activo en imagenes
    $this->set('jsupload',1); //upload js
    $this->set('jquery_ui',1); //jquery ui js
    $max_upload = $this->config['negocios_img_max_peso'] * 1024; //bytes
    $debug = 0;
    
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
        'prefix'=>false,
        'controller'=>'Sucursales',
        'action'=>'editarImg',
        $sucursal_id, $this->tokenFalso
      ]);
    }
    
    
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para menu lateral
    $this->set(compact('debug','listaNegocios','negocio_id','sucursal','max_upload','sucursal_id','isMobile'));
  }
  
  //funcion para cargar las imagenes mediante ajax
  public function cargarImagenes($negocio_id=null, $sucursal_id=null) {   
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    $this->layout='ajax';
    $this->autoRender = false;    
    $this->loadComponent('Archivos');
    
    /*obtener contador de imagenes actual de la sucursal para verificar el limite de imagens permitido*/
    $imagenesTable = TableRegistry::get('Imagenes');
    $query = $imagenesTable->find();
    $count = $query->func()->count('*');
    $res = $query
      ->select(['count' => $count])
      ->where([
        'negocio_id' => $negocio_id,
        'sucursal_id' => $sucursal_id
      ])
      ->execute()->fetch('assoc')
    ;
    
    if($res['count'] >= $this->config['negocios_sucursal_cant_imagenes']){ //mandar error si llega al limite de imagenes           
      $custom_error['jquery-upload-file-error']="L&iacute;mite alcanzado";
      echo json_encode($custom_error);
      die();
    }


    if ($this->request->is(['post'])) {
      //crear directorio si no existe     
      $ruta = WWW_ROOT. DS ."img". DS ."neg". DS .$negocio_id. DS .$sucursal_id; //ruta de guardado   
      if(!file_exists($ruta)){
        /*$old = umask(0);
        mkdir($ruta, 0777);
        umask($old);*/
        
        $folder = new Folder();
        $folder->create($ruta, 0777);
      }
      
      $ret = array();      
      $error = $_FILES["file"]["error"];
      $custom_error= array();
      //You need to handle  both cases
      //If Any browser does not support serializing of multiple files using FormData() 
      if (!is_array($_FILES["file"]["name"])) { //single file                
        
        
        $fecha = date_timestamp_get(new \DateTime('now')); //fecha de carga
        $nombre_img = md5($fecha.$_FILES["file"]["name"]); //generar md5 como base para el nombre de la imagen
        
        //acortar nombre a 10 caracteres
        $sub1 = substr($nombre_img, 0,5); //primeros 5 caracteres
        $sub2 = substr($nombre_img, -5);  //ultimos 5 caractered  
        $nombre_img = $sub1.$sub2; //unir para formar nombre final
        
        $data = null;
        $data['negocio_id']=$negocio_id;
        $data['sucursal_id']=$sucursal_id;
        $data['orden'] = 999;
        $data['nombre'] = $nombre_img;
        
        $image_size = $this->Archivos->getImageSize($_FILES["file"]);
        $data['ancho'] = (int) $image_size['ancho'];
        $data['alto'] = (int) $image_size['alto'];
        
        $imagen = $imagenesTable->newEntity($data);
        
        //si la imagen llega a disco entonces guardar en la base
        if($this->Archivos->cargarImagenes($_FILES["file"], $negocio_id, $sucursal_id, $nombre_img)){
          $imagenesTable->save($imagen);
        }else{
          $custom_error['jquery-upload-file-error']="Im&aacute;gen no pudo ser guardada";
          echo json_encode($custom_error);
          die();
        }
        
        $fileName = $_FILES["file"]["name"];
        $ret[] = $fileName;        
       
      } else {  //Multiple files, file[]
        
        /*$fileCount = count($_FILES["file"]["name"]);
        for ($i = 0; $i < $fileCount; $i++) {
          
          //get ultimo numero de orden
          $imagenesTable = TableRegistry::get('Imagenes');

          $data = null;
          $data['negocio_id']=$negocio_id;
          $data['sucursal_id']=$sucursal_id;
          $data['orden'] = 999;

          $imagen = $imagenesTable->newEntity($data);
          
          if($imagenesTable->save($imagen)){
            $fileName = $_FILES["file"]["name"][$i];
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], $output_dir . $fileName);
            $formato = $_FILES["file"]["type"][$i];
            $ret[] = $fileName;
          }
        }*/
        
      }
      
      echo json_encode($ret);
    }
  }
  
  //funcion para guardar el orden de las imagenes mediante ajax
  public function guardarOrdenImagenes(){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    $this->layout= 'ajax';
    $orden = null;
    
    $imagenesTable = TableRegistry::get('Imagenes');
    
    if($this->request->is(array('post'))){
      $orden = $_POST['order'];
      $orden = filter_var_array($orden);    
      
      foreach ($orden as $img){ //guardar el orden para cada imagen
        $imagenesTable->updateAll([
          'orden'=>(int)$img['orden']
        ], [
          'id'=>(int)$img['id']
        ]);
      }      
    }
    
    $this->set(compact('orden'));
  }
  
  //funcion para mostrar imagenes luego de ser cargadas por ajax
  public function mostrarImagenes($negocio_id=null, $sucursal_id = null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    $this->layout = 'ajax';
    
    //verificar si se esta viendo desde un telefono para cambiar en mensaje de las instrucciones en la vista
    $this->loadComponent('RequestHandler');    
    $isMobile = false;
    if ($this->RequestHandler->isMobile()){
      $isMobile = true;
    }
    
    $imagenes = null;    
    if($this->request->is(['ajax','post'])){
      $imagenesTable = TableRegistry::get('Imagenes');
      $imagenes = $imagenesTable->find('all', [
        'conditions'=>[
          'negocio_id'=>$negocio_id,
          'sucursal_id'=>$sucursal_id
        ],
        'order'=>[
          'orden'=>'asc',
          'id'=>'asc'
        ]
      ]);
    }
    
    $this->set(compact('imagenes','isMobile'));
  }
}
