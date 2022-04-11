<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PrincipalController extends AppController{

  public function beforeFilter(\Cake\Event\Event $event) {
    if($this->request->action != 'mantenimiento'){
      parent::beforeFilter($event);
    }
  }

	public function index(){
		$this->layout='index';
    $this->set('jquery_ui', 1);
    /*activar autocompletado de buscador*/
    $this->js_buscador = 1;
    $this->set('js_buscador', $this->js_buscador);
    /*fin activar autocompletado de buscador*/
    $debug = 0;


    /*mostrar categorias mas utilizadas*/
    $categoriasTable = TableRegistry::get('Categorias');
    $categorias = $categoriasTable->find()
      ->select(['Categorias.id', 'Categorias.nombre', 'count'=>'count(NegociosCategorias.id)'])
      ->leftJoin(['NegociosCategorias'=>'negocios_categorias'], [
        'Categorias.id = NegociosCategorias.categoria_id'
      ])
      ->where([

      ])
      ->group(['Categorias.id', 'Categorias.nombre'])
      ->order(['count'=>'desc'])
      ->limit(20)
      ->toArray();

    shuffle($categorias); //mezclar el resultado

    //transformar las catgorias a enlaces, guardados en un grupo de 3 y otro de 5
    $m = 0;
    $categorias_3 = [];
    $categorias_5 = [];
    for($i = 0; $i < 5; $i++){
      $url = \Cake\Routing\Router::url([
        'prefix'=>false, 'controller'=>'Principal', 'action'=>'buscar',
        '?' => [
          'que'=>  urlencode($categorias[$i]['nombre']), 'tq'=>1,
          'en'=>'', 'te'=>0
        ]
      ]);
      $categorias_5[$i] = "<a class='btn btn-primary btn-sm' href='".$url."'>".$categorias[$i]['nombre']."</a>";

      if($m < 3){
        $categorias_3[$i] = "<a class='btn btn-primary btn-sm' href='".$url."'>".$categorias[$i]['nombre']."</a>";
        $m++;
      }
    }

    $texto_cat3 = implode(' ', $categorias_3);
    $texto_cat5 = implode(' ', $categorias_5);

    $negociosTable = TableRegistry::get('Negocios');

    /*obtener los ultimos negocios*/
    $ultimos_registros = $negociosTable->find('all', [
      'conditions'=>[
        'Negocios.admin_habilitado'=>1
      ],
      'contain'=>[
        'Sucursales'=>function($q){
          return $q
            ->where([
              'principal'=>1
            ])
            ;
        }
      ],
      'order'=>[
        'Negocios.id'=>'desc'
      ],
      'limit'=>8
    ])->toArray();

    $ids=array();  //Id's para no incluir en los negocios al azar
    foreach($ultimos_registros as $ul){
      $ids[] = $ul['id'];
    }

    /*negocios al azar*/
    $negocios = $negociosTable->find('all', [
      'conditions'=>[
        'Negocios.admin_habilitado'=>1,
        'Negocios.id not in'=>$ids
      ],
      'contain'=>[
        'Sucursales'=>function($q){
          return $q
            ->where([
              'principal'=>1
            ])
            ;
        }
      ],
      'order'=>[
        'rand()'
      ],
      'limit'=>8
    ])->toArray();


    $this->set(compact('negocios', 'ultimos_registros', 'debug', 'texto_cat3', 'texto_cat5'));
	}

  /*funcion para obtener texto predictivo de lo que se busca*/
  public function getBuscar(){
    $this->layout = 'ajax';
    $this->autoRender = false;

    $term = filter_input(INPUT_GET,'term');

    /*obtener categorias*/
    $categoriasTable = TableRegistry::get('Categorias');
    $categorias = $categoriasTable->find('all', [
      'conditions'=>[
        'nombre like'=>'%'.$term.'%'
      ],
      'order'=>[
        'nombre'=>'asc'
      ]
    ])->toArray();

    //convertir a formato esperado por jquery ui
    $categoriasJson = array();
    foreach ($categorias as $cat){
      $categoriasJson[]=['label'=>$cat['nombre'], 'category'=>'Categor&iacute;as', 'tq'=>1, 'comparar'=>$cat['nombreSinAcento']];
    }
    /*fin obtener categorias*/

    /*obtener negocios*/
    $negociosTable = TableRegistry::get('Negocios');
    $negocios = $negociosTable->find('all', [
      'conditions'=>[
        'nombre like'=>'%'.$term.'%',
        'admin_habilitado'=>1
      ],
      'order'=>[
        'nombre'=>'asc'
      ]
    ])->toArray();

    //convertir a formato esperado por jquery ui
    $negociosJson = array();
    foreach ($negocios as $neg){
      $negociosJson[]=['label'=>$neg['nombre'], 'category'=>'Negocios', 'tq'=>2, 'comparar'=>$neg['nombreSinAcento']];
    }
    /*fin obtener negocios*/

    //unir categorias y negocios en un solo array
    $data = array_merge($categoriasJson,$negociosJson);

    echo json_encode($data);
  }



  /*funcion para obtener texto predictivo de donde se busca*/
  public function getDonde(){
    $this->layout = 'ajax';
    $this->autoRender = false;

    $term = filter_input(INPUT_GET,'term');

    /*obtener departamentos*/
    $departamentosTable = TableRegistry::get('Departamentos');
    $departamentos = $departamentosTable->find('all', [
      'conditions'=>[
        'nombre like'=>'%'.$term.'%',
        'pais_id'=>1
      ],
      'order'=>[
        'nombre'=>'asc'
      ]
    ])->toArray();

    //convertir a formato esperado por jquery ui
    $departamentosJson = array();
    foreach ($departamentos as $dep){
      $departamentosJson[]=['label'=>$dep['nombre'], 'category'=>'Departamentos', 'te'=>1, 'comparar'=>$dep['nombreSinAcento']];
    }
    /*fin obtener departamentos*/

    /*obtener municipios*/
    $municipiosTable = TableRegistry::get('Municipios');
    $municipios = $municipiosTable->find('all', [
      'conditions'=>[
        'nombre like'=>'%'.$term.'%',
        'pais_id'=>1
      ],
      'order'=>[
        'nombre'=>'asc'
      ]
    ])->toArray();

    //convertir a formato esperado por jquery ui
    $municipiosJson = array();
    foreach ($municipios as $mun){
      $municipiosJson[]=['label'=>$mun['nombre'], 'category'=>'Municipios', 'te'=>2, 'comparar'=>$mun['nombreSinAcento']];
    }
    /*fin obtener municipios*/

    //unir municipios y departamentos en un solo array
    $data = array_merge($municipiosJson, $departamentosJson);

    echo json_encode($data);
  }

  public function buscarEncode(){
    $this->layout = 'ajax';

    $que = urlencode(filter_input(INPUT_GET, 'que')); //termino de busqueda
    $tq = filter_input(INPUT_GET, 'tq'); //tipo de busqueda. 0 - indeterminado, 1 - categoria, 2 - negocio
    $en = urlencode(filter_input(INPUT_GET, 'en')); //donde se busca
    $te = filter_input(INPUT_GET, 'te'); //tipo de busqueda. 0 - indeterminado, 1 - departamento, 2 - municipio
    $ver = filter_input(INPUT_GET, 'ver'); //cuantos elementos se ven por pagina
    $orden = filter_input(INPUT_GET, 'orden'); //ordenamiento de resultados

    $this->redirect([
      'prefix'=>false, 'controller'=>'Principal', 'action'=>'buscar',
      '?' => [
        'que'=> $que,
        'tq' => $tq,
        'en' => $en,
        'te' => $te,
        'ver' => $ver,
        'orden' => $orden
      ]
    ]);
  }

  //funcion para desplegar los resultados de la busqueda
  public function buscar(){
    $this->set('buscar_sidebar',1);
    $this->set('jquery_ui', 1);
    $this->set('meta_busqueda', 1);
    /*activar autocompletado de buscador*/
    $this->js_buscador = 1;
    $this->set('js_buscador', $this->js_buscador);
    /*fin activar autocompletado de buscador*/

    $que = urldecode(filter_input(INPUT_GET, 'que')); //termino de busqueda
    $tq = filter_input(INPUT_GET, 'tq'); //tipo de busqueda. 0 - indeterminado, 1 - categoria, 2 - negocio
    $en = urldecode(filter_input(INPUT_GET, 'en')); //donde se busca
    $te = filter_input(INPUT_GET, 'te'); //tipo de busqueda. 0 - indeterminado, 1 - departamento, 2 - municipio
    $ver = filter_input(INPUT_GET, 'ver'); //cuantos elementos se ven por pagina
    $orden = filter_input(INPUT_GET, 'orden'); //ordenamiento de resultados


    //establecer titulo segun la busqueda
    $texto_en = '';
    if($en!=''){
      $texto_en = " en $en ";
    }
    $this->set('titulo',$que.$texto_en);


    //contador de busquedas
    if($this->request->session()->read("sessionQue")!=$que && !$this->Cookie->check('UsuarioAdmin')){
      $estadisticasTable = TableRegistry::get('Estadisticas');
      $data = null;
      $dataActualizar = null;
      $data['fecha'] = new \DateTime('now');

      $estadisticaBuscar = $estadisticasTable->find('all', [
        'conditions'=>[
          'Estadisticas.fecha'=>$data['fecha']
        ]
      ])->first();

      if(empty($estadisticaBuscar)){
        $estadistica =$estadisticasTable->newEntity($data);
        $estadisticasTable->save($estadistica);
      }else{
        $dataActualizar['contador'] = $estadisticaBuscar->contador + 1;
        $estadisticaBuscar = $estadisticasTable->patchEntity($estadisticaBuscar, $dataActualizar);
        $estadisticasTable->save($estadisticaBuscar);
      }

      if($tq==1){
        $dataCategoria = null;
        $categoriasTable = TableRegistry::get('Categorias');
        $categoria = $categoriasTable->find('all', [
          'conditions'=>[
            'Categorias.nombre'=>$que
          ]
        ])->first();

        $dataCategoria['contador'] = $categoria['contador'] + 1;
        $categoria = $categoriasTable->patchEntity($categoria, $dataCategoria);
        $categoriasTable->save($categoria);
      }
    }

    /*guardar busqueda en sesion para buscador en perfiles*/
    $this->request->session()->write("sessionQue", $que);
    $this->request->session()->write('sessionEn', $en);

    $sucursalesTable = TableRegistry::get('Sucursales');


    //formar condicion para la busqueda por ubicacion
    $pais_id=1;
    $condicionDepartamento=array();
    $condicionMunicipios=array();
    $exclusionPalabras = ['de', 'y', 'o', '&']; //escluir estas palabras en las busquedas


    /************************* DONDE SE BUSCA*************************************/

    switch ($te) {
      case 0: //no se sabe si es departamento o municipio
        $subQuery = $sucursalesTable->find();

        if($en!=''){ //texto en donde buscar tiene algun contenido pero indeterminado si es municipio o departamento
          //busqueda contempla ubicacion en direccion de sucursal, nombre de departamento o municipio
          $subQuery->select([
              'id' => $subQuery->func()->min('Sucursales.id')
            ])
            ->contain(['Departamentos', 'Municipios'])
            ->where([
              'or'=>[
                'Departamentos.nombre like'=>'%'.$en.'%',
                'Municipios.nombre like'=>'%'.$en.'%',
                'Sucursales.direccion like'=>'%'.$en.'%'
              ],
              'and'=>[
                'Sucursales.pais_id'=>$pais_id,
                'Sucursales.habilitado'=>1
              ]
            ])
            ->group('Sucursales.negocio_id');


        }else{ //texto en donde se busca esta vacio
          //la busqueda no contempla ubicacion, solo nombre del negocio
          $queArray = explode(' ', mb_strtolower($que));
          $condicionPalabras = array();

          if($tq==0){
            foreach($queArray as $queItem){ //formar condition con palabras de la busqueda
              if(!in_array($queItem, $exclusionPalabras)){
                $condicionPalabras[]=['Negocios.nombre like'=>'%'.$queItem.'%'];
                $condicionPalabras[]=['Negocios.descripcion like'=>'%'.$queItem.'%'];
              }
            }
          }

          $subQuery->select([
              'id' => $subQuery->func()->min('Sucursales.id')
            ])
            ->contain(
              'Negocios.Categorias'
            )
            ->where([
              'Sucursales.pais_id'=>$pais_id,
              'Sucursales.habilitado'=>1,
              'or'=>$condicionPalabras
            ])
            ->group('Sucursales.negocio_id');

        }
        break;
      case 1: //busqueda en departamentos
        $condicionDepartamento=[
          'departamentos.nombre like'=>'%'.$en.'%'
        ];

        $subQuery = $sucursalesTable->find();
        $subQuery->select([
            'id' => $subQuery->func()->min('Sucursales.id')
          ])
          ->innerJoin('departamentos', [
            'Sucursales.departamento_id = departamentos.id',
            $condicionDepartamento
          ])
          ->where([
            'Sucursales.pais_id'=>$pais_id,
            'Sucursales.habilitado'=>1
          ])
          ->group('Sucursales.negocio_id');

       break;
      case 2: //busqueda en municipios
        $condicionMunicipios=[
          'municipios.nombre like'=>'%'.$en.'%'
        ];

        $subQuery = $sucursalesTable->find();
        $subQuery->select([
            'id' => $subQuery->func()->min('Sucursales.id')
          ])
          ->innerJoin('municipios', [
            'Sucursales.municipio_id = municipios.id',
            $condicionMunicipios
          ])
          ->where([
            'Sucursales.pais_id'=>$pais_id,
            'Sucursales.habilitado'=>1
          ])
          ->group('Sucursales.negocio_id');
       break;

      default:
        break;
    }
    /************************* FIN DONDE SE BUSCA*************************************/


    if($orden==''){
      $orden = 1;
    }
    if($tq!=1 && $orden==1){
      $this->request->query['orden']=2;
      return $this->redirect(['controller'=>'Principal', 'action'=>'buscar', '?'=>$this->request->query]);

    }

    $ordenResultados = [];
    switch ($orden){
      case 1:
        $ordenResultados = [
          'NegociosCategorias.contador' => 'desc',
          'Negocios.nombre'=>'asc'
        ];
        break;
      case 2:
        $ordenResultados = [
          'Negocios.nombre'=>'asc'
        ];
        break;
    }


    /************************* QUE SE BUSCA*************************************/
    switch ($tq) {
      case 0: //Busqueda indeterminada, en este caso buscar por nombres de negocios

        $queArray = explode(' ', mb_strtolower($que));
        $condicionPalabras = array();
        foreach($queArray as $queItem){ //formar condicion de busqueda por nombre de negocios
          if(!in_array($queItem, $exclusionPalabras)){
            $condicionPalabras[]=['Negocios.nombre like'=>'%'.$queItem.'%'];
            $condicionPalabras[]=['Negocios.descripcion like'=>'%'.$queItem.'%'];
          }
        }

        $busqueda = $sucursalesTable->find()
          ->contain(['Telefonos'])
          ->matching('Negocios', function ($w) use ($condicionPalabras){
            return $w
              ->contain(['Categorias'])
              ->where([
                'OR'=>$condicionPalabras      ,
                'AND'=>[
                  'Negocios.admin_habilitado'=>1
                ]
              ])
            ;
          })
          ->matching('Departamentos', function($y) use($condicionDepartamento){
            return $y
              ->where([
                //$condicionDepartamento
              ]);
          })
          ->matching('Municipios', function($y) use($condicionMunicipios){
            return $y
              ->where([
                //$condicionMunicipios
              ]);
          })
          ->matching('Paises')
          ->where([
            'Sucursales.id in '=>$subQuery
          ])
          ->order($ordenResultados)
          ;


          /*buscar en categorias si no hay negocios ------PENDIENTE DE DESARROLLO--------*/
          /*if($busqueda->isEmpty()){
            echo "categorias";
            $busqueda = $sucursalesTable->find();
            $busqueda->contain([
                'Negocios' => function ($q) use ($que, $busqueda){
                  return $q
                    ->matching('Categorias', function ($w) use ($que, $busqueda){
                      return $w
                        ->where([
                          'Categorias.nombre like'=>'%'.$que.'%'
                        ])
                        ;
                    });
                },
                'Telefonos',
              ])

              ->matching('Departamentos', function($y) use($condicionDepartamento){
                return $y
                  ->where([
                    //$condicionDepartamento
                  ]);
              })
              ->matching('Municipios', function($y) use($condicionMunicipios){
                return $y
                  ->where([
                    //$condicionMunicipios
                  ]);
              })
              ->order([
                'Sucursales.negocio_id', 'Sucursales.id'
              ])
              ;

              debug($busqueda);
          }*/
          /*fin buscar en categorias si no hay negocios ------PENDIENTE DE DESARROLLO--------*/


        break;
      case 1: //busqueda en categorias
        $busqueda = $sucursalesTable->find()
          ->contain([
            'Negocios' => function ($q) use ($que){
              return $q
                ->matching('Categorias', function ($w) use ($que){
                  return $w
                    ->where([
                      //'Categorias.nombre like'=>'%'.$que.'%'
                      'Categorias.nombre'=>$que
                    ]);
                });
            },
            'Telefonos'
          ])
          ->matching('Departamentos', function($y) use($condicionDepartamento){
            return $y
              ->where([
                //$condicionDepartamento
              ]);
          })
          ->matching('Municipios', function($y) use($condicionMunicipios){
            return $y
              ->where([
                //$condicionMunicipios
              ]);
          })
          ->matching('Paises')
          ->where([
            'Sucursales.id in '=>$subQuery,
            'Negocios.admin_habilitado'=>1
          ])
          ->order($ordenResultados);

        break;
      case 2: //busqueda en nombres de negocios

        $busqueda = $sucursalesTable->find()
          ->contain(['Telefonos'])
          ->matching('Negocios', function ($w) use ($que){
            return $w
              ->contain(['Categorias'])
              ->where([
                'Negocios.nombre like'=>'%'.$que.'%',
                'Negocios.admin_habilitado'=>1
              ]);
          })
          ->matching('Departamentos', function($y) use($condicionDepartamento){
            return $y
              ->where([
                //$condicionDepartamento
              ]);
          })
          ->matching('Municipios', function($y) use($condicionMunicipios){
            return $y
              ->where([
                //$condicionMunicipios
              ]);
          })
          ->matching('Paises')
          ->where([
            'Sucursales.id in '=>$subQuery
          ])
          ->order($ordenResultados)
          ;

        break;

      default:
        break;
    }
    /************************* FIN QUE SE BUSCA*************************************/

    if($ver==''){
      $ver=$this->config['sitio_buscador_opcion_defecto'];
    }

    $this->paginate = [
      'limit'=>$ver,
      'sortWhitelist' => ['Negocios.nombre']
    ];

    $resultado = $this->paginate($busqueda)->toArray();

    $opciones_visibles = $this->getVerOpciones($this->config['sitio_buscador_opciones']);

    $opciones_orden = [
      1 => 'Relevancia',
      2 => 'Alfabético'
    ];

    if($tq!=1){
      $opciones_orden = [
        2 => 'Alfabético'
      ];
    }

    $this->set(compact('que', 'tq', 'en', 'te', 'ver', 'resultado','opciones_visibles','orden', 'opciones_orden'));
  }


  //funcion para presentar una pagina de mantenimiento del sitio web
  public function mantenimiento(){
    $this->layout = 'mantenimiento';
    $this->set('titulo',__('Mantenimiento'));

    $configsTable = TableRegistry::get('Configs');
    $nombre_sitio = $configsTable->findByNombre('sitio_nombre')->first();
    $mensaje = $configsTable->findByNombre('deshabilitar_mensaje')->first();
    $sitio_deshabilitado = $configsTable->findByNombre('deshabilitar_sitio')->first();


    if($sitio_deshabilitado->valor==0){ //sitio en cero es que ya no esta deshabilitado
      return $this->redirect([
        'prefix'=>false,
        'controller'=>'Principal',
        'action'=>'index'
      ]);
    }

    $this->set(compact('nombre_sitio', 'mensaje'));
  }

  //funcion para enviar un mensaje al negocio solicitado por un usuario
  public function enviarMensaje(){
    $this->layout = 'ajax';
    $this->autoRender = false;

    //error general
    $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Error de solicitud. Intente de nuevo.<span>'];
    if($this->request->is(['post', 'ajax'])){
      $captcha = filter_input(INPUT_POST, 'captcha'); //contenido del captcha

      if($captcha==''){ //captcha vacio
        $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">El captcha es requerido<span>'];

      }else{ //captcha llenado

        $enviarNotificacion = 0; //bandera para verificar si se puede enviar el correo
        if(!isset($this->cookieUsuario['id'])){ //verificar captcha para usuarios NO autenticados
          //verificar captcha
          $arrContextOptions = array(
            "ssl" => array(
              "verify_peer" => false,
              "verify_peer_name" => false,
            ),
          );

          $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeI6Q8TAAAAAD110uX7L6RICOk3ewBbxH8ifHTc&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR'], false, stream_context_create($arrContextOptions));
          $obj = json_decode($response);

          if($obj->success==true){ //captcha verificado con exito
            $enviarNotificacion = 1;
          }
        }else{
          $enviarNotificacion = 1;
        }


        if($enviarNotificacion == 1){ //enviar correo
          $sucursal_id = filter_input(INPUT_POST, 'sucursal_id');
          $correo = filter_input(INPUT_POST, 'correo');
          $nombreDe = filter_input(INPUT_POST, 'deNombre');
          $correoDe = filter_input(INPUT_POST, 'deCorreo');
          $mensaje = filter_input(INPUT_POST, 'mensaje');

          if($nombreDe=='' || $correoDe=='' || $mensaje==''){ //verificar si algun campo requerido esta vacio
            $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Los campos requeridos no pueden estar vac&iacute;os<span>'];
          }else{

            $sucursalesTable = TableRegistry::get('Sucursales');
            $sucursal=$sucursalesTable->get($sucursal_id, ['contain'=>'Negocios']);

            //verificar que el correo en el formulario no haya sido cambiado por el usuario que solicita mandar el mensaje
            if($correo!=$sucursal['correo'] && $correo!=$sucursal['negocio']['correo']){
              $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">El correo del destinatario no debe ser modificado<span>'];
            }else{
              $this->loadComponent('Correo');

              if($this->Correo->contactoNegocio($correo, $nombreDe, $correoDe, $mensaje)==true){
                $respuesta = ['cod'=>1, 'mensaje'=>'Mensaje enviado'];
              }else{
                $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Error de comunicaci&oacute;n. El mensaje no pudo ser enviado.<span>'];
              }
            }
          }

        }else{
          $respuesta = ['cod'=>0, 'mensaje'=>'<span style="color:red; font-size: 12px;">Verificaci&oacute;n de captcha incorrecta<span>'];
        }
      }
    }

    echo json_encode($respuesta);
  }

  public function contacto(){
    $this->set('titulo',__('Contacto'));

    $debug = 0;
    $nombres = null;
    $correo = null;


    $data = null;
    if($this->request->is('post')){
      $data = $this->request->data;

      $camposVacios = 0;
      foreach ($data as $valor){
        if($valor==''){
          $camposVacios = 1;
          $this->Flash->error(__('Todos los campos son requeridos'));
          break;
        }
      }

      if($camposVacios == 0){

        $enviarNotificacion = 0; //bandera para verificar si se puede enviar el correo
        if(!isset($this->cookieUsuario['id'])){ //verificar captcha para usuarios NO autenticados
          //verificar captcha
          $arrContextOptions = array(
            "ssl" => array(
              "verify_peer" => false,
              "verify_peer_name" => false,
            ),
          );

          $captcha = $data['g-recaptcha-response'];
          $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeI6Q8TAAAAAD110uX7L6RICOk3ewBbxH8ifHTc&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR'], false, stream_context_create($arrContextOptions));
          $obj = json_decode($response);

          if($obj->success==true){ //captcha verificado con exito
            $enviarNotificacion = 1;
          }
        }else{
          $enviarNotificacion = 1;
        }

        if($enviarNotificacion == 1){ //enviar correo

          $nombreDe = $data['nombre_apellido'];
          $correoDe = $data['correo'];
          $asunto = $data['asunto'];
          $mensaje = $data['mensaje'];

          $this->loadComponent('Correo');

          if($this->Correo->contactoNosotros($nombreDe, $correoDe, $asunto, $mensaje)==true){
            $this->Flash->success(__('En mensaje ha sido enviado con éxito.'), [
              'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
            ]);

            return $this->redirect([
              'controller'=>'Principal',
              'action'=>'contacto'
            ]);

          }else{
            $this->Flash->error(__('El mensaje no pudo ser enviado, por favor intente de nuevo.'));
          }

        }else{
          $this->Flash->error(__('El captcha no pudo ser verificado, intente de nuevo.'));
        }
      }
    }else{
      if(isset($this->cookieUsuario['id'])){
        $usuariosTable = TableRegistry::get('Usuarios');
        $usuario = $usuariosTable->get($this->cookieUsuario['id']);

        $nombres = $usuario['nombres']." ".$usuario['apellidos'];
        $correo= $usuario['correo'];
      }
    }

    $this->set(compact('debug', 'nombres', 'correo'));
  }


  public function comentarios(){
    $this->set('titulo',__('Comentarios'));

    $debug = 0;
    $nombres = null;
    $correo = null;


    $data = null;
    if($this->request->is('post')){
      $data = $this->request->data;

      $enviarNotificacion = 0; //bandera para verificar si se puede enviar el correo
      if(!isset($this->cookieUsuario['id'])){ //verificar captcha para usuarios NO autenticados
        //verificar captcha
        $arrContextOptions = array(
          "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
          ),
        );

        $captcha = $data['g-recaptcha-response'];
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeI6Q8TAAAAAD110uX7L6RICOk3ewBbxH8ifHTc&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR'], false, stream_context_create($arrContextOptions));
        $obj = json_decode($response);

        if($obj->success==true){ //captcha verificado con exito
          $enviarNotificacion = 1;
        }
      }else{
        $enviarNotificacion = 1;
      }

      if($enviarNotificacion == 1){ //enviar correo

        $nombreDe = $data['nombre_apellido'];
        $correoDe = $data['correo'];
        $mensaje = $data['mensaje'];

        $this->loadComponent('Correo');

        if($this->Correo->comentariosNosotros($nombreDe, $correoDe, $mensaje)==true){
          $this->Flash->success(__('En mensaje ha sido enviado con éxito. Tus comentarios y sugerencias son importantes, gracias.'), [
            //'params' => ['class' => 'alert-absolute timed', 'tiempo' => 5]
          ]);

          return $this->redirect([
            'controller'=>'Principal',
            'action'=>'comentarios'
          ]);

        }else{
          $this->Flash->error(__('El mensaje no pudo ser enviado, por favor intente de nuevo.'));
        }

      }else{
        $this->Flash->error(__('El captcha no pudo ser verificado, intente de nuevo.'));
      }

    }else{
      if(isset($this->cookieUsuario['id'])){
        $usuariosTable = TableRegistry::get('Usuarios');
        $usuario = $usuariosTable->get($this->cookieUsuario['id']);

        $nombres = $usuario['nombres']." ".$usuario['apellidos'];
        $correo= $usuario['correo'];
      }
    }

    $this->set(compact('debug', 'nombres', 'correo'));
  }

  public function condicionesDeUso(){
    $this->set('titulo',__('Condiciones de uso'));

    $debug = 0;

    $this->set(compact('debug'));
  }

  public function politicasDePrivacidad(){
    $this->set('titulo',__('Pol&iacute;ticas de privacidad'));

    $debug = 0;

    $this->set(compact('debug'));
  }
}
