<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container container-min-height" >
    <?= $this->Flash->render('error'); ?>
    <?= $this->Form->create($usuario,['class'=>'form-horizontal']); ?>
    <div class="row text-center">
      <h2><?= __('Iniciar sesi&oacute;n'); ?></h2>
    </div>    
    <div class="form-group">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
        <?= $this->Form->input('usuario',[
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Usuario o correo electr&oacute;nico'),
          'escape'=>false,
          'autofocus'=>'autofocus'
        ]); ?>
      </div>      
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
         <?= $this->Form->input('contrasena',[
          'type'=>'password',
          'label'=>false,
          'div'=>false,
          'class'=>'form-control input-md',
          'placeholder'=>__('Contrase&ntilde;a'),
          'escape'=>false,          
        ]); ?>
      </div>
    </div>
    
    <div class="form-group">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
         <?= $this->Form->checkbox('recordar',[
          'id'=>'recordar',
          'style'=>'transform: scale(1.5); margin-left: 5px;'
        ]); ?>
        <label for="recordar" style="margin-left: 5px;"><?= __('Mantener sesi&oacute;n abierta'); ?></label>
      </div>
    </div>
    
    <div class="form-group text-center">      
      <?=
      $this->Form->button(__('Iniciar sesi&oacute;n'), [
        'type' => 'submit',
        'class' => 'btn btn-md btn-success',
      ]);
      ?>  
    </div>
    
    <div class="form-group text-center">
      <?=
      $this->Html->link('Restablecer mi contrase&ntilde;a',[
        'controller'=>'Usuarios',
        'action'=>'restablecerContrasena'
      ],['escape'=>false]);
      ?>
      <div class="visible-sm-inline visible-md-inline visible-lg-inline">|</div>
      <div class="visible-xs margin-bottom-20x"></div>
      <?=
      $this->Html->link(__('Reenviar correo de activaci&oacute;n'),[
        'controller'=>'Usuarios',
        'action'=>'reenviarActivacion'
      ], ['escape'=>false]);
      ?>
    </div>
    
    
    <?= $this->Form->end(); ?>
  </div>
</div>

