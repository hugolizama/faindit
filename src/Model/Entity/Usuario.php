<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use \Cake\Utility\Security;
use \Cake\Core\Configure;

class Usuario extends Entity {

  //campos virtuales disponibles para utilizar
  protected $_virtual = ['fechaRegistroFormat', 'fechaSuspensionFormat', 'fechaTerminaSuspensionFormat',
    'tiempoTranscurrido', 'tiempoTerminaSuspension'];

  /* Fecha de registro con formato */
  /*protected function _getFechaRegistroFormat() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    return date_format($this->_properties['fecha_registro'], $formatoFecha);
  }*/

  /* Fecha de suspension con formato */
  /*protected function _getFechaSuspensionFormat() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    
    if($this->_properties['fecha_suspension'] ==null){
      return false;
    }
    
    return date_format($this->_properties['fecha_suspension'], $formatoFecha);
  }*/

  /* fecha en que termina la suspension con formato */
  /*protected function _getFechaTerminaSuspensionFormat() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    
    if($this->_properties['fecha_termina_suspension'] ==null){
      return false;
    }
    
    //transformar la fecha de la base a un objeto
    $fecha = \DateTime::createFromFormat(
      $formatoFecha, date_format($this->_properties['fecha_termina_suspension'], $formatoFecha)
    );    
    
    return date_format($fecha, $formatoFecha);
  }*/
  
  /* obtener tiempo en que termina la suspension de un usuario */
  /*protected function _getTiempoTerminaSuspension() {
    
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    $tiempo = '';            
    
    if($this->_properties['fecha_termina_suspension'] ==null){
      return false;
    }
       
    $fecha1 = new \DateTime('now');
    $fecha2 = \DateTime::createFromFormat(
      $formatoFecha, date_format($this->_properties['fecha_termina_suspension'], $formatoFecha)
    ); 
    $resta = $fecha1->diff($fecha2);    

    if ($resta->format('%R') == '-') {
      $tiempo.=$resta->format('(%R) ');
    }

    if ($resta->format('%y') > 0) {
      $tiempo.=$resta->format('%y<b>a</b>, ');
    }

    if ($resta->format('%m') > 0) {
      $tiempo.=$resta->format('%m<b>m</b>, ');
    }

    if ($resta->format('%d') > 0) {
      $tiempo.=$resta->format('%d<b>d</b>, ');
    }

    if ($resta->format('%h') > 0) {
      $tiempo.=$resta->format('%h<b>h</b>, ');
    }

    $tiempo.= $resta->format('%i<b>mi</b>');


    return $tiempo;
  }*/
  

  /* obtener tiempo desde que el usuario se registro y no ha sido activado */
  /*protected function _getTiempoTranscurrido() {
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    $tiempo = '';
            
    $fecha1 = \DateTime::createFromFormat(
      $formatoFecha, date_format($this->_properties['fecha_registro'], $formatoFecha)
    );    
    $fecha2 = new \DateTime('now');
    $resta = $fecha1->diff($fecha2);

    if ($resta->format('%y') > 0) {
      $tiempo.=$resta->format('%y<b>a</b>, ');
    }

    if ($resta->format('%m') > 0) {
      $tiempo.=$resta->format('%m<b>m</b>, ');
    }

    if ($resta->format('%d') > 0) {
      $tiempo.=$resta->format('%d<b>d</b>, ');
    }

    if ($resta->format('%h') > 0) {
      $tiempo.=$resta->format('%h<b>h</b>, ');
    }

    $tiempo.= $resta->format('%i<b>mi</b>');
    

    return $tiempo;
  }*/

  /* cifrar contraseña en la base de datos */
  protected function _setContrasena($contrasena) {
    return Security::hash($contrasena, 'sha256', true);
  }

  /* verificar la contraseña con la de la base de datos */
  public static function checkContrasena($contrasena, $hash) {
    return Security::hash($contrasena, 'sha256', true) === $hash;
  }

  
  /*convertir a minusculas el nombre de usuario*/
  protected function _setUsuario($usuario){
    return trim(strtolower($usuario));
  }
  
  /*convertir a minusculas correo electronico*/
  public function _setCorreo($correo){
    return trim(strtolower($correo));
  }
}
