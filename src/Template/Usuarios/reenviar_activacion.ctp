<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container container-min-height">
    <?= $this->Form->create($usuario, ['class'=>'form-horizontal']) ?>
    <div class="row text-center">
      <h2><?= __('Reenviar activaci&oacute;n de cuenta'); ?></h2>
    </div>
    
    <div class="form-group text-center">
      <div class="col-xs-12 col-md-8 col-md-offset-2">
        <?= __('Si no recibe el correo para activar su cuenta puede solicitar uno nuevo digitando el correo electr&oacute;nico que utiliz&oacute; para registrarse'); ?>
      </div>      
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-md-offset-3">
        <?= $this->Form->input('correo',[
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Correo electr&oacute;nico'),
          'escape'=>false,
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