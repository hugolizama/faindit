<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container container-min-height" >
    
    <?php if($formularioDesactivado == 0): ?>
    
    
    <?= $this->Form->create($usuario,['class'=>'form-horizontal', 'autocomplete'=>'off']); ?>
    <div class="row text-center">
      <h2><?= __('Formulario de registro'); ?></h2>
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <?= $this->Form->input('usuario',[
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Usuario'),
          'value'=>(isset($data['usuario'])) ? $data['usuario'] : '',
        ]); ?>
      </div>      
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <?= $this->Form->input('correo',[
          'type'=>'email',
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Correo electr&oacute;nico'),
          'escape'=>false,
          'value'=>(isset($data['correo'])) ? $data['correo'] : '',
        ]); ?>
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <?= $this->Form->input('confirmarcorreo',[
          'type'=>'email',
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Confirmar correo electr&oacute;nico'),
          'escape'=>false,
          'value'=>(isset($data['confirmarcorreo'])) ? $data['confirmarcorreo'] : ''
        ]); ?>
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <?= $this->Form->input('contrasena',[
          'type'=>'password',
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Contrase&ntilde;a'),
          'escape'=>false,
          'value' => '',
        ]); ?>
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <?= $this->Form->input('confirmar',[
          'type'=>'password',
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Confirmar contrase&ntilde;a'),
          'escape'=>false,
          'value' => '',
        ]); ?>
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <div id='gCaptchaRegistro'></div>
      </div>
    </div>
    
    <div class="form-group text-center">
      <?=
      $this->Html->link(__('Condiciones de uso'),[
        'controller'=>'Principal',
        'action'=>'condicionesDeUso'
      ],['escape'=>false, 'target'=>'_blank']);
      ?> -
      <?=
      $this->Html->link(__('Pol&iacute;ticas de privacidad'),[
        'controller'=>'Principal',
        'action'=>'politicasDePrivacidad'
      ],['escape'=>false, 'target'=>'_blank']);
      ?>
    </div>
    
    <div class="form-group text-center">      
      <?php
      echo $this->Form->button(__('Registrar'), [
        'type' => 'submit',
        'class' => 'btn btn-md btn-success btn-margin-right',
      ]);
      
      echo $this->Form->button(__('Cancelar'), [
        'class' => 'btn btn-md btn-danger',
        'type' => 'reset'
      ]);
      ?>
    </div>
    
    <div class="form-group text-center">
      <?= __('Al registrarse esta aceptando nuestras Condiciones de uso y Pol&iacute;ticas de privacidad.'); ?>
    </div>
    
    <div class="form-group text-center">
      <?=
      $this->Html->link(__('Reenviar activaci&oacute;n de cuenta'),[
        'controller'=>'Usuarios',
        'action'=>'reenviarActivacion'
      ],['escape'=>false]);
      ?>
    </div>
    <?= $this->Form->end(); ?>
    
    
    <?php else: ?>
    
    <div class="row">
      <div id="registro_exito_contenido" class="col-xs-12 text-center">
        <div class="registro_exito">
          <?= __('El formulario de registro ha sido desactivado.') ?>
        </div>
      </div>
    </div>
    
    <?php endif; ?>
    
  </div>
</div>
<script>
  var widgetId1;
  function generarCaptcha(){
    widgetId1 = grecaptcha.render('gCaptchaRegistro', {
      'sitekey' : '6LeI6Q8TAAAAAL_yOH82ttIyccRrIMwK7Ki784YV'
    });
  }
</script>
<script src='https://www.google.com/recaptcha/api.js?onload=generarCaptcha&render=explicit' defer async></script>