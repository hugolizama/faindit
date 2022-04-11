<?php

namespace App\Controller;
use \Cake\ORM\TableRegistry;
use \Cake\Network\Exception\ForbiddenException;
use Cake\Filesystem\Folder;
use \Cake\Core\Configure;

class NegociosController extends AppController{  
  public $tokenFalso = null;
  
  public function beforeFilter(\Cake\Event\Event $event) {
    //evitar llamada beforeFilter a las llamadas ajax
    if(!$this->request->is(['ajax']) || $this->request->action == 'cargarImagenes' || $this->request->action == 'sugerenciaCategorias'){
      parent::beforeFilter($event);      
            
      /*genera un token falso para la direccion web*/
      $tokenFalso = $this->generarTokenFalso();
      $this->tokenFalso = $tokenFalso;
      $this->set('tokenFalso',$tokenFalso);
    }
  }  
  
  //sin utilizar de momento
  public function index(){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    return $this->redirect([
      'prefix'=>false,
      'controller'=>'Usuarios',
      'action'=>'perfil'
    ]);
  }
      
  //funcion para agregar un nuevo negocio
  public function agregar(){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    $this->loadComponent('Ubicacion');
    $this->loadComponent('Archivos');
    
    //veriricar permiso para crear negocios
    if(!isset($this->perRol['crear_negocios']) || $this->perRol['crear_negocios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }    
       
    $this->set('titulo',__('Agregar negocio'));
    $this->set('jquery_tags',1); //categorias
    $this->set('jquery_mask',1); //mascara de campos
    $this->set('mapa_nuevo',1); //css de mapa
    $this->set('activo_agregar_neg',1);
    $debug = 0; 
    
    $negociosTable = TableRegistry::get('Negocios');    
    $negocio=$negociosTable->newEntity();      
       
    $data = null;
    if($this->request->is('post')){
      $data = $this->limpiarRedesSociales($this->request->data); //captura de datos
            
      //array de datos para sucursales
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
      
      $negocio=$negociosTable->newEntity($data, [
        'associated' => [ //especificar las asociaciones para tambien guadar sus datos
          'Categorias', 'Sucursales' => [
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
        
        $this->Flash->success(__('Tu negocio ha sido publicado. En esta sección puedes modificar la información de tu 
          negocio así como administrar las imágenes que quieres que aparezcan en su perfil.'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);        
        
        return $this->redirect([
          'prefix'=>false,
          'controller'=>'Negocios',
          'action'=>'editar',
          $negocio->id,
          $this->tokenFalso
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }
        
    $paises = $this->Ubicacion->getPaises();    
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para menu lateral
    
    $this->set(compact('negocio', 'debug', 'data','paises','listaNegocios'));
  }
  
  
  /*funcion para obtener las categorias para los negocios*/
  public function getCategorias($cat=null){
    $this->layout='ajax';
    $this->autoRender = false;
    
    $categoriasTable = TableRegistry::get('Categorias');
    $categorias = $categoriasTable->find('all', [
      'fields'=>[
        'id','nombre'
      ],
      'conditions'=>[
        'nombre like'=> "%".$cat."%"
      ],
      'limit'=> (isset($this->config['negocios_cat_visibles'])) ? $this->config['negocios_cat_visibles'] : 10
    ])->toArray();
    
    echo json_encode($categorias);
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
  
  //funcion para agregar caja de texto dinamicamente a los formularios de agregar y editar
  public function addTelToForm($index=null){
    $this->layout='ajax';
    $this->set(compact('index'));
  }
  
  //funcion para editar la informacion de un negocio
  public function editar($negocio_id=null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }    
    
    //verificar permiso de crear negocio
    if(!isset($this->perRol['crear_negocios']) || $this->perRol['crear_negocios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->loadComponent('Archivos');
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id, [ //obtener la informacion del negocio
      'contain'=>[        
        'Categorias', 'Sucursales' => function($q){
          return $q
            ->where(['Sucursales.principal'=>1])
            ->contain(['Telefonos']);
        }
      ]      
    ]);
      
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
    
    //verificar que el negocio que intenta ver le pertenezca
    if($negocio->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
            
    $this->set('titulo',__('Editar negocio'));
    $this->set('no_cache',1); //no cache
    $this->set('me_general',1); //activo editar general
    $this->set('jquery_tags',1); //categorias
    $this->set('jquery_mask',1); //mascara de campos
    $debug = 0;
    
    $data=null;
    if($this->request->is(['post','put'])){
      $data = $this->limpiarRedesSociales($this->request->data); //captura de datos
      
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
        
        //Notificar al administrador que un negocio deshabilitado ha sido modificado para revision
        if($negocio['admin_habilitado']==0){
          $this->loadComponent('Correo');
          
          $this->Correo->notificarAdminNegocioSuspendido(
            $negocio->id, $negocio->nombre, $negocio->razon_deshabilitado
          );
          
          $this->Flash->success(__('Tu negocio ha sido notificado para revisión.'), [
            'key'=>'notificado',
            'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
          
        }else{
          $this->Flash->success(__('Tu negocio ha sido actualizado.'), [
            'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }
                
        return $this->redirect([
          'prefix'=>false,
          'controller'=>'Negocios',
          'action'=>'editar',
          $negocio->id,
          $this->tokenFalso
        ]);
        
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));        
      } //fin save
    }
    
    
    $listaNegocios = $this->getListaNegocios(); //lista de negocios
    $this->set(compact('debug','listaNegocios','negocio_id','data','negocio'));
  }
  
  /*funcion para editar la ubicacion del negocio*/
  public function editarMapa($negocio_id=null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }     
    
    //verificar permiso para crear negocio
    if(!isset($this->perRol['crear_negocios']) || $this->perRol['crear_negocios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
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
    
    //verificar que el negocio que intenta ver le pertenezca
    if($negocio->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }    
    
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
    
    $this->loadComponent('Ubicacion');
    $this->set('titulo',__('Editar mapa/ubicaci&oacute;n'));
    $this->set('me_mapa',1); //activo editar general
    $this->set('mapa_nuevo',1); //css de mapa  
    $debug = 0;
    
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
        
        //Notificar al administrador que un negocio deshabilitado ha sido modificado para revision
        if($negocio['admin_habilitado']==0){
          $this->loadComponent('Correo');
          
          $this->Correo->notificarAdminNegocioSuspendido(
            $negocio->id, $negocio->nombre, $negocio->razon_deshabilitado
          );
          
          $this->Flash->success(__('Tu negocio ha sido notificado para revisión.'), [
            'key'=>'notificado',
            'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
          
        }else{        
          $this->Flash->success(__('La ubicación de tu negocio ha sido actualizada.'), [
            'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);
        }
        
        return $this->redirect([
          'prefix'=>false,
          'controller'=>'Negocios',
          'action'=>'editarMapa',
          $negocio->id,
          $this->tokenFalso
        ]);
      }else{
        $this->Flash->error(__('Los datos no se han guardado, intente de nuevo.'));
      }
    }
    
    $paises = $this->Ubicacion->getPaises(); //lista de paises   
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para el menu
    $this->set(compact('debug','listaNegocios','negocio_id','data','negocio','paises'));    
  }
  
  
  //funcion para editar la galeria de imagenes
  public function editarImg($negocio_id=null){  
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }    
    
    //verificar permiso para crear negocio y cargar imagenes
    if((!isset($this->perRol['crear_negocios']) || $this->perRol['crear_negocios']==0) || 
      (!isset($this->perRol['cargar_elim_imagenes']) || $this->perRol['cargar_elim_imagenes']==0)){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id, [
      'contain'=>[
        'Imagenes' => [
          'conditions' => [
            'Imagenes.sucursal_id' => 0
          ]
        ]
      ]
    ]);
    
    //verificar que el negocio que intenta ver le pertenezca
    if($negocio->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    if($negocio['admin_habilitado']==0){
      $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$negocio['razon_deshabilitado'].'</u>'));
    }
    
    //verificar si se esta viendo desde un telefono para cambiar en mensaje de las instrucciones en la vista
    $this->loadComponent('RequestHandler');    
    $isMobile = false;
    if ($this->RequestHandler->isMobile()){
      $isMobile = true;
    }    
    
    $this->set('titulo',__('Im&aacute;genes'));  
    $this->set('me_imagenes',1); //activo en imagenes
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
      
      //Notificar al administrador que un negocio deshabilitado ha sido modificado para revision
      if($negocio['admin_habilitado']==0){
        $this->loadComponent('Correo');

        $this->Correo->notificarAdminNegocioSuspendido(
          $negocio->id, $negocio->nombre, $negocio->razon_deshabilitado
        );

        $this->Flash->success(__('Tu negocio ha sido notificado para revisión.'), [
          'key'=>'notificado',
          'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);

      }
      
      $this->redirect([
        'prefix'=>false,
        'controller'=>'Negocios',
        'action'=>'editarImg',
        $negocio_id, $this->tokenFalso
      ]);
    }
    
    $listaNegocios = $this->getListaNegocios(); //lista de negocios para el menu lateral
    $this->set(compact('debug','listaNegocios','negocio_id','negocio','max_upload','isMobile'));
  }
  
  //funcion para cargar las imagenes mediante ajax
  public function cargarImagenes($negocio_id=null, $sucursal_id=null) {   
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    $this->layout='ajax';
    $this->autoRender = false;    
    $this->loadComponent('Archivos');    
    
    if($sucursal_id==null){
      $sucursal_id = 0;
    }
    
    /*obtener contador de imagenes actual del negocio para verificar el limite de imagens permitido*/
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
    
    if($res['count'] >= $this->config['negocios_cant_imagenes']){ //mandar error si llega al limite de imagenes          
      $custom_error['jquery-upload-file-error']="L&iacute;mite alcanzado";
      echo json_encode($custom_error);
      die();
    }


    if ($this->request->is(['post'])) {
      //crear directorio si no existe    
      $output_dir = WWW_ROOT. DS ."img". DS ."neg". DS .$negocio_id. DS .$sucursal_id; //ruta de guardado     
      if(!file_exists($output_dir)){             
        $folder = new Folder();
        $folder->create($output_dir, 0777);
      }
      
      $ret = array();      
      $error = $_FILES["file"]["error"];
      $custom_error= array();
      //You need to handle  both cases
      //If Any browser does not support serializing of multiple files using FormData() 
      if (!is_array($_FILES["file"]["name"])) { //single file
                
        if($res['count'] >= $this->config['negocios_cant_imagenes']){ //mandar error si llega al limite de imagenes          
          $custom_error['jquery-upload-file-error']="L&iacute;mite alcanzado";
          echo json_encode($custom_error);
          die();
        }
        
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
    
    if($sucursal_id==null){
      $sucursal_id = 0;
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
    
    $this->set(compact('imagenes', 'isMobile'));
  }
  
  //funcion para eliminar un negocio
  public function eliminarNegocio($negocio_id =  null){
    if($this->isLogueado()){ //verficar si esta logueado
      return $this->isLogueado();
    }
    
    //verificar permiso de crear negocio
    if(!isset($this->perRol['crear_negocios']) || $this->perRol['crear_negocios']==0){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($negocio_id);
    $this->loadComponent('Archivos');
    
    //verificar que el negocio que intenta ver le pertenezca
    if($negocio->usuario_id!=$this->cookieUsuario['id']){
      throw new ForbiddenException($this->getMensajeError(403));
    }
    
    $this->layout = 'ajax';
    $this->autoRender = false;    
    
    $folder = new Folder();
    if($folder->delete('img/neg/'.$negocio_id)){ //eliminar directorio de imagenes
      if($negociosTable->delete($negocio)){ //eliminar negocio y datos asociados
        $this->Flash->success(__('Tu negocio ha sido eliminado con éxito.'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]); 
        
        return $this->redirect([
          'prefix'=>false,
          'controller'=>'Usuarios',
          'action'=>'perfil'
        ]);
      }else{
        $this->Flash->error(__('Tu negocio no ha sido eliminado. Intenta de nuevo.'), [
          //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
        ]);
        
        return $this->redirect([
          'prefix'=>false,
          'controller'=>'Negocios',
          'action'=>'editar',
          $negocio_id, $this->tokenFalso
        ]);
      }
    }
  }
  
  
  
  /*funcion para guardar las sugerencias de categorias*/
  public function sugerenciaCategorias(){
    $this->layout = 'ajax';
    $this->autoRender = false;
    
    $data = null;
    $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Error de solicitud. Intente de nuevo.<span>'];
    
    if($this->request->is(['post', 'ajax'])){     
      $categoria = filter_input(INPUT_POST, 'categoria'); 
      
      if($categoria==''){
        $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">La categor&iacute;a es requerida.<span>'];
        echo json_encode($respuesta);
        exit();
      }
      
      $categoriasTable = TableRegistry::get('CategoriasSugerencias');
      $categoriaBuscar = $categoriasTable->find('all', [
        'conditions'=>[
          'nombre'=>$categoria
        ]
      ])->first();
      
      
      if(!empty($categoriaBuscar)){
        //sugerencia repetida
        
        switch ($categoriaBuscar['estado']) {
          case 0:
            $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Esta sugerencia ya se encuentra en revisi&oacute;n.<span>'];
            break;
          case 1:
            $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Esta sugerencia ya ha sido aprobada anteriormente.<span>'];
            break;
          case 2:
            $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Esta sugerencia ha sido regitrada anteriormente y no ha sido aprobada.<span>'];
            break;

          default:
            break;
        }
        
      }else{
        //sugerencia no encontrada, guardar
        $data['nombre']=$categoria;
        $data['usuario_id']=$this->cookieUsuario['id'];
        
        $categoriaEntity = $categoriasTable->newEntity($data);
        
        if($categoriasTable->save($categoriaEntity)){
          $respuesta = ['cod'=>1, 'mensaje'=>'<span style="color:green; font-size: 12px;">Tu sugerencia ha sido guardada.<span>'];
        }
      }
    }
    
    
    echo json_encode($respuesta);
  }
}

