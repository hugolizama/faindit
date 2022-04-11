<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($usuario); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container container-min-height">
    <div class="row">
      <div class="col-xs-12"><h2 class="page-header"><?= __('Restableciendo contrase&ntilde;a') ?></h2></div>
    </div>
    <?php if($restablecer==0): ?>
    <div class="row">
      <div id="registro_exito_contenido" class="col-xs-12 text-center">
        <div class="registro_exito"><?= __('C&oacute;digo inv&aacute;lido') ?></div>
        <?php
          echo __('El c&oacute;digo de verificaci&oacute;n no es v&aacute;lido, puedes solicitar otro en ');
          echo $this->Html->link(__('Restablecer contrase&ntilde;a.'), [
            'prefix' => false,
            'controller' => 'Usuarios',
            'action' => 'restablecerContrasena'
            ], [
            'escape' => false
          ]);
        ?>
      </div>
    </div>  
    
    <?php elseif($restablecer == 1): ?>
    
    <?= $this->Form->create($usuario, ['id' => 'frm-contrasena', 'autocomplete' => 'off']); ?>    
    <div class="row">
      <div>
        <label class="control-label control-label-left col-md-offset-4 col-md-4 required"><?= __('Digite su nueva contrase&ntilde;a') ?></label>
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-md-offset-4 col-md-4">            
        <?php
        echo $this->Form->input('contrasena', array(
          'id' => 'contrasena',
          'class' => 'form-control',
          'div' => false,
          'label' => false,
          'type' => 'password',
          'value' => '',
          'autofocus'=>true
        ));
        ?>              
      </div> 
    </div>
    
    <div class="row">
      <div>
        <label class="control-label control-label-left col-md-offset-4 col-md-4 required"><?= __('Confirmar contrase&ntilde;a') ?></label>
      </div>
    </div>

    <div class="row margin-bottom-10x">
      <div class="col-md-offset-4 col-md-4">            
        <?php
        echo $this->Form->input('confirmar', array(
          'id' => 'contrasena',
          'class' => 'form-control',
          'div' => false,
          'label' => false,
          'type' => 'password',
          'value' => ''
        ));
        ?>              
      </div> 
    </div>

    <div class="row text-center">
      <div class="col-md-offset-4 col-md-4">
        <?php
        echo $this->Form->submit(__('Guardar'), array(          
          'class' => 'btn btn-success btn-margin-right',
          'div' => false,
        ));

        echo $this->Form->button(__('Cancelar'), array(
          'type' => 'reset',
          'class' => 'btn btn-danger',
          'div' => false,
        ));
        ?>
      </div>
    </div>
    <?= $this->Form->end(); ?>
    
    <script>
      $(window).load(function(){
        $('input[type=password]').val('');
      });
    </script>
    
    <?php endif; ?>
    
    
  </div>
</div>

