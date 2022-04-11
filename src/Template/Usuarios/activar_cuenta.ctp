<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($activacion); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container container-min-height">
    
    <?php if($activacion==1): ?>
    <div class="row">
      <div id="registro_exito_contenido" class="col-xs-12 text-center">
        <div class="registro_exito"><?= __('Tu cuenta ha sido activada exitosamente') ?></div>   
        <div>
          <?php
          echo __('Inicia sesi&oacute;n y registra tu negocio. ');
          echo $this->Html->link(__('Iniciar de sesi&oacute;n.'), [
            'prefix' => false,
            'controller' => 'Usuarios',
            'action' => 'login'
            ], [
            'escape' => false
          ]);
          ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    <?php if($activacion==2): ?>
    <div class="row">
      <div id="registro_exito_contenido" class="col-xs-12 text-center">
        <div class="registro_exito"><?= __('Tu cuenta ya ha sido activada anteriormente') ?></div>           
      </div>
    </div>
    <?php endif; ?>
    
    
    <?php if($activacion==0): ?>
    <div class="row">
      <div id="registro_exito_contenido" class="col-xs-12 text-center">
        <div class="registro_exito"><?= __('Activaci&oacute;n de cuenta inv&aacute;lido') ?></div>
        <?php
          echo __('El c&oacute;digo de activaci&oacute;n no es v&aacute;lido, puedes solicitar otro en el ');
          echo $this->Html->link(__('reenv&iacute;o del correo de activaci&oacute;n de cuenta.'), [
            'prefix' => false,
            'controller' => 'Usuarios',
            'action' => 'reenviarActivacion'
            ], [
            'escape' => false
          ]);
        ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>