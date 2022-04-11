<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($usuarioDatos); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container container-min-height">
    <?= $this->Form->create($usuario, ['class'=>'form-horizontal']) ?>
    <div class="row text-center">
      <h2><?= __('Restablecer contrase&ntilde;a'); ?></h2>
    </div>
    
    <div class="form-group text-center">
      <div class="col-xs-12 col-md-8 col-md-offset-2">
        <?= __('Digite el correo electr&oacute;nico de su cuenta para recibir instrucciones de como restablecer su contrase&ntilde;a'); ?>
      </div>      
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <?= $this->Form->input('correo',[
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Correo electr&oacute;nico'),
          'escape'=>false
        ]); ?>
      </div>      
    </div>
    
    <div class="form-group text-center">      
      <?=
      $this->Form->button(__('Verificar'), [
        'type' => 'submit',
        'class' => 'btn btn-md btn-success',
      ]);
      ?>
    </div>
    
    <?= $this->Form->end(); ?>
  </div>
</div>