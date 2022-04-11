<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Configuraci&oacute;n del sitio') ?></h3>  
    </div>
  </div>
  
  <?= $this->Form->create($configsEntity, ['class'=>'form-horizontal']); ?>
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Nombre del sitio') ?> <span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_nombre',[
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_nombre'])) ? $config['sitio_nombre'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Nombre del sitio secundario') ?> <span class="required"></span>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_nombre_secundario',[
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_nombre_secundario'])) ? $config['sitio_nombre_secundario'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Descripci&oacute;n del sitio web') ?> <span class="required"></span>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->textarea('sitio_descripcion',[        
        'rows' => '3',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_descripcion'])) ? $config['sitio_descripcion'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Correo de administrador') ?> <span class="required"></span>
      <div class="label-descripcion">
        <?= __('Correo de administrador. Ser&aacute; usado para enviar correos hacia afuera del sitio.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_correo_administrador',[
        'type'=>'email',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_correo_administrador'])) ? $config['sitio_correo_administrador'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Correo No-Reply') ?>
      <div class="label-descripcion">
        <?= __('Correo utilizado para enviar notificaciones regulares.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_correo_notificacion',[
        'type'=>'email',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_correo_notificacion'])) ? $config['sitio_correo_notificacion'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Correo de contacto') ?>
      <div class="label-descripcion">
        <?= __('Correo al que se env&iacute;an los mensajes a trav&eacute;s del formulario de contacto.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_correo_contacto',[
        'type'=>'email',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_correo_contacto'])) ? $config['sitio_correo_contacto'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Correos a notificar en caso de errores') ?>
      <div class="label-descripcion">
        <?= __('Lista de correos a notificar en caso de algun error con el sitio.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->textarea('sitio_correo_errores',[        
        'rows' => '3',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_correo_errores'])) ? $config['sitio_correo_errores'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Formato de fecha para el sitio') ?>
      <span class="required"></span>
      <div class="label-descripcion">
        <?= __('Formato de fecha por defecto para el sitio. Formato corresponde a <a href=\'http://php.net/manual/es/function.date.php\' target=\'_blank\'>date_format de php</a>') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_formato_fecha',[
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_formato_fecha'])) ? $config['sitio_formato_fecha'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Fecha de inicio del sistema (dd-mm-yyyy)') ?> <span class="required"></span>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_fecha_inicio',[
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_fecha_inicio'])) ? $config['sitio_fecha_inicio'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('C&oacute;digo de rastreo') ?>
      <div class="label-descripcion">
        <?= __('Script para rastreo de navegaci&oacute;n como Google Analytics') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->textarea('sitio_script_rastreo',[        
        'rows' => '3',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_script_rastreo'])) ? $config['sitio_script_rastreo'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Redes sociales') ?></h3>  
    </div>
  </div>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Facebook') ?>     
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_facebook',[
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_facebook'])) ? $config['sitio_facebook'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('Twitter') ?>     
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_twitter',[
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value'=> (isset($config['sitio_twitter'])) ? $config['sitio_twitter'] : ''
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Ajustes de resultados en buscador') ?></h3>  
    </div>
  </div>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;meros de opciones visibles en la secci&oacute;n de resultados') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Opciones de cu&aacute;ntos registros se puede ver por p&aacute;gina en los resultados del buscador') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_buscador_opciones',[        
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['sitio_buscador_opciones'])) ? $config['sitio_buscador_opciones'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left col-xs-12 col-md-6 col-lg-5">
      <?= __('N&uacute;mero de opci&oacute;n por defecto en la secci&oacute;n de resultados') ?><span class="required"></span>      
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('sitio_buscador_opcion_defecto',[   
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['sitio_buscador_opcion_defecto'])) ? $config['sitio_buscador_opcion_defecto'] : '',
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
