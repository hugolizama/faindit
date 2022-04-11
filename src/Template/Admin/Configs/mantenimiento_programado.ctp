<?php
/*radio buttons personalizados para abregar class radio-label*/
$this->Form->templates([
  'nestingLabel' => '<label{{attrs}} class=\'radio-label-{{text}}\'>{{input}}{{text}}</label>',
]);
?>
<div class="container-fluid">
  <?= $this->Form->create($configsEntity, ['class'=>'form-horizontal']); ?>
  
  
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Mantenimiento programado') ?></h3>  
    </div>
  </div>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Mantenimiento programado') ?> <span class="required"></span>
      <div class="label-descripcion">
        <?= __('Activar o desactivar un mensaje de mantenimiento en el sitio web.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">   
      <?= $this->Form->radio('mantenimiento_activar', [
        ['value'=>1,'text'=>'Si'],
        ['value'=>0,'text'=>'No']
      ],[
        'class'=>'form-control',
        'default'=> (isset($config['mantenimiento_activar'])) ? $config['mantenimiento_activar'] : 0,
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/> 
  
  <div class="form-group">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Mensaje') ?> <span class="required"></span>
      <div class="label-descripcion">
        <?= __('Este mensaje ser&aacute; mostrado a todos.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->textarea('mantenimiento_mensaje',[        
        'rows' => '4',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['mantenimiento_mensaje'])) ? $config['mantenimiento_mensaje'] : '',
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
