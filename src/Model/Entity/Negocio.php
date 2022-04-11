<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Inflector;
use \Cake\Core\Configure;

class Negocio extends Entity{
  
  //campos virtuales disponibles para utilizar
  protected $_virtual = ['nombreSinAcento', 'fechaCreacionFormat', 'FechaDiasFormat', 'FechaMesesFormat'];

  //funcion para convertir el nombre del negocio en slug
  protected function _setNombre($nombre){
    $this->set('nombre_slug', Inflector::slug(mb_strtolower($nombre),'-')); //convierte nombre a slug
    
    if(!Configure::check('cookieUsuarioAdmin')){
      $this->set('usuario_id', Configure::read('cookieUsuario')['id']); //agregar id del usuario 
    }
    
    return $nombre;
  }
  
  //funcion para completar direccion web
  protected function _setUrl($url){    
    if ($url != '' && !preg_match('/http/i', $url)) {
      $url = 'http://' . $url;
    }
    
    return $url;
  }
  
  /* nombre sin acento */
  protected function _getNombreSinAcento() {    
    return Inflector::slug(mb_strtolower($this->_properties['nombre']), ' ');
  }  
  
  /* Fecha de creacion con formato */
  protected function _getFechaCreacionFormat() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    return date_format($this->_properties['fecha_creacion'], $formatoFecha);
  }
  
  /* Fecha de registro con formato */
  protected function _getFechaCreacionDiasFormat() {
    return date_format($this->_properties['fecha_creacion'], 'd-m-Y');
  }
  
  protected function _getFechaCreacionMesesFormat() {
    return date_format($this->_properties['fecha_creacion'], 'm-Y');
  }
}
