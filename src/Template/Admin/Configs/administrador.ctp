<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Ajustes de panel de administraci&oacute;n') ?></h3>  
    </div>
  </div>
  
  <?= $this->Form->create($configsEntity, ['class'=>'form-horizontal', 'autocomplete'=>'off']); ?>    
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;meros de opciones visibles en la secci&oacute;n de usuarios') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Opciones de cu&aacute;ntos registros se puede ver por p&aacute;gina en el administrador') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('adm_usuarios_opciones_visibles',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['adm_usuarios_opciones_visibles'])) ? $config['adm_usuarios_opciones_visibles'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de opci&oacute;n por defecto en la secci&oacute;n de usuarios') ?><span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('adm_usuarios_opcion_defecto',[   
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['adm_usuarios_opcion_defecto'])) ? $config['adm_usuarios_opcion_defecto'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;meros de opciones visibles en la secci&oacute;n de categor&iacute;as') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Opciones de cu&aacute;ntos registros se puede ver por p&aacute;gina en el administrador') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('adm_categorias_opciones_visibles',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['adm_categorias_opciones_visibles'])) ? $config['adm_categorias_opciones_visibles'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de opci&oacute;n por defecto en la secci&oacute;n de categor&iacute;as') ?><span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('adm_categorias_opcion_defecto',[   
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['adm_categorias_opcion_defecto'])) ? $config['adm_categorias_opcion_defecto'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Ajustes de negocios') ?></h3>  
    </div>
  </div>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;meros de opciones visibles en la secci&oacute;n de negocios') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Opciones de cu&aacute;ntos registros se puede ver por p&aacute;gina en el administrador') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('adm_negocios_opciones_visibles',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['adm_negocios_opciones_visibles'])) ? $config['adm_negocios_opciones_visibles'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de opci&oacute;n por defecto en la secci&oacute;n de negocios') ?><span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('adm_negocios_opcion_defecto',[   
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['adm_negocios_opcion_defecto'])) ? $config['adm_negocios_opcion_defecto'] : '',
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