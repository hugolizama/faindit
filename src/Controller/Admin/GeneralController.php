<?php

namespace App\Controller\Admin;
use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class GeneralController extends AppController {
  public $layout = 'admin';
  public $menu = 'menu-general';
  
  function beforeFilter(\Cake\Event\Event $event) {
    parent::beforeFilter($event);
  }


  //funcion para obtener el tamanio de un directorio
  function tamanioDir($ruta) {
    $tamanio = 0;
    foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($ruta)) as $archivo){
        $tamanio+=$archivo->getSize();
    }
    return round($tamanio / 1024 / 1024, 2).' MB';
  } 
  
  public function index(){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }    
            
    $this->set('general_activo', 1); //top menu
    $this->set('menu_admin', $this->menu); //sidebar
    
    $conn = ConnectionManager::get('default');
    
    $nombre_bd = $conn->config()['database'];
    
    
    /*get version de mysql*/
    $bd_version=$conn->execute('SELECT version() as v')->fetch('assoc')['v'];
    
    /*get tamanio de base*/
    $bd_tamanio = $conn->execute('SELECT table_schema nombre, 
      concat(round(sum( data_length + index_length ) / 1024 / 1024, 2),\' \',\'MB\')  tamanio
      FROM information_schema.TABLES 
      where table_schema = \''.$nombre_bd.'\'')->fetch('assoc')['tamanio']; 
    
    /*get cantidad de usuarios activos*/
    $cant_usuarios = $conn
      ->newQuery()
      ->select('count(*) as cant')
      ->from('usuarios') 
      ->where('estado>0')
      ->execute()
      ->fetch('assoc');
    
    
    /*cantidad de negocios*/
    $cant_negocios = $conn
      ->newQuery()
      ->select('count(*) as cant')
      ->from('negocios')
      ->where(['admin_habilitado'=>1])
      ->execute()
      ->fetch('assoc')
      ;
    
    /*cantidad de sucursales*/
    $cant_sucursales = $conn
      ->newQuery()
      ->select('count(*) as cant')
      ->from('sucursales')
      ->where('principal=0')
      ->execute()
      ->fetch('assoc')
      ;    
    
    /*cantidad de sucursales habilitadas*/
    $cant_sucursales_habilitadas = $conn
      ->newQuery()
      ->select('count(*) as cant')
      ->from('sucursales')
      ->where(['principal=0', 'habilitado=1'])
      ->execute()
      ->fetch('assoc')
      ;
    
    /*cantidad de categorias esperando aprobacion*/
    $cant_categorias = $conn
      ->newQuery()
      ->select('count(*) as cant')
      ->from('categorias_sugerencias')
      ->where('estado=0')
      ->execute()
      ->fetch('assoc')
      ;  
    
    /*Maxima cantidad de busquedas en una fecha*/
    $max_busquedas = $conn
      ->newQuery()
      ->select('date_format(fecha, \'%d-%m-%Y\') as fecha, contador as max')
      ->from('estadisticas')
      ->order(['contador'=>'desc'])
      ->limit(1)
      ->execute()
      ->fetch('assoc')
      ; 
    
    /*cantidad de busquedas totales*/
    $cant_busquedas = $conn
      ->newQuery()
      ->select('sum(contador) as suma')
      ->from('estadisticas')
      ->execute()
      ->fetch('assoc')
      ; 
    
     /*cantidad de busquedas hoy*/
    $fecha_hoy = new \DateTime('now');
    $fecha_hoy = $fecha_hoy->format('Y-m-d');
    $cant_busquedas_hoy = $conn
      ->newQuery()
      ->select('contador')
      ->from('estadisticas')
      ->where("fecha = '$fecha_hoy'")
      ->execute()
      ->fetch('assoc')
      ;
    
    
    /*promedio de busquedas por dia*/
    $fecha1 = strtotime('now');
    $fecha2 = strtotime($this->config['sitio_fecha_inicio']);    
    $fecha3 = ($fecha1 - $fecha2) / (24*60*60);      
         
    $promedio = number_format($cant_busquedas['suma']/$fecha3, 2);
    
    $tamanioImg = $this->tamanioDir('img/neg/');
        
    $this->set(compact('bd_version', 'bd_tamanio', 'cant_usuarios', 'tamanioImg', 'cant_negocios', 
      'cant_sucursales','cant_categorias', 'max_busquedas', 'cant_busquedas', 'promedio','cant_busquedas_hoy',
      'cant_sucursales_habilitadas'));
  }
  
  
  /*funcion para mostrar mas estadisticas*/
  function masEstadisticas($modo = null){
    if($this->isLogueadoAdmin()){ //verficar si esta logueado como administrador
      return $this->isLogueadoAdmin();
    }
    
    $this->set('jquery_ui', 1);
    $this->set('amcharts', 1);
    $this->set('general_activo', 1); //top menu
    $this->set('menu_admin', $this->menu); //sidebar
    
    $fechaInicio = explode('-', $this->config['sitio_fecha_inicio']);
    $fechaHoy = date('d-m-Y');
    $fechaHoy = explode('-', $fechaHoy);   
    
    $this->set(compact('modo', 'fechaInicio', 'fechaHoy'));
  }
  
  /*Obtener los datos para la grafica de busquedas*/
  function getDatosBusquedas (){
    $this->layout = 'ajax';
    $this->autoRender = false;    
    
    $modo = filter_input(INPUT_POST, 'modo');
    $fecha1 = filter_input(INPUT_POST, 'fecha1');
    $fecha2 = filter_input(INPUT_POST, 'fecha2');
    
    $estadisticasTable = \Cake\ORM\TableRegistry::get('estadisticas');    
    $arrayDatos = array();
    
    if($modo == 'dias'){
      $estadisticas = $estadisticasTable->find();
      $fecha = $estadisticas->func()->date_format(array(
       'fecha'=>'literal',
        "'%Y-%m-%d'" => 'literal'
      ));
      
      $estadisticas
        ->select(array("fecha"=>$fecha, 'contador'))
        ->where(array("date_format(fecha, '%Y-%m-%d') between '$fecha1' and '$fecha2'"))
        ->order("date_format(fecha, '%Y-%m-%d')");      
            
      foreach ($estadisticas as $e) {
        $arrayDatos[] = array(
          'fecha'=>$e['FechaDiasFormat'], 'contador'=>$e['contador']
        );
      }
    
    }elseif($modo == 'meses'){
      
      $estadisticas = $estadisticasTable->find();
      $fecha = $estadisticas->func()->date_format(array(
       'fecha'=>'literal',
        "'%Y-%m'" => 'literal'
      ));
      
      $contador = $estadisticas->func()->sum('contador');
      
      $estadisticas
        ->select(array("fecha"=>$fecha, 'contador'=>$contador))
        ->where(array("date_format(fecha, '%Y-%m') between '$fecha1' and '$fecha2'"))
        ->group(array($fecha))
        ->order("date_format(fecha, '%Y-%m')");          
          
      foreach ($estadisticas as $e) {
        $arrayDatos[] = array(
          'fecha'=>$e['FechaMesesFormat'], 'contador'=>$e['contador']
        );
      }
    }
    
    echo json_encode($arrayDatos);
    
  }
  
  /*Obtener los datos para la grafica de registros*/
  function getDatosRegistros (){
    
    //$this->set('general_activo', 1); //top menu
    //$this->set('menu_admin', $this->menu); //sidebar
    $this->layout = 'ajax';
    $this->autoRender = false;    
    
    $modo = filter_input(INPUT_POST, 'modo');
    $fecha1 = filter_input(INPUT_POST, 'fecha1');
    $fecha2 = filter_input(INPUT_POST, 'fecha2');
    
    $negociosTable = \Cake\ORM\TableRegistry::get('negocios');    
    $arrayDatos = array();
    
    if($modo == 'dias'){
      $negocios = $negociosTable->find();
      $fecha = $negocios->func()->date_format(array(
       'fecha_creacion'=>'literal',
        "'%Y-%m-%d'" => 'literal'
      ));
      
      $contador = $negocios->func()->count('id');
      
      $negocios
        ->select(array("fecha_creacion"=>$fecha, 'contador' => $contador))
        ->where(array("date_format(fecha_creacion, '%Y-%m-%d') between '$fecha1' and '$fecha2'"))
        ->group(array($fecha))
        ->order($fecha);      
            
      foreach ($negocios as $n) {
        $arrayDatos[] = array(
          'fecha'=>$n['FechaCreacionDiasFormat'], 'contador'=>$n['contador']
        );
      }
    
    }elseif($modo == 'meses'){
      
      $negocios = $negociosTable->find();
      $fecha = $negocios->func()->date_format(array(
       'fecha_creacion'=>'literal',
        "'%Y-%m'" => 'literal'
      ));
      
      $contador = $negocios->func()->count('id');
      
      $negocios
        ->select(array("fecha_creacion"=>$fecha, 'contador'=>$contador))
        ->where(array("date_format(fecha_creacion, '%Y-%m') between '$fecha1' and '$fecha2'"))
        ->group(array($fecha))
        ->order($fecha);          
          
      foreach ($negocios as $n) {
        $arrayDatos[] = array(
          'fecha'=>$n['FechaCreacionMesesFormat'], 'contador'=>$n['contador']
        );
      }
    }
    
    echo json_encode($arrayDatos);
    
  }
}