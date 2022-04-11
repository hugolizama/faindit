<?php
/*radio buttons personalizados para abregar class radio-label*/
$this->Form->templates([
  'nestingLabel' => '<label{{attrs}} class=\'radio-label-{{text}}\'>{{input}}{{text}}</label>',
]);
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Deshabilitar sitio') ?></h3>  
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12">
      <?= __('En caso de deshabilitar el sitio de forma general para mantenimiento o actualizaciones habilitar la siguiente opci&oacute;n. 
        Los invitados y usuarios regulares solo ver&aacute;n el mensaje que aqui se especifique.') ?>
    </div>
  </div>
  
  <?= $this->Form->create($configsEntity, ['class'=>'form-horizontal']); ?>
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Deshabilitar sitio') ?>
      <div class="label-descripcion">
        <?= __('Solo podr&aacute; ser visto por administradores o usuarios con permiso.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">   
      <?= $this->Form->radio('deshabilitar_sitio', [
        ['value'=>1,'text'=>'Si'],
        ['value'=>0,'text'=>'No']
      ],[
        'class'=>'form-control',
        'default'=> (isset($config['deshabilitar_sitio'])) ? $config['deshabilitar_sitio'] : 0,
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/> 
  
  <div class="form-group">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Mensaje') ?>
      <div class="label-descripcion">
        <?= __('Este mensaje ser&aacute; mostrado a los usuarios cuando el sitio este deshabilitado.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->textarea('deshabilitar_mensaje',[        
        'rows' => '4',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['deshabilitar_mensaje'])) ? $config['deshabilitar_mensaje'] : '',
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
