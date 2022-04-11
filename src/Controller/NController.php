<?php

namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;

class NController extends AppController{
  public function index($sucursal_id=null, $slug=null){
    $this->set('jquery_ui', 1);
    $this->set('photoswipe',1);
    $this->set('meta_facebook',1);
    $this->set('mostrar_buscador_en_navbar', 1);
    /*activar autocompletado de buscador*/
    $this->js_buscador = 1;
    $this->set('js_buscador', $this->js_buscador);
    /*fin activar autocompletado de buscador*/
    
        
    $tq = urldecode(filter_input(INPUT_GET, 'tq')); //tipo de busqueda. 0 - indeterminado, 1 - categoria, 2 - negocio
    $en = urldecode(filter_input(INPUT_GET, 'en')); //donde se busca
    $te = filter_input(INPUT_GET, 'te'); //tipo de busqueda. 0 - indeterminado, 1 - departamento, 2 - municipio
    
    if($tq===null){
      $tq = 0;
    }
    
    if($te===null){
      $te = 0;
    }
    
    if($tq === 0 && $te === 0){
      $this->request->session()->delete('sessionQue');
      $this->request->session()->delete('sessionEn');
    }
    
    //obtener todos los datos de la sucursal
    $sucursalesTable = TableRegistry::get('Sucursales');        
    $sucursal = $sucursalesTable->get($sucursal_id, [
      'contain'=>[
        'Telefonos' => function($q){
          return $q
            ->order(['Telefonos.tipo'=>'asc']);
        }, 
        'Paises', 'Departamentos', 'Municipios', 'Negocios' 
      ]
    ]);
        
        
    if($sucursal['negocio']['admin_habilitado']==0){ //verificar si el negocio esta deshabilitado      
      //solo el dueno de la sucursal o un admin con permiso de ver el panel de configuraciones de negocios y sucursales
      if($sucursal['usuario_id']==$this->cookieUsuario['id']){
        $urlPanel = Router::url([
          'controller'=>'Sucursales', 'action'=>'index',
          $sucursal['negocio_id'], $this->config['negocios_opcion_defecto'],
          $this->generarTokenFalso()
        ]);
        $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$sucursal['negocio']['razon_deshabilitado'].'</u>'));
        
      }else if(isset($this->perRol) && $this->perRol['ver_panel_neg_sucursales']==1){
        $this->Flash->warning(__('Este negocio ha sido deshabilitado por un administrador por la siguiente razón: <u>'.$sucursal['negocio']['razon_deshabilitado'].'</u>'));
      }else{
        throw new NotFoundException($this->getMensajeError(404));
      }
    }
    
    
        
    if($sucursal['habilitado']==0){ //verificar si la sucursal esta deshabilitado      
      //solo el dueno de la sucursal o un admin con permiso de ver el panel de configuraciones de negocios y sucursales
      if($sucursal['usuario_id']==$this->cookieUsuario['id']){
        $urlPanel = Router::url([
          'controller'=>'Sucursales', 'action'=>'index',
          $sucursal['negocio_id'], $this->config['negocios_opcion_defecto'],
          $this->generarTokenFalso()
        ]);
        $this->Flash->warning(__('Esta sucursal esta deshabilitada para los dem&aacute;s usuarios. Para habilitarla 
          debes ir a tu <a class="alert-link" href="'.$urlPanel.'">panel de administración</a>'));
        
      }else if(isset($this->perRol) && $this->perRol['ver_panel_neg_sucursales']==1){
        $this->Flash->warning(__('Esta sucursal ha sido deshabilitada por el usuario.'));
      }else{
        throw new NotFoundException($this->getMensajeError(404));
      }
    }
      
    //obtener las imagenes de la sucursal y las del negocio en general (sucursal 0)
    $imagenesTable= TableRegistry::get('Imagenes');
    $imagenes = $imagenesTable->find('all',[
      'conditions'=>[
        'negocio_id'=>$sucursal['negocio_id'],
        'sucursal_id in'=>[$sucursal_id, 0]
      ],
      'order'=>[
        'sucursal_id'=>'desc',
        'orden'=>'asc'
      ]
    ])->toArray();
    
    //un admin puede ver las sucursales deshabilitadas en la lista select
    $cond_suc_deshabilitadas = 'Sucursales.habilitado=1';
    if(isset($this->perRol) && $this->perRol['ver_panel_neg_sucursales']==1){
      $cond_suc_deshabilitadas=null;
    }
    
    //obtener las sucursales del negocio    
    $negociosTable = TableRegistry::get('Negocios');
    $negocio = $negociosTable->get($sucursal['negocio_id'], [
      'contain'=>[
        'Sucursales'=> function($q) use ($cond_suc_deshabilitadas){
          return $q
            ->where([
              'Sucursales.pais_id'=>1,
              $cond_suc_deshabilitadas
            ])
            ->contain([
              'Departamentos','Municipios', 'Paises', 'Telefonos'
            ])
            ->order([
              'Departamentos.nombre'=>'asc',
              'Municipios.nombre'=>'asc',
              'Sucursales.nombre'=>'asc'
            ])
            ;
        },
        'Categorias' => function($x){
          return $x
            ->order(['Categorias.nombre'=>'asc']);
        }       
      ]
    ])->toArray();
    $this->set('titulo',$negocio['nombre']);
      
    /*NOTA:
     *Importante notar que la impresion de las sucursales en el select debe ser en mismo orden
     * que al imprimirse los marcadores en el mapa porque al seleccionar un opcion en el select
     * este toma el indice de la posicion para ubicar el marcador, no su id.      
     */
    $arraySucursales=array();  
    $defaultSucursal = null;
    
    foreach($negocio['sucursales'] as $suc){    
      $sucurusal_deshabilitada = ''; //limpiar texto
      $idMapa = $suc['id']."|".$suc['lat']."|".$suc['lng'];
      
      if($sucursal_id ==$suc['id']){ //establecer la sucursal seleccionada en el select
        $defaultSucursal = $idMapa;
      }
      
      if($suc['habilitado']==0){ //evaluar si esta deshabilitada la sucursal y agregar un asterisco para identificarla
        $sucurusal_deshabilitada = '*';
      }
      
      $arraySucursales[$suc['departamento']['nombre']. " - ".$suc['municipio']['nombre']][$idMapa]= $sucurusal_deshabilitada.$suc['nombre'];
    }
    
    
    /*agregar 1 al campo contador en la base de datos (orden por relevancia)*/
    $url_referer = $this->request->referer(true);    
    $sessionQue = $this->request->session()->read("sessionQue");
    if(strpos($url_referer, 'buscar') && $tq==1 && !$this->Cookie->check('neg.'.$negocio['nombre_slug'].'.'.$sessionQue) && !$this->Cookie->check('UsuarioAdmin')){
      //echo "sumar";
      
      $categoriasTable = TableRegistry::get('Categorias');
      $categoria=$categoriasTable->find('all',[
        'conditions'=>[
          'nombre'=>$sessionQue
        ]
      ])->first();     
      
      $negociosCategoriasTable = TableRegistry::get('NegociosCategorias');
      $negocioCategoria = $negociosCategoriasTable->find('all', [
        'conditions'=>[
          'negocio_id'=>$negocio['id'],
          'categoria_id'=>$categoria['id']
        ]
      ])->first();
      
      $data['contador'] = $negocioCategoria['contador'] + 1;
      
      $negocioCategoria = $categoriasTable->patchEntity($negocioCategoria, $data);
      $negociosCategoriasTable->save($negocioCategoria);
      
      $this->Cookie->configKey('neg', 'expires' ,'+2 hours');
      $this->Cookie->write('neg.'.$negocio['nombre_slug'].'.'.$sessionQue,1);      
    }
    /*fin agregar 1 al campo contador en la base de datos (orden por relevancia)*/
    
    //enlace para el link Editar
    $token_falso = $this->generarTokenFalso();    
    $url_editar = Router::url(array(
      'prefix'=>false, '_base'=>false, 'controller'=>'Negocios', 'action'=>'editar', $sucursal['negocio_id'], $token_falso
    ));
    
    if(isset($this->perRol['admin_neg_sucursales']) && $this->perRol['admin_neg_sucursales']==1){
      $url_editar = Router::url(array(
        'prefix'=>'admin', '_base'=>false, 'controller'=>'negociosSucursales', 'action'=>'editarNegocio', $sucursal['negocio_id']
      ));
    }
    
    $this->set(compact('sucursal','imagenes','arraySucursales', 'sucursal_id','negocio', 'defaultSucursal',
      'tq', 'en', 'te', 'url_editar'));
  }
  
  public function prueba(){
    $this->set('lightbox',1);
    $this->set('titulo','nombre de la empresa');
    
    
    //Prueba de publicar a facebook desde cakephp
    $this->loadComponent('Facebook');    
    $this->Facebook->publicar();
  }
}
