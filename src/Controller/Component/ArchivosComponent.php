<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;

class ArchivosComponent extends Component {
    
  //funcion para cargar logo del negocio
  public function cargarLogo($imagen_temp = null, $negocio_id = null) {    
    $tipo = explode('/', $imagen_temp['type']);
    $extension = ($tipo[1] == 'jpeg') ? 'jpg' : $tipo[1]; //extension del archivo
    
    $ruta_base = 'img/neg/'.$negocio_id.'/';
    $nombre_archivo = 'logo.'.$extension;
    
    $destino = $ruta_base.$nombre_archivo; //ruta destino

    //mover archivo temporal
    $old = umask(0);
    move_uploaded_file($imagen_temp['tmp_name'], $destino); //mover imagen
    umask($old);
    
    list($ancho, $alto) = getimagesize($destino); //obtener ancho y alto
    $ancho_original = $ancho;
    $alto_original = $alto;

    //ancho y altura maxima
    $config = Configure::read('config');
    $negocios_logo_max_ancho = $config['negocios_logo_max_ancho'];
    $negocios_logo_max_altura = $config['negocios_logo_max_altura'];

    
    if ($ancho > $negocios_logo_max_ancho) { //ajustar ancho               
      $alto = round(($negocios_logo_max_ancho / $ancho) * $alto);
      $ancho = $negocios_logo_max_ancho;      
    }

    if ($alto > $negocios_logo_max_altura) { //ajustar alto
      $ancho = round(($negocios_logo_max_altura / $alto) * $ancho);
      $alto = $negocios_logo_max_altura;      
    }

    //crear nueva imagen
    $imagen_p = imagecreatetruecolor($ancho, $alto);

    switch ($extension) {
      case 'jpg':
        $imagen = imagecreatefromjpeg('img/neg/'.$negocio_id.'/logo.' . $extension);
        break;

      case 'png':
        $imagen = imagecreatefrompng('img/neg/'.$negocio_id.'/logo.' . $extension);
        break;

      default:
        break;
    }

    imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
    imagejpeg($imagen_p, 'img/neg/'.$negocio_id.'/logo.jpg', 90);

    if ($extension != 'jpg') {
      unlink($destino); //eliminar archivo original png
    }
    
  }
  
  
  
  /*function para cargar imagenes*/
  public function cargarImagenes($imagen_temp = null, $negocio_id = null, $sucursal_id = null, $nombre_img=null) {    
    $tipo = explode('/', $imagen_temp['type']);
    $extension = ($tipo[1] == 'jpeg') ? 'jpg' : $tipo[1]; //extension del archivo
    
    $ruta_base = 'img/neg/'.$negocio_id.'/'.$sucursal_id.'/';
    $nombre_archivo = $nombre_img.'.'.$extension;
    
    $destino = $ruta_base.$nombre_archivo; //ruta destino

    //mover archivo temporal
    $old = umask(0);
    if(!move_uploaded_file($imagen_temp['tmp_name'], $destino)){ //mover imagen
      return false;
    }    
    umask($old);
    
    list($ancho, $alto) = getimagesize($destino); //obtener ancho y alto
    $ancho_original = $ancho;
    $alto_original = $alto;

    //ancho y altura maxima
    $config = Configure::read('config');
    $negocios_img_max_ancho = $config['negocios_img_max_ancho'];
    $negocios_img_max_altura = $config['negocios_img_max_altura'];
    
    if ($ancho > $negocios_img_max_ancho) { //ajustar ancho               
      $alto = round(($negocios_img_max_ancho / $ancho) * $alto);
      $ancho = $negocios_img_max_ancho;      
    }

    if ($alto > $negocios_img_max_altura) { //ajustar alto
      $ancho = round(($negocios_img_max_altura / $alto) * $ancho);
      $alto = $negocios_img_max_altura;      
    }

    //crear nueva imagen
    $imagen_p = imagecreatetruecolor($ancho, $alto);

    switch ($extension) {
      case 'jpg':
        $imagen = imagecreatefromjpeg('img/neg/'.$negocio_id.'/'.$sucursal_id.'/'.$nombre_img.'.'.$extension);
        break;

      case 'png':
        $imagen = imagecreatefrompng('img/neg/'.$negocio_id.'/'.$sucursal_id.'/'.$nombre_img.'.'.$extension);
        break;

      default:
        break;
    }

    imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
    imagejpeg($imagen_p, 'img/neg/'.$negocio_id.'/'.$sucursal_id.'/'.$nombre_img.'.jpg', 90);

    if ($extension != 'jpg') {
      unlink($destino); //eliminar archivo original png
    }
    
    return true;
  }
  
  
  public function getImageSize ($imagen_temp=null){
    list($ancho, $alto) = getimagesize($imagen_temp['tmp_name']); //obtener ancho y alto

    //ancho y altura maxima
    $config = Configure::read('config');
    $negocios_img_max_ancho = $config['negocios_img_max_ancho'];
    $negocios_img_max_altura = $config['negocios_img_max_altura'];
    
    if ($ancho > $negocios_img_max_ancho) { //ajustar ancho               
      $alto = round(($negocios_img_max_ancho / $ancho) * $alto);
      $ancho = $negocios_img_max_ancho;      
    }

    if ($alto > $negocios_img_max_altura) { //ajustar alto
      $ancho = round(($negocios_img_max_altura / $alto) * $ancho);
      $alto = $negocios_img_max_altura;      
    }
    
    $arr = array(
      'ancho'=>$ancho,
      'alto'=>$alto
    );
    
    return $arr;
  }
}
  