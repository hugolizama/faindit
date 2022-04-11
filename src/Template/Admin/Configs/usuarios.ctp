<?php
/*radio buttons personalizados para abregar class radio-label*/
$this->Form->templates([
  'nestingLabel' => '<label{{attrs}} class=\'radio-label-{{text}}\'>{{input}}{{text}}</label>',
]);
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Ajustes de usuario') ?></h3>  
    </div>
  </div>
  
  <?= $this->Form->create($configsEntity, ['class'=>'form-horizontal']); ?>
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Deshabilitar registros en el sitio') ?>
      <div class="label-descripcion">
        <?= __('Deshabilitar el registro de nuevos usuarios.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">   
      <?= $this->Form->radio('usuarios_deshabilitar_registros', [
        ['value'=>1,'text'=>'Si'],
        ['value'=>0,'text'=>'No']
      ],[
        'class'=>'form-control',
        'default'=> (isset($config['usuarios_deshabilitar_registros'])) ? $config['usuarios_deshabilitar_registros'] : 0,
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/> 
  
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('M&iacute;nimo de caracteres para el nombre de usuario') ?> <span class="required"></span>    
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">   
      <?= $this->Form->input('usuarios_usuario_min_car', [
        'type'=>'number',
        'div'=>false,
        'label'=>false,
        'class'=>'form-control',
        'default'=> (isset($config['usuarios_usuario_min_car'])) ? $config['usuarios_usuario_min_car'] : 0,
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/> 
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('M&aacute;ximo de caracteres para el nombre de usuario') ?> <span class="required"></span>    
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">   
      <?= $this->Form->input('usuarios_usuario_max_car', [
        'type'=>'number',
        'div'=>false,
        'label'=>false,
        'class'=>'form-control',
        'default'=> (isset($config['usuarios_usuario_max_car'])) ? $config['usuarios_usuario_max_car'] : 0,
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>  
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('M&iacute;nimo de caracteres para la contrase&ntilde;a') ?> <span class="required"></span>    
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">   
      <?= $this->Form->input('usuarios_contrasena_min_car', [
        'type'=>'number',
        'div'=>false,
        'label'=>false,
        'class'=>'form-control',
        'default'=> (isset($config['usuarios_contrasena_min_car'])) ? $config['usuarios_contrasena_min_car'] : 0,
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/> 
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('M&aacute;ximo de caracteres para la contrase&ntilde;a') ?> <span class="required"></span>    
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">   
      <?= $this->Form->input('usuarios_contrasena_max_car', [
        'type'=>'number',
        'div'=>false,
        'label'=>false,
        'class'=>'form-control',
        'default'=> (isset($config['usuarios_contrasena_max_car'])) ? $config['usuarios_contrasena_max_car'] : 0,
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/> 
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Rol por defecto para los nuevos usuarios') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->select('usuarios_rol_id_defecto', $rol_id, [
        'class'=>'form-control',
        'default'=>(isset($config['usuarios_rol_id_defecto'])) ? $config['usuarios_rol_id_defecto'] : 0
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="row">
    <div class="col-xs-12 text-center">
      <?= $this->Form->submit(__('Guardar ajustes'), array(
        'class' => 'btn btn-primary btn-margin-right',
        'div' => false,          
      )); ?>
    </div>
  </div>
  <?= $this->Form->end(); ?>
</div>
