<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container container-min-height" >
    <?= $this->Form->create($usuario,['class'=>'form-horizontal', 'id'=>'form' ,'autocomplete'=>'off']); ?>
    <div class="row text-center">
      <h2><?= __('Eliminaci&oacute;n de cuenta'); ?></h2>
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= __('Digite su contrase&ntilde;a actual para confirmar la eliminaci&oacute;n de su cuenta. Esto eliminar&aacute; 
          su usuario, datos y dem&aacute;s informaci&oacute;n asociada. Este paso es irreversible.') ?>
      </div>      
    </div>
        
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <input type="password" style="display: none;" />
        <?php
        echo $this->Form->input('contrasena_actual', array(
          'id' => 'contrasena',
          'class' => 'form-control',
          'div' => false,
          'label' => false,
          'type' => 'password',
          'placeholder'=>__('Contrase&ntilde;a'),
          'value' => null,
          'escape'=>false
        ));
        ?> 
      </div>
    </div>
    
    <div class="form-group text-center">      
      <?php
      echo $this->Form->button(__('Aceptar'), [
        'type' => 'submit',
        'class' => 'btn btn-md btn-success btn-margin-right',
      ]);
      ?>
    </div>
    
    <?= $this->Form->end(); ?>
  </div>
</div>
<script>
  $(window).load(function(){
    $('input[type=password]').val('');
  });
</script>
