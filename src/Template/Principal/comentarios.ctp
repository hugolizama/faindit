<div class="container-fluid">
  <div class="container container-min-height">
    
    <?= $this->Form->create(); ?>
    
    <div class="row">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
        <h3><?= __('Formulario de comentarios') ?></h3>
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= __('Aqu&iacute; puedes enviarnos tu sugerencias. Tambi&eacute;n puedes enviarnos informes de algo que no este 
          funcionando bien en la web o comentarios en general. <br/><i>Nombres y correo no son requeridos</i>.') ?>
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3">  
        <?= $this->Form->input('nombre_apellido', [
          'class'=>'form-control',
          'placeholder'=>__('Su nombre y apellido'),
          'div'=>false,
          'label'=>false,
          'value'=>$nombres
        ]); ?>
      </div>      
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3">  
        <?= $this->Form->input('correo', [
          'type'=>'email',
          'class'=>'form-control',
          'placeholder'=>__('Su correo electr&oacute;nico'),
          'div'=>false,
          'label'=>false,
          'escape'=>false,
          'value'=>$correo
        ]); ?>
      </div>      
    </div>   
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= __('Si reportas un problema es importante que nos puedas indicar que estabas haciendo, el navegador que utilizas, 
          la direcci&oacute;n web en la que navegabas, etc. Entre mas detalles mejor.') ?>
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3">          
        <?= $this->Form->textarea('mensaje', [
          'class'=>'form-control',
          'placeholder'=>__('Su mensaje'),
          'div'=>false,
          'label'=>false,
          'escape'=>false,
          'rows'=>5,
          'required'=>true /*necesario*/
        ]); ?>
      </div>      
    </div>
    
    <?php if(!isset($cookieUsuario)): ?>
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3">          
        <div id='gCaptchaContacto'></div>
      </div>      
    </div>  
    <?php endif; ?>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
        <?= $this->Form->submit(__('Enviar'), array(
          'class' => 'btn btn-primary btn-margin-right',
          'div' => false,          
        )); ?>
        
         <?= $this->Form->reset(__('Restablecer'), array(
          'class' => 'btn btn-danger btn-margin-right',
          'div' => false,          
        )); ?>
      </div>
    </div>
    
    <?= $this->Form->end(); ?>
    
  </div>
</div>

<?php if(!isset($cookieUsuario)): ?>
<script>
 var widgetId1;
  function generarCaptcha(){
    widgetId1 = grecaptcha.render('gCaptchaContacto', {
      'sitekey' : '6LeI6Q8TAAAAAL_yOH82ttIyccRrIMwK7Ki784YV'
    });
  }
</script>
<script src='https://www.google.com/recaptcha/api.js?onload=generarCaptcha&render=explicit' defer async></script>
<?php endif; ?>