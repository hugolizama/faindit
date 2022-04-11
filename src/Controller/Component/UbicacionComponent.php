<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class UbicacionComponent extends Component{
  
  //funcion para obtener lista de paises
  public function getPaises(){
    $paisesTable = TableRegistry::get('Paises');
    
    $vacio = [''=>__('- Pa&iacute;s -')];
    
    $paises = $paisesTable->find('list', [
      'order' => [
        'Paises.nombre' => 'desc'
      ]
    ])->toArray();
    
    $paises = $vacio + $paises;
    
    return $paises;
  }
  
  //funcion para obtener lista de departamentos
  public function getDepartamentos($pais_id=null){
    $departamentosTable = TableRegistry::get('Departamentos');
    
    $vacio = [''=>__('- Seleccione -')];
    
    $departamentos = $departamentosTable->find('list', [
      'conditions'=>[
        'pais_id' => $pais_id
      ],
      'order' => [
        'nombre' => 'asc'
      ]
    ])->toArray();
    
    if(empty($departamentos)){
      $vacio = [''=>__('- Departamentos -')];
    }
    
    $departamentos = $vacio + $departamentos;
    
    return $departamentos;
  }
  
  //lista de municipios
  public function getMunicipios($departamento_id=null){
    $municipiosTable = TableRegistry::get('Municipios');
    
    $vacio = [''=>__('- Seleccione -')];
    
    $municipios = $municipiosTable->find('list', [
      'conditions'=>[
        'departamento_id' => $departamento_id
      ],
      'order' => [
        'nombre' => 'asc'
      ]
    ])->toArray();
    
    if(empty($municipios)){
      $vacio = [''=>__('- Municipios -')];
    }
    
    $municipios = $vacio + $municipios;
    
    return $municipios;
  }
}
