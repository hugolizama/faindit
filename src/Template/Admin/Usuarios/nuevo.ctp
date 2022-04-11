<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3>
        <?= $this->Html->link(__('Usuarios'),[
          'prefix'=>'admin',
          'controller'=>'usuarios',
          'action'=>'index'
        ]); ?> / 
        <?= __('Nuevo usuario') ?>       
      </h3>       
    </div>
  </div>
  
  <?php
  echo $this->Form->create($usuario, array(
    'class' => 'form-horizontal',
    'autocomplete' => 'off'
  ));
  ?>

  <div class="form-group form-group-sm">
    <div class="control-label col-md-3 col-lg-2 required">
      <label class="control-label"><?= __('Nombre de usuario') ?></label>
      <a tabindex="0" role="button" data-toggle="popover" 
         data-trigger="focus" data-placement="right" data-html="true"
         data-content=' 
         <div class="popover-reg-usuarios">
         <?= __('Guardado en min&uacute;sculas, acepta letras, n&uacute;meros y gui&oacute;n (-_)') ?>         
         </div>
         ' >
        <span class="fa fa-question-circle indicacion-icono"></span>
      </a>
    </div>
    <div class="col-md-6 col-lg-4">    
      <?php
      echo $this->Form->input('usuario', array(
        'class' => 'form-control',
        'div' => false,
        'label' => false
      ));
      ?>
    </div>
  </div>
  <div class="form-group form-group-sm">
    <label class="control-label col-md-3 col-lg-2"><?= __('Nombres'); ?></label>
    <div class="col-md-6 col-lg-4">
      <?php
      echo $this->Form->input('nombres', array(
        'class' => 'form-control',
        'div' => false,
        'label' => false
      ));
      ?>
    </div>
  </div>
  <div class="form-group form-group-sm">
    <label class="control-label col-md-3 col-lg-2"><?= __('Apellidos') ?></label>
    <div class="col-md-6 col-lg-4">
      <?php
      echo $this->Form->input('apellidos', array(
        'class' => 'form-control',
        'div' => false,
        'label' => false
      ));
      ?>
    </div>
  </div>
  <div class="form-group form-group-sm">
    <label class="control-label col-md-3 col-lg-2 required"><?= __('Correo electr&oacute;nico') ?></label>
    <div class="col-md-6 col-lg-4">
      <?php
      echo $this->Form->input('correo', array(
        'type'=>'email',
        'class' => 'form-control',
        'div' => false,
        'label' => false
      ));
      ?>
    </div>
  </div>
  <div class="form-group form-group-sm">
    <label class="control-label col-md-3 col-lg-2 required"><?= __('Contrase&ntilde;a') ?></label>
    <div class="col-md-6 col-lg-4">
      <div class="input-group">
        <?php
        echo $this->Form->input('contrasena', array(
          'id' => 'contrasena',
          'class' => 'form-control',
          'div' => false,
          'label' => false,
          'type' => 'password',          			
        ));
        ?>
        <span class="input-group-addon" style="cursor: pointer;" title="Ver/Ocular contrase&ntilde;a"><input type="checkbox" id="mostrar_pass" /></span>
      </div>
    </div> 
  </div>
  <div class="form-group form-group-sm">
    <label class="control-label col-md-3 col-lg-2 required"><?= __('Rol') ?></label>
    <div class="col-md-6 col-lg-4">
      <?php
      echo $this->Form->input('rol_id', array(
        'class' => 'form-control',
        'div' => false,
        'label' => false,
        'empty' => 'Seleccione',
        'options'=>$listaRoles,
        'default'=>(isset($config['usuarios_rol_id_defecto'])) ? $config['usuarios_rol_id_defecto'] : ''
      ));
      ?>
    </div>
  </div>
  
  <div class="form-group">    
    <div class="col-md-offset-3 col-md-6 col-lg-offset-2 col-lg-4">
      <?= $this->Form->checkbox('estado',[
        'id'=>'estado_usuario'
      ]); ?>
      <label for="estado_usuario" class="label-light"><?= __('Activar usuario') ?></label>
    </div>    
  </div>
  
  <div class="form-group">    
    <div class="col-md-offset-3 col-md-6 col-lg-offset-2 col-lg-4">
      <?= $this->Form->checkbox('notificar_correo',[
        'id'=>'notificar_correo'
      ]); ?>
      <label for="notificar_correo" class="label-light"><?= __('Notificar por correo') ?></label>
    </div>    
  </div>
  <div class="form-group form-group-sm text-center">
    <div class="col-md-6 col-lg-4">
      <?php
      echo $this->Form->submit('Guardar', array(
        'class' => 'btn btn-success btn-margin-right',
        'div' => false,
      ));

      echo $this->Html->link('Cancelar', array(
        'prefix'=>'admin',
        'controller' => 'usuarios',
        'action' => 'index'
        ), array(
        'div' => false,
        'class' => 'btn btn-danger',
        'escape' => false
      ));
      ?>
    </div>
  </div>
<?php echo $this->Form->end(); ?>
</div>
<script>
  $(document).ready(function () {
    /*activar popover*/
    $('[data-toggle="popover"]').popover();
  
  
    $('#mostrar_pass').click(function () {
      if ($('#mostrar_pass').is(':checked')) {
        $('#contrasena').attr('type', 'text');
      } else {
        $('#contrasena').attr('type', 'password');
      }
    });
  });
</script>