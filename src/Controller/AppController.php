<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use \Cake\Core\Configure;
use \Cake\Utility\Security;
use \Cake\Routing\Router;
use Cake\Cache\Cache;


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $perRol = null; //var para permisos
    public $config = null; //var para configuraciones del sitio
    public $cookieUsuario = null; //var para datos del usuario
    public $cookieUsuarioAdmin = null; //var para datos del usuario
    public $js_buscador = null; //bandera para cargar js de autocompletado del buscador
    public $google_map_api_key;


    public $permisos_array = array(
        'publicacion'=> array(
          'nombre'=>'Publicaci&oacute;n',
          'permisos'=>array(
            'cambiar_usuario'=>'Puede <b>cambiar</b> nombre de usuario',
            'cambiar_contrasena'=>'Puede <b>cambiar</b> contrase&ntilde;a',
            'crear_negocios'=>'Puede <b>crear</b> negocios',
            'crear_sucursales'=>'Puede <b>crear</b> sucursales para sus negocios',
            'cargar_elim_imagenes'=>'Puede <b>cargar y eliminar</b> im&aacute;genes',
            'reportar_negocios'=>'Puede <b>reportar</b> negocios',
          )
        ),
  
  
        'configuracion'=> array(
          'nombre'=>'Configuraci&oacute;n',
          'permisos'=>array(
            'acceso_admin'=>'Puede <b>accesar</b> al panel de administraci&oacute;n',
            'ver_panel_config'=>'Puede <b>ver</b> el panel de configuraciones',
            'cambiar_config_sitio'=>'Puede <b>cambiar</b> configuraciones del sitio',
            'deshabilitar_sitio'=>'Puede <b>deshabilitar</b> el sitio',
            'naveg_sitio_deshabilitado'=>'Puede <b>navegar</b> por el sitio cuando esta deshabilitado',
            'cambiar_ajustes_correo'=>'Puede <b>cambiar</b> los ajustes del correo electr&oacute;nico',    
            'cambiar_ajustes_usuario_registro'=>'Puede <b>cambiar</b> los ajustes de usuarios y registro',  
            'cambiar_ajustes_administrador'=>'Puede <b>cambiar</b> los ajustes de administrador',  
            'cambiar_ajustes_negocios_sucursales'=>'Puede <b>cambiar</b> los ajustes de negocios y sucursales',  
          )
        ),
  
        'negocios_sucursales'=> array(
          'nombre'=>'Negocios y sucursales',
          'permisos'=>array(
            'ver_panel_neg_sucursales'=>'Puede <b>ver</b> el panel de negocios y sucursales',
            'admin_neg_categorias' => 'Puede <b>administrar</b> las categor&iacute;as de los negocios',
            'admin_neg_sucursales' => 'Puede <b>administrar</b> la informaci&oacute;n de cualquier negocio o sucursal',
            'eliminar_neg_sucursal' => 'Puede <b>eliminar</b> la publicaci&oacute;n de cualquier negocio o sucursal',     
          )
        ),
  
        'usuarios_roles'=> array(
          'nombre'=>'Usuarios y roles',
          'permisos'=>array(
            'ver_panel_usua_roles'=>'Puede <b>ver</b> el panel de usuarios y roles',
            'admin_usuarios'=>'Puede <b>administrar</b> usuarios',
            'eliminar_usuarios'=>'Puede <b>eliminar</b> usuarios',
            'administrar_roles'=>'Puede <b>administrar</b> roles',
            'admin_exclu_correo'=>'Puede <b>administrar</b> exclusiones de correo electr&oacute;nico',
            'admin_exclu_ip'=>'Puede <b>administrar</b> exclusiones de IP',
            'admin_exclu_nom_usua'=>'Puede <b>administrar</b> exclusiones de nombres de usuario',
          )
        ),
  
      );

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('ConfigsComp');   
        $this->loadComponent('Cookie');

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }



    /*obtener las configuraciones del sitio*/
    public function getConfiguraciones($llaves = null) {
        $configsTable = TableRegistry::get('Configs');
  
        /* obtener lista de configuraciones */
        if ($llaves == null) {
          $configs = $configsTable->find('all');
        } else {
          $configs = $configsTable->find('all', [
            'conditions' => [
              'Configs.nombre' => $llaves
            ]
          ]);
        }
  
        //trasformar resultado a un arreglo sencillo
        $config = array();
        foreach ($configs as $configVal) {
          $config[$configVal->nombre] = $configVal->valor;
        }
  
        return $config;
      }
      /*fin obtener las configuraciones del sitio*/
      
      /*funcion para deserializar permisos*/
      public function deserializarRol($rolSerial=null){
        $permisos['id'] = $rolSerial['id'];
        $permisos['nombre'] = $rolSerial['nombre'];
        $permisos['descripcion'] = $rolSerial['descripcion'];
        $permisos['puede_eliminarse'] = $rolSerial['puede_eliminarse'];
        
        $permisos = array_merge($permisos, unserialize($rolSerial['permisos']));
        return $permisos;
      }
      /*fin funcion para deserializar permisos*/
      
      /*funcion para obtener los permisos de un rol*/
      public function getRol($rol_id = null){
        if ($rol_id == null) { return false; }
        
        $rolesTable = TableRegistry::get('Roles');
        $rolSerial = $rolesTable->get($rol_id)->toArray();
        
        $permisos = $this->deserializarRol($rolSerial);
        
        return $permisos;
      }
      
      /*funcion para verificar si el usuario NO esta logueado*/
      public function isLogueado(){      
        if(!$this->Cookie->check('Usuario')){
          //guardar url a la que intento entrar para redirigir despues de logueado
          $this->Cookie->write('url', Router::url(null, true));
          
          //redirigir a login
          return $this->redirect([
            'prefix'=>false,
            'controller'=>'Usuarios',
            'action'=>'login'
          ]);
        }
      }
      /*fin funcion para verificar si el usuario NO esta logueado*/
      
      //verificar si el usuario esta logueado para no permitir acceso por ejemplo a
      //nuevo registros, reenvio de activacion de cuenta, etc.
      public function siLogueadoNo(){
        if($this->Cookie->check('Usuario')){
          return true;
        }
      }
      
      
      /*funcion para verificar si el usuario administrador NO esta logueado*/
      public function isLogueadoAdmin(){      
        if(!$this->Cookie->check('UsuarioAdmin')){
          //guardar url a la que intento entrar para redirigir despues de logueado
          $this->Cookie->write('url', Router::url(null, true));
          
          //redirigir a login
          return $this->redirect([
            'prefix'=>'admin',
            'controller'=>'Usuarios',
            'action'=>'login'
          ]);
        }
      }
      /*fin funcion para verificar si el usuario administrador NO esta logueado*/
      
      
      /*obtene lista desplegable con opciones de cuantos registros se pueden ver en paginacion*/
      public function getVerOpciones($lista=null){
        $arr1 = $arr2 = explode(',', $lista);
        return array_combine($arr1, $arr2);
      }
      
      
      /*funcion para generar un token de verificacion de movimientos del usuario*/
      public function generarToken(){
        $token = array();
        
        $fecha = new \DateTime('now');
        
        $token['fecha_token'] = $fecha;
        $token['token'] = Security::hash($fecha->format('Y-m-d H:i:s'), 'sha256', true);
        
        return $token;
      }
      
      /*funcion para generar un token falso que no se utiliza en realidad.
       * Su utilidad es tratar de evitar psicologicamente que el usuario modifique las url introduciendo
       * datos para navegar entre otros registros
       */
      public function generarTokenFalso(){
        $fecha = new \DateTime('now');
        return Security::hash($fecha->format('Y-m-d H:i:s'), 'sha256', true);
      }
      
      /*function para devolver el mensaje de error estandarizado*/
      public function getMensajeError($error = null){
        $mensaje = null;
        
        switch($error){
          case 403:
            $mensaje = __('Acceso restringido');
            break;
          case 404:
            $mensaje = __('Registro no encontrado');
            break;
          
          default:
            break;
        }
        
        return $mensaje;
      }
      
      /*function para recuperar el navegador que utiliza el usuario*/
      public function getNavegadorWeb(){
        $user_agent = $this->request->header('User-Agent');
        
        $navegador = 'No Determinado';
        if(preg_match('/firefox/i', $user_agent)){
          $navegador = 'Firefox';
          
        }elseif(preg_match('/chrome/i', $user_agent)){
          $navegador = 'Chrome';
          
        }elseif(preg_match('/safari/i', $user_agent)){
          $navegador = 'Safari';
          
        }elseif(preg_match('/msie/i', $user_agent)){
          $navegador = 'Internet Explorer';
          
        }elseif(preg_match('/mobile/i', $user_agent)){
          $navegador = 'Mobile';
          
        }
        
        return $navegador;
      }
      
      /*funcion para obtener lista de negocios del usuario que se muestran menu de perfil*/
      public function getListaNegocios(){    
        $negociosTable = TableRegistry::get('Negocios');
  
        $negocios = $negociosTable->find('all', [
          'conditions' => [
            'usuario_id' => $this->cookieUsuario['id']
          ],
          'contain'=>[
            'Sucursales'=>function($l){
              return $l
                ->where([
                  'Sucursales.principal'=>1
                ]);
            }
          ]
        ])->toArray();
  
        return $negocios;
      }
      
      //funcion que se dispara antes de cada carga de funcion de los controladores
      public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
              
        //obtener configuraciones
        $config = $this->getConfiguraciones();
        $this->set('config', $config);
        $this->config = $config;
        Configure::write('config',$config);
        //fin obtener configuraciones
        
        $params = $this->request->params;      
        if(isset($params['prefix']) && $params['prefix']=='admin'){
          //trabajar con cookie de admin
          
          if($this->Cookie->check('UsuarioAdmin')){
            $cookieUsuarioAdmin = $this->Cookie->read('UsuarioAdmin');
            $this->set('cookieUsuarioAdmin', $cookieUsuarioAdmin);
            $this->cookieUsuarioAdmin = $cookieUsuarioAdmin;
            Configure::write('cookieUsuarioAdmin', $cookieUsuarioAdmin);
            
            //obtener arreglo de los permisos del rol
            $permisos = $this->getRol($cookieUsuarioAdmin['rol_id']);        
            $this->set('perRol', $permisos);        
            $this->perRol = $permisos;
          }  
          
        }else{
          
          //trabajar con cookie de usuario regular        
          //obtener cookie del usuario
          if($this->Cookie->check('Usuario')){
            $cookieUsuario = $this->Cookie->read('Usuario');
            $this->set('cookieUsuario', $cookieUsuario);
            $this->cookieUsuario = $cookieUsuario;
            Configure::write('cookieUsuario', $cookieUsuario);
  
            //obtener arreglo de los permisos del rol
            $permisos = $this->getRol($cookieUsuario['rol_id']);        
            $this->set('perRol', $permisos);        
            $this->perRol = $permisos;
          }      
          //fin obtener cookie del usuario
        }
        
        //echo $this->request->params['prefix'];
        
        /*verificar si el sitio esta deshabilitado y solo aceptar entrar en la pagina de login del administrador*/
        if($this->config['deshabilitar_sitio']==1 && (!isset($this->request->prefix) || $this->request->action != 'login')){  
          //verificar permiso para navegar poe el sitio incluso si esta deshabilitado
          if(!isset($this->perRol['naveg_sitio_deshabilitado']) || $this->perRol['naveg_sitio_deshabilitado']==0){
            return $this->redirect([
              'prefix'=>false, 'controller'=>'Principal', 'action'=>'mantenimiento'
            ]);
          }
        }
        /*fin verificar si el sitio esta deshabilitado*/
        
        
        $mostrarPublicidad = Configure::read('mostrarPublicidad');
        $publicidadResultados = Configure::read('publicidadResultados');
        $this->set(compact('mostrarPublicidad', 'publicidadResultados'));
      }
      
      /*funcion para limpiar url de redes sociales en los perfiles*/
      public function limpiarRedesSociales($data){
        
        //quitar url innecesario para facebook, twitter, instagram, google+
        //facebook
        $buscar1 = 'facebook.com/';
        $pos1 = stripos($data['facebook'], $buscar1);
        
        if($pos1 !== false){
          $data['facebook'] = substr($data['facebook'], $pos1 + strlen($buscar1));
        }
        
        //twitter
        $buscar2 = 'twitter.com/';
        $pos2 = stripos($data['twitter'], $buscar2);
        
        if($pos2 !== false){
          $data['twitter'] = substr($data['twitter'], $pos2 + strlen($buscar2));
        }
        
        //instagram
        $buscar3 = 'instagram.com/';
        $pos3 = stripos($data['instagram'], $buscar3);
        
        if($pos3 !== false){
          $data['instagram'] = substr($data['instagram'], $pos3 + strlen($buscar3));
        }
        $data['instagram'] = str_replace('@', '', $data['instagram']); //quitar arroba
        
        //Google + (1)
        $buscar4 = 'plus.google.com/u/0/';
        $pos4 = stripos($data['google_plus'], $buscar4);
        
        if($pos4 !== false){
          $data['google_plus'] = substr($data['google_plus'], $pos4 + strlen($buscar4));
        }
        
        //Google + (2)
        $buscar5 = 'plus.google.com/';
        $pos5 = stripos($data['google_plus'], $buscar5);
        
        if($pos5 !== false){
          $data['google_plus'] = substr($data['google_plus'], $pos5 + strlen($buscar5));
        }
        //fin - quitar url innecesario para facebook, twitter, instagram, google+
        
        return $data;
      }
      
      /*funcion que se dispara antes de renderizar una vista*/
      public function beforeRender(\Cake\Event\Event $event) {
        parent::beforeRender($event);
        
        if(isset($this->js_buscador)){        
          $urlQue = Router::url(['prefix'=>false,'controller'=>'Principal','action'=>'getBuscar']);
          $urlEn = Router::url(['prefix'=>false,'controller'=>'Principal','action'=>'getDonde']);
          
          $this->set(compact('urlQue', 'urlEn'));
        }
        
        $this->google_map_api_key = getEnv('google_map_api_key');
        $this->set('google_map_api_key', $this->google_map_api_key);        
      }
}
