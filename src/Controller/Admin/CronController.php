<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Admin;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use \Cake\Core\Configure;

/**
 * CakePHP cronController
 * @author hugo lizama
 */

class CronController extends AppController {
  public function index(){
    $this->autoRender = false;
  }
  
  public function usuariosSinNegocio(){
    $this->layout = 'ajax';
    
    $saltoBorrar = false;
    $saltoInsertar = false;
    
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    $this->loadComponent('Correo');
    $notificacionesUsuariosTable = TableRegistry::get('NotificacionesUsuarios');
    $conn = ConnectionManager::get('default');
        
    
    /***********************************************************************************
     * Inicio verificar aqui la tabla de notificaciones_usuarios cual usuario ya tiene
      un negocio registrado o mas de 60 dias registrado para borrarlo de la tabla
     **********************************************************************************/
    $notificacionesUsuariosLista = $notificacionesUsuariosTable->find('all', [
      'conditions'=>[
        'NotificacionesUsuarios.tipo_notificacion'=>'usuario-sin-negocio'
      ],
      'contain'=>[
        'Usuarios'=>function($q){
          return $q
            ->contain([
              'Negocios'
            ]);
        }
      ],
      'order'=>'NotificacionesUsuarios.id'
    ])->toArray();
    
    //Recorrer cada registro y eliminar los que ya tienen al menos un negocio registrado
    foreach ($notificacionesUsuariosLista as $nul){
      $notificacionesUsuariosBorrar = null;
      
      /*Verificar si tiene mas de 60 dias de registro para ya no seguir enviandole notificaciones*/            
      $fecha1 = new \DateTime('now'); //fecha actual
      $fecha2 = date_create_from_format('d-m-Y h:i a', $nul['fechaRegistroFormat']);  //fecha de registro 
      
      $resta = $fecha1->diff($fecha2); //resta de fechas
      /*FIN Verificar si tiene mas de 60 dias de registro para ya no seguir enviandole notificaciones*/
      
      if(!empty($nul['usuario']['negocios']) || $resta->days >= 60){
        $saltoBorrar = true;
        echo "Borrar usuario: ".$nul['usuario']['usuario']."<br/>
";
        $notificacionesUsuariosBorrar = $notificacionesUsuariosTable->get($nul['id']);
        $notificacionesUsuariosTable->delete($notificacionesUsuariosBorrar);
      }
    }    
    
    if($saltoBorrar == true){
      echo "<br/><br/>
      
";
    }
    /*Fin verificar tabla notificaciones_usuarios*/
    
    
    //obtener lista de usuarios que no tienen negocios, que tengan menos de 60 dias de registro
    //y que no estan en la tabla de notificaciones_usuarios para registrarlos
    $usuarios = $conn->execute(
      'SELECT Usuarios.id AS `id`,       
      Usuarios.usuario AS `usuario`,  
      Usuarios.nombres AS `nombres`, Usuarios.apellidos AS `apellidos`, 
      Usuarios.correo AS `correo`,    
      Usuarios.fecha_registro AS `fecha_registro`,
      datediff(now(), Usuarios.fecha_registro) `dias`
      FROM usuarios Usuarios 
      WHERE (
        Usuarios.estado = 1 
        AND Usuarios.id not in (
          SELECT DISTINCT NotificacionesUsuarios.usuario_id AS `usuario_id` 
          FROM notificaciones_usuarios NotificacionesUsuarios
          where NotificacionesUsuarios.tipo_notificacion = \'usuario-sin-negocio\'
          union
          SELECT DISTINCT Negocios.usuario_id AS `usuario_id` FROM negocios Negocios
        ) 	
        AND datediff(now(), Usuarios.fecha_registro) < 60
      )'
    )->fetchAll('assoc');
    
    foreach ($usuarios as $usuario) {
      $data = null;
      //convertir string de fecha a objeto
      $fechaObjeto = date_create_from_format('Y-m-d H:i:s', $usuario['fecha_registro']);      
      
      //fecha de registro
      $fecha1 = new \DateTime('now'); //fecha actual
      $fecha2 = \DateTime::createFromFormat(
        $formatoFecha, date_format($fechaObjeto, $formatoFecha)
      ); 
      
      $resta = $fecha1->diff($fecha2); //resta de fechas
      
      /*verificar si la fecha de registro es igual o mayor a 7 dias,
        si es asi insertar registro en notificaciones_usuarios*/
      if ($resta->days >= 7) {
        $urlInsertar = \Cake\View\Helper\UrlHelper::build([
          'prefix' =>'admin',
          'controller'=>'usuarios',
          'action'=>'editar', $usuario['id']  
        ],['full'=>true]);
        
        $saltoInsertar = true;
        echo "Insertar: ".$usuario['usuario'].". URL: ".$urlInsertar."<br/>
";
        
        $data = [
          'usuario_id'=>$usuario['id'],
          'tipo_notificacion'=>'usuario-sin-negocio',
          'fecha_registro'=>$fechaObjeto
        ];
        $notificacionesUsuarios = $notificacionesUsuariosTable->newEntity($data);
        $notificacionesUsuariosTable->save($notificacionesUsuarios);
      }
    }
    
    if($saltoInsertar==true){
      echo "<br/><br/>
      
";
    }
    //fin registrar nuevos usuarios
    
    
    /***********************************************************************************
     * Iniciar proceso de envio de correos para recordar que registren su negocio
     **********************************************************************************/
    $notificacionesUsuariosCorreo = $notificacionesUsuariosTable->find('all', [
      'fields'=> [
        'Usuarios.correo'
      ],
      'conditions'=>[
        'NotificacionesUsuarios.tipo_notificacion'=>'usuario-sin-negocio'
      ],
      'contain'=>[
        'Usuarios'
      ]
    ])->toArray();    
    
    $correos = array();
    foreach ($notificacionesUsuariosCorreo as $nuc){      
      //echo "Enviar correo a: ".$nuc['usuario']['usuario'].". URL: ".$urlCorreo."<br/>
//";
      $correos[] = $nuc['Usuarios']['correo'];
    }   
    
    $chunk = array_chunk($correos, 20); //Separar array en grupos de 20 correos    
    foreach ($chunk as $c) {
      $this->Correo->usuariosSinNegocio($c); //Enviar correo
    }
  }
  
