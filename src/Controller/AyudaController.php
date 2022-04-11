<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

/**
 * CakePHP AyudaController
 * @author hugo lizama
 */
class AyudaController extends AppController {
  
  public function redesSociales(){
    $this->set('titulo', __('Ayuda - Redes sociales'));
  }
}
