<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * CakePHP TwitterComponent
 * @author hugo lizama
 */
class TwitterComponent extends Component {
  
  private $api_key = 'hDv75zcpZzRIEPNdWhBZ761Es';
  private $api_secret = 'IxEJABufr3xIUZau7vxfE3qIocwL9Iqs1bMUbVTDSOEm78oO5o';
  private $token = '4465116556-zyiRHD2XpZuj58mT6Sd7HEzDSAYPwfqxShyY3u1';
  private $token_secret = 'YWi5DtqD0p0292uQr0rmAEYHm1QaC3vtymSQRFCErw6SG';
  
  public function publicarNuevoNegocio($data, $negocio_id, $sucursal_id){
    require_once ROOT.DS.'vendor'.DS.'codebird-php'.DS.'codebird.php';
    
    /*Datos generales*/
    $nombre = $data['nombre'];
    $nombre_slug = \Cake\Utility\Inflector::slug(mb_strtolower($nombre),'-');
    //$descripcion = \Cake\Utility\Text::truncate($data['descripcion'], 400);
    
    //imagen
    $imagen_ruta = 'img/neg/'.$negocio_id.'/logo.jpg';
    if(!file_exists($imagen_ruta)){
      $imagen_ruta = 'img/no_logo.jpg';
    }
    $imagen_link = \Cake\Routing\Router::url('/'.$imagen_ruta, true);
    $negocio_link = \Cake\Routing\Router::url(['prefix' => false,'controller'=>'N', 'action'=>'index', $sucursal_id, $nombre_slug], true);
    /*Fin datos generales*/
    
    \Codebird\Codebird::setConsumerKey($this->api_key, $this->api_secret);
    
    $cb = \Codebird\Codebird::getInstance();
    $cb->setToken($this->token, $this->token_secret);
    
    $media_ids = [];
    $img_ids = $cb->media_upload([
      'media'=>$imagen_link
    ]);
    
    $media_ids[] = $img_ids->media_id_string;
    $media_ids = implode(',', $media_ids);
    
    $params = [
      'status' => $nombre." se ha registrado en #FainditElSalvador. $negocio_link #ElSalvador",
      //'media_ids' => $media_ids
    ];
    
    try {
      $reply = (array) $cb->statuses_update($params);
      //debug($reply);
    } catch (Exception $exc) {
      //echo "error";
    }
  }
  
  public function publicarNuevaSucursal($negocio, $sucursal){
    require_once ROOT.DS.'vendor'.DS.'codebird-php'.DS.'codebird.php';
    
    /*Datos generales*/
    $negocio_id = $negocio['id'];
    $negocio_nombre = $negocio['nombre'];
    $negocio_nombre_slug = $negocio['nombre_slug'];
    //$negocio_descripcion = \Cake\Utility\Text::truncate($negocio['descripcion'], 400);
    
    $sucursal_id = $sucursal['id'];
    //$sucursal_nombre = $sucursal['nombre'];
    
    //imagen
    $imagen_ruta = 'img/neg/'.$negocio_id.'/logo.jpg';
    if(!file_exists($imagen_ruta)){
      $imagen_ruta = 'img/no_logo.jpg';
    }
    $imagen_link = \Cake\Routing\Router::url('/'.$imagen_ruta, true);
    $negocio_link = \Cake\Routing\Router::url(['prefix' => false,'controller'=>'N', 'action'=>'index', $sucursal_id, $negocio_nombre_slug], true);
    /*Fin datos generales*/
    
    \Codebird\Codebird::setConsumerKey($this->api_key, $this->api_secret);
    
    $cb = \Codebird\Codebird::getInstance();
    $cb->setToken($this->token, $this->token_secret);
    
    $media_ids = [];
    $img_ids = $cb->media_upload([
      'media'=>$imagen_link
    ]);
    
    $media_ids[] = $img_ids->media_id_string;
    $media_ids = implode(',', $media_ids);
    
    $params = [
      'status' => "Sucursal registrada de ".$negocio_nombre." en #FainditElSalvador. $negocio_link #ElSalvador",
      //'media_ids' => $media_ids
    ];
    
    try {
      $reply = (array) $cb->statuses_update($params);
      //debug($reply);
    } catch (Exception $exc) {
      //echo "error";
    }
  }
  
}