  public function usuariosSinActivar(){    
    $this->layout = 'ajax';
    
    $saltoBorrar = false;
    $saltoInsertar = false;
    
    $formatoFecha = Configure::read('config')['sitio_formato_fecha']; //obtener el formado de la fecha
    $this->loadComponent('Correo');
    $notificacionesUsuariosTable = TableRegistry::get('NotificacionesUsuarios');
    $conn = ConnectionManager::get('default');
    
    /***********************************************************************************
     * Inicio verificar aqui la tabla de notificaciones_usuarios cual usuario ya se encuentra activado
     **********************************************************************************/
    $notificacionesUsuariosLista = $notificacionesUsuariosTable->find('all', [
      'conditions'=>[
        'NotificacionesUsuarios.tipo_notificacion'=>'usuario-sin-activar'
      ],
      'contain'=>[
        'Usuarios'
      ]
    ])->toArray();
    
    
    //Recorrer cada registro y eliminar los que ya se encuentran activados
    foreach ($notificacionesUsuariosLista as $nul){
      $notificacionesUsuariosBorrar = null;
      if($nul['usuario']['estado']==1){
        $saltoBorrar = true;
        echo "Borrar usuario: ".$nul['usuario']['usuario']."<br/>
";
        $notificacionesUsuariosBorrar = $notificacionesUsuariosTable->get($nul['id']);
        $notificacionesUsuariosTable->delete($notificacionesUsuariosBorrar);
      }
    }
    
    if($saltoBorrar == true){
      echo "<br/><br/>
      
";
    }
    /*Fin verificar tabla notificaciones_usuarios*/
    
    
    //obtener lista de usuarios que no estan activados y que no estan en la tabla de notificaciones_usuarios
    //para registrarlos
    $usuarios = $conn->execute(
      'SELECT Usuarios.id AS `id`,       
      Usuarios.usuario AS `usuario`,  
      Usuarios.nombres AS `nombres`, Usuarios.apellidos AS `apellidos`, 
      Usuarios.correo AS `correo`,    
      Usuarios.fecha_registro AS `fecha_registro`
      FROM usuarios Usuarios 
      WHERE (
        Usuarios.estado = 0 
        AND Usuarios.id not in (
          SELECT DISTINCT NotificacionesUsuarios.usuario_id AS `usuario_id` 
          FROM notificaciones_usuarios NotificacionesUsuarios
          where NotificacionesUsuarios.tipo_notificacion = \'usuario-sin-activar\'          
        ) 	
      )'
    )->fetchAll('assoc');
    
    foreach ($usuarios as $usuario) {
      $data = null;
      //convertir string de fecha a objeto
      $fechaObjeto = date_create_from_format('Y-m-d H:i:s', $usuario['fecha_registro']);      
      
      //fecha de registro
      $fecha1 = new \DateTime('now'); //fecha actual
      $fecha2 = \DateTime::createFromFormat(
        $formatoFecha, date_format($fechaObjeto, $formatoFecha)
      ); 
      
      $resta = $fecha1->diff($fecha2); //resta de fechas
      
      /*verificar si la fecha de registro es igual o mayor a 7 dias,
        si es asi insertar registro en notificaciones_usuarios*/      
      if ($resta->format('%d') >= 7) {
        $urlInsertar = \Cake\View\Helper\UrlHelper::build([
          'prefix' =>'admin',
          'controller'=>'usuarios',
          'action'=>'editar', $usuario['id']  
        ],['full'=>true]);
        
        $saltoInsertar = true;
        echo "Insertar: ".$usuario['usuario'].". URL: ".$urlInsertar."<br/>
";
        
        $data = [
          'usuario_id'=>$usuario['id'],
          'tipo_notificacion'=>'usuario-sin-activar',
          'fecha_registro'=>$fechaObjeto
        ];
        $notificacionesUsuarios = $notificacionesUsuariosTable->newEntity($data);
        $notificacionesUsuariosTable->save($notificacionesUsuarios);
      }
    }
    
    if($saltoInsertar==true){
      echo "<br/><br/>
      
";
    }
    //fin registrar nuevos usuarios
    
    
    /***********************************************************************************
     * Iniciar proceso de envio de correos para recordar que registren su negocio
     **********************************************************************************/
    $notificacionesUsuariosCorreo = $notificacionesUsuariosTable->find('all', [
      'conditions'=>[
        'NotificacionesUsuarios.tipo_notificacion'=>'usuario-sin-activar'
      ],
      'contain'=>[
        'Usuarios'
      ]
    ])->toArray();
    
    foreach ($notificacionesUsuariosCorreo as $nuc){      
      //echo "Enviar correo a: ".$nuc['usuario']['usuario'].". URL: ".$urlCorreo."<br/>
//";
      $this->Correo->usuariosSinActivar(
        $nuc['usuario']['id'], $nuc['usuario']['usuario'], $nuc['usuario']['correo'], $nuc['usuario']['token']
      );
    }
  }
  
  /*function prueba(){
    //$this->layout = 'ajax';
    //$this->autoRender = false;
    
    $this->loadComponent('Correo');   
    
    
    $notificacionesUsuariosTable = TableRegistry::get('NotificacionesUsuarios');
    $notificacionesUsuariosCorreo = $notificacionesUsuariosTable->find('all', [
      'fields'=> [
        'Usuarios.correo'
      ],
      'conditions'=>[
        'NotificacionesUsuarios.tipo_notificacion'=>'usuario-sin-negocio'
      ],
      'contain'=>[
        'Usuarios'
      ]
    ])->toArray();
    
    $correos = array();
    foreach ($notificacionesUsuariosCorreo as $nuc){      
      $correos[] = $nuc['Usuarios']['correo'];
    }    
    
    $chunk = array_chunk($correos, 20);    
    foreach ($chunk as $c) {
      $this->Correo->prueba($c);
    }
    
  }*/
}
