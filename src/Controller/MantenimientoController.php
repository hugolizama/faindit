<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

/**
 * CakePHP MantenimientoController
 * @author hugo lizama
 */
class MantenimientoController extends AppController {
  public function beforeFilter(\Cake\Event\Event $event) {
    
  }
  
  public function index(){
    $this->layout = 'mantenimiento';
  }
}
