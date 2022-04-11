<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class ConfigsCompComponent extends Component {
  
  public $components = ['Flash'];

  //funcion para obtener las configuraciones del sitio
  public function getConfigs($llaves = null) {
    $configsTable = TableRegistry::get('Configs');

    /* obtener lista de configuraciones */
    if($llaves == null){
      $configs = $configsTable->find('all');
    }else{
      $configs = $configsTable->find('all', [
        'conditions'=>[
          'Configs.nombre'=>$llaves
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
  
  
  //funcion para guardar las configuraciones en la base de datos
  public function setConfigs($configsTable, $configEntity, $opciones, $arrayNoVacio = null) {
    try {
      //recorrer las opciones para verificar las que no pueden estar vacias
      if($arrayNoVacio!=null){
        foreach ($opciones as $llave => $valor) {
          if (in_array($llave, $arrayNoVacio) && empty($valor)) {
            throw new \Exception(__('Los ajustes requeridos no pueden estar vacíos'));
          }
        }
      }
      
      //iniciar transaccion en la base de datos
      //enviar parametros $configsTable y $opciones
      $configsTable->connection()->transactional(function() use($configsTable, $configEntity, $opciones) {
        //recorrer todas las opciones del arreglo
        foreach ($opciones as $llave => $opcion) {
          //verificar si la opcion ya existe en la base de datos
          $q = $configsTable->find('all', [
            'conditions' => [
              'Configs.nombre' => $llave
            ]
          ]);
          $reg = $q->first(); //obtener el primer registro

          $data = array(); //inicializar data
          if (empty($reg)) {
            //si la opcion no existe crear arreglo con los datos y guardarlo en la base
            $data['nombre'] = $llave;
            $data['valor'] = $opcion;

            $configEntity = $configsTable->newEntity($data);

            if (!$configsTable->save($configEntity, ['atomic' => false])) {
              //lanza excepcion en caso de fallo
              throw new \Exception(__('Error al actualizar los ajustes'));
            }
          } else {
            //si la opcion ya existe actualizar su contenido en la base              
            $data['id'] = $reg->id;
            $data['nombre'] = $llave;
            $data['valor'] = $opcion;
            
            $reg = $configsTable->patchEntity($reg, $data);

            if (!$configsTable->save($reg)) {
              //lanza excepcion en caso de fallo
              throw new \Exception(__('Error al actualizar los ajustes'));
            }
          }
        }
      });

      return true; //todo bien
      
    } catch (\PDOException $ex) { //excepcion de base de datos
      $this->Flash->error($ex->getCode() . ' - ' . $ex->getMessage(), [
        //'params'=>['class'=>'alert-absolute timed', 'tiempo'=>5]
      ]);
    } catch (\Exception $ex) { //excepcion personalizada
      $this->Flash->error($ex->getMessage(), [
        //'params'=>['class'=>'alert-absolute timed', 'tiempo'=>5]
      ]);
    }
  }

}
