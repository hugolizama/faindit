<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Configuraci&oacute;n de negocios') ?></h3>  
    </div>
  </div>
  
  <?= $this->Form->create($configsEntity, ['class'=>'form-horizontal']); ?>
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de categor&iacute;as por negocio') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_max_num_cat',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_max_num_cat'])) ? $config['negocios_max_num_cat'] : 10
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de categor&iacute;as visibles en formulario de negocios') ?> <span class="required"></span>   
      <div class="label-descripcion">
        <?= __('N&uacute;mero de opciones visibles al digitar en el input de categor&iacute;as.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_cat_visibles',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_cat_visibles'])) ? $config['negocios_cat_visibles'] : 10
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Logo: Ancho m&aacute;ximo en pixeles') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_logo_max_ancho',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_logo_max_ancho'])) ? $config['negocios_logo_max_ancho'] : 200
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Logo: Altura m&aacute;xima en pixeles') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_logo_max_altura',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_logo_max_altura'])) ? $config['negocios_logo_max_altura'] : 120
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Logo: Peso m&aacute;ximo en kilobytes') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_logo_max_peso',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_logo_max_peso'])) ? $config['negocios_logo_max_peso'] : 100
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>  
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Cantidad de im&aacute;genes por negocio') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_cant_imagenes',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_cant_imagenes'])) ? $config['negocios_cant_imagenes'] : 10
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Im&aacute;genes: Ancho m&aacute;ximo en pixeles') ?> <span class="required"></span>  
      <div class="label-descripcion">
        <?= __('Tambi&eacute;n aplica para sucursales.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_img_max_ancho',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_img_max_ancho'])) ? $config['negocios_img_max_ancho'] : 800
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Im&aacute;genes: Altura m&aacute;xima en pixeles') ?> <span class="required"></span>   
      <div class="label-descripcion">
        <?= __('Tambi&eacute;n aplica para sucursales.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_img_max_altura',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_img_max_altura'])) ? $config['negocios_img_max_altura'] : 800
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Im&aacute;genes: Peso m&aacute;ximo en kilobytes') ?> <span class="required"></span>      
      <div class="label-descripcion">
        <?= __('Tambi&eacute;n aplica para sucursales.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_img_max_peso',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_img_max_peso'])) ? $config['negocios_img_max_peso'] : 500
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('URL base para Facebook') ?> <span class="required"></span>      
      <div class="label-descripcion">
        <?= __('URL base para los enlaces de Facebook en los negocios') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_url_facebook',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_url_facebook'])) ? $config['negocios_url_facebook'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('URL base para Twitter') ?> <span class="required"></span>      
      <div class="label-descripcion">
        <?= __('URL base para los enlaces de Twitter en los negocios') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_url_twitter',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_url_twitter'])) ? $config['negocios_url_twitter'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('URL base para Google+') ?> <span class="required"></span>      
      <div class="label-descripcion">
        <?= __('URL base para los enlaces de Google+ en los negocios') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_url_google_plus',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_url_google_plus'])) ? $config['negocios_url_google_plus'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('URL base para Instagram') ?> <span class="required"></span>      
      <div class="label-descripcion">
        <?= __('URL base para los enlaces de Instagram en los negocios') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_url_instagram',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_url_instagram'])) ? $config['negocios_url_instagram'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  
  
  
  
  <!-- INICIA SUCURSALES -->
  <div class="form-group form-group-sm">    
    <div class="col-xs-12">
      <h3><?= __('Configuraci&oacute;n de sucursales') ?></h3>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Cantidad de im&aacute;genes por sucursal') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_sucursal_cant_imagenes',[
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['negocios_sucursal_cant_imagenes'])) ? $config['negocios_sucursal_cant_imagenes'] : 10
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  
  
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;meros de opciones visibles') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Opciones de cu&aacute;ntos registros se puede ver por p&aacute;gina en el administrador') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_opciones_visibles',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['negocios_opciones_visibles'])) ? $config['negocios_opciones_visibles'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de opci&oacute;n por defecto') ?><span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_opcion_defecto',[   
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['negocios_opcion_defecto'])) ? $config['negocios_opcion_defecto'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de zoom al desplegar un marcador en google maps') ?><span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('negocios_zoom_marcador',[   
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['negocios_zoom_marcador'])) ? $config['negocios_zoom_marcador'] : '',
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
