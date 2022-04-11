<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
//use Cake\ORM\TableRegistry;

class FacebookComponent extends Component {
  
  private $accessToken = "CAAKzT3gEzkEBAHG3HT15lZBlhdMcs4AfkeWZCGCAzFreE9JwnHLaORT0sgpqhX58Qs2ZAugnG6If4bY48dYQdVx4T6PevZCKT00ZBZB1MBJglcLZBNkWhcviytJ0cEQx4pgSULKV8UQVyO5i57veuZB3tv3dZCEoJfBGGJE72duvZAgOdZBjWBnS3L2MlI0tO4qzU0ZD";
  private $app_id = "760103850790465";
  private $app_secret = "3bb58c40b141993b43683d3e46a3c763";
  private $default_graph_version = "v2.5";
  
  public function publicarNuevoNegocio($data, $negocio_id, $sucursal_id){
    require_once ROOT.DS.'vendor'.DS.'facebook'.DS.'autoload.php'; //incluir libreria de facebook
    
    /*Datos generales*/
    $nombre = $data['nombre'];
    $nombre_slug = \Cake\Utility\Inflector::slug(mb_strtolower($nombre),'-');
    $descripcion = \Cake\Utility\Text::truncate($data['descripcion'], 400);
    
    //imagen
    $imagen_ruta = 'img/neg/'.$negocio_id.'/logo.jpg';
    if(!file_exists($imagen_ruta)){
      $imagen_ruta = 'img/no_logo.jpg';
    }
    $imagen_link = \Cake\Routing\Router::url('/'.$imagen_ruta, true);
    $negocio_link = \Cake\Routing\Router::url(['prefix' => false,'controller'=>'N', 'action'=>'index', $sucursal_id, $nombre_slug], true);
    /*Fin datos generales*/
    
    
    $fb = new \Facebook\Facebook([
      'app_id' => $this->app_id,
      'app_secret' => $this->app_secret,
      'default_graph_version' => $this->default_graph_version,
    ]);   
    
    //Parametros de facebook
    $params['message'] = $nombre." se ha registrado en #FainditElSalvador #Negocios #ElSalvador";
    $params['picture'] = $imagen_link;
    $params['link'] = $negocio_link;
    $params['name'] = $nombre;
    $params['description'] = $descripcion;
    $params['caption'] = "Faindit El Salvador";
    
    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->post('/me/feed', $params, $this->accessToken);

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      //echo 'Graph returned an error: ' . $e->getMessage();
      
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      //echo 'Facebook SDK returned an error: ' . $e->getMessage();
      
    }
  }
  
  
  public function publicarNuevaSucursal($negocio, $sucursal){
    
    require_once ROOT.DS.'vendor'.DS.'facebook'.DS.'autoload.php'; //incluir libreria de facebook
    
    /*Datos generales*/
    $negocio_id = $negocio['id'];
    $negocio_nombre = $negocio['nombre'];
    $negocio_nombre_slug = $negocio['nombre_slug'];
    $negocio_descripcion = \Cake\Utility\Text::truncate($negocio['descripcion'], 400);
    
    $sucursal_id = $sucursal['id'];
    $sucursal_nombre = $sucursal['nombre'];
    
    //imagen
    $imagen_ruta = 'img/neg/'.$negocio_id.'/logo.jpg';
    if(!file_exists($imagen_ruta)){
      $imagen_ruta = 'img/no_logo.jpg';
    }
    $imagen_link = \Cake\Routing\Router::url('/'.$imagen_ruta, true);
    $negocio_link = \Cake\Routing\Router::url(['prefix' => false,'controller'=>'N', 'action'=>'index', $sucursal_id, $negocio_nombre_slug], true);
    /*Fin datos generales*/
    
    
    $fb = new \Facebook\Facebook([
      'app_id' => $this->app_id,
      'app_secret' => $this->app_secret,
      'default_graph_version' => $this->default_graph_version,
    ]);   
    
    //Parametros de facebook
    $params['message'] = "Una nueva sucursal de ".$negocio_nombre." ha sido registrada en #FainditElSalvador #Negocios #ElSalvador";
    $params['picture'] = $imagen_link;
    $params['link'] = $negocio_link;
    $params['name'] = $sucursal_nombre;
    $params['description'] = $negocio_descripcion;
    $params['caption'] = "Faindit El Salvador";
    
    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->post('/me/feed', $params, $this->accessToken);

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      //echo 'Graph returned an error: ' . $e->getMessage();
      
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      //echo 'Facebook SDK returned an error: ' . $e->getMessage();
      
    }
    
  }
}
