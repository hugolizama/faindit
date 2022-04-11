<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($usuario); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3>
        <?php
        switch ($usuario->estado) {
          case 0:
            $accion = 'inactivos';
            $texto1 = 'inactivos';
            break;
          case 1:
            $accion = 'index';
            $texto1 = '';
            break;
          case 2:
            $accion = 'suspendidos';
            $texto1 = 'suspendidos';
            break;
          default:
            $accion = '';
            $texto1 = '';
            break;
        }
        
        
        echo $this->Html->link(__('Usuarios').' '.$texto1, [
          'prefix' => 'admin',
          'controller' => 'usuarios',
          'action' => $accion
        ]);
        ?> / 
        <?= __('Editar usuario') ?>       
      </h3>       
    </div>
  </div>

  <?php
  echo $this->Form->create($usuario, array(
    'class' => 'form-horizontal',
    'autocomplete' => 'off'
  ));
  ?>

  <div class="row">
    <div class="col-md-8 col-lg-6">
      
      <div class="form-group form-group-sm">
        <label class="control-label col-md-4 col-lg-4"><?= __('Fecha de registro'); ?></label>
        <div class="col-md-8 col-lg-8" style="padding-top: 5px;">
          <?php
          echo $usuario['fechaRegistroFormat'];
          ?>
        </div>
      </div>
      
      <div class="form-group form-group-sm">
        <div class="control-label  col-md-4 col-lg-4 required">
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
        <div class="col-md-8 col-lg-8">    
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
        <label class="control-label col-md-4 col-lg-4"><?= __('Nombres'); ?></label>
        <div class="col-md-8 col-lg-8">
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
        <label class="control-label col-md-4 col-lg-4"><?= __('Apellidos') ?></label>
        <div class="col-md-8 col-lg-8">
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
        <label class="control-label col-md-4 col-lg-4 required"><?= __('Correo electr&oacute;nico') ?></label>
        <div class="col-md-8 col-lg-8">
          <?php
          echo $this->Form->input('correo', array(
            'type' => 'email',
            'class' => 'form-control',
            'div' => false,
            'label' => false
          ));
          ?>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label class="control-label col-md-4 col-lg-4"><?= __('Contrase&ntilde;a') ?></label>
        <div class="col-md-8 col-lg-8">
          <div class="input-group">
            <?php
            echo $this->Form->input('contrasena', array(
              'id' => 'contrasena',
              'class' => 'form-control',
              'div' => false,
              'label' => false,
              'type' => 'password',
              'value'=>'',
            ));
            ?>
            <span class="input-group-addon" style="cursor: pointer;" title="Ver/Ocular contrase&ntilde;a"><input type="checkbox" id="mostrar_pass" /></span>
          </div>
        </div> 
      </div>
      <div class="form-group form-group-sm">
        <label class="control-label col-md-4 col-lg-4 required"><?= __('Rol') ?></label>
        <div class="col-md-8 col-lg-8">
          <?php
          echo $this->Form->input('rol_id', array(
            'class' => 'form-control',
            'div' => false,
            'label' => false,
            'empty' => 'Seleccione',
            'options' => $listaRoles,            
          ));
          ?>
        </div>
      </div>

      <div class="form-group form-group-sm">    
        <div class="col-md-offset-4 col-md-8 col-lg-offset-4 col-lg-8">
          <?php
          if($usuario->estado == 0){ /*inactivo*/
            echo $this->Form->checkbox('estado', [
              'hiddenField'=>false,
              'id' => 'estado_usuario',
              'value'=>1
            ]);
            echo '<label for="estado_usuario" class="label-light">'.__('Activar usuario').'</label>';
          }elseif($usuario->estado == 1){ /*usuario activo*/
            echo $this->Form->checkbox('estado', [
              'hiddenField'=>false,
              'id' => 'estado_usuario',
              'value'=>2
            ]);
            echo '<label for="estado_usuario" class="label-light">'.__('Suspender usuario').'</label>';
          }elseif($usuario->estado == 2){ /*suspendido*/
            echo $this->Form->checkbox('estado', [
              'hiddenField'=>false,
              'id' => 'estado_usuario',
              'value'=>1
            ]);
            echo '<label for="estado_usuario" class="label-light">'.__('Habilitar usuario').'</label>';
          }          
          ?>
          
        </div>    
      </div>

      <div id="div_suspension" style="display: none;">
        <div class="form-group">    
          <label class="control-label col-md-4 col-lg-4"><?= __('Raz&oacute;n de suspensi&oacute;n') ?></label>
          <div class="col-md-8 col-lg-8">
            <?=
            $this->Form->textarea('razon_suspension', [
              'id' => 'razon_suspension',
              'class' => 'form-control'
            ]);
            ?>
          </div>    
        </div>
        
        <div class="form-group form-group-sm">
          <label class="control-label col-md-4 col-lg-4"><?= __('Suspender hasta') ?></label>
          <div id="div-fecha-suspension" class="col-md-8 col-lg-8"></div>
        </div>
      </div>      
      
      <div class="form-group form-group-sm text-center">
        <div class="col-md-8 col-lg-8">
          <?php
          echo $this->Form->submit('Guardar', array(
            'class' => 'btn btn-success btn-margin-right',
            'div' => false,
          ));

          echo $this->Html->link('Cancelar', array(
            'prefix'=>'admin',
            'controller' => 'usuarios',
            'action' => $accion
            ), array(
            'div' => false,
            'class' => 'btn btn-danger',
            'escape' => false
          ));
          ?>
        </div>
      </div>
    </div>
    <?php if($usuario->estado > 0): ?>
    <div class="col-md-4 col-lg-6">
      <h4 style="margin-top: 0px;"><?= __('Lista de negocios') ?></h4>
      <div class="list-group">
        <?php
        foreach ($usuario['negocios'] as $neg){           
          echo $this->Html->link($neg['nombre'], [
            'prefix'=>'admin', 'controller'=>'NegociosSucursales', 'action'=>'editarNegocio',
            $neg['id']
          ], [
            'class'=>'list-group-item'
          ]);
        }
        ?>
      </div>
    </div>
    <?php endif; ?>
  </div>


<?php echo $this->Form->end(); ?>
</div>
<script>
  $(document).ready(function () {
    
    function getFechasSuspension(estado_id){
      
      var url = "<?= \Cake\Routing\Router::url(['prefix'=>'admin', 'controller'=>'Usuarios', 'action'=>'getFechasSuspension']) ?>";
      
      if(estado_id==2){
        url = "<?= \Cake\Routing\Router::url(['prefix'=>'admin', 'controller'=>'Usuarios', 'action'=>'getFechasSuspension']) ?>/" + estado_id;
      }
      
      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html'
      }).done(function(data){
        $('#div-fecha-suspension').html(data);
        $('#div_suspension').slideDown();
      });
    }
    
    /*activar popover*/
    $('[data-toggle="popover"]').popover();
    
    
    <?php if($usuario->estado == 1): ?>
    /*verificar si mostrar texto de suspension*/
    if($('#estado_usuario').is(':checked')){
      getFechasSuspension();
    }
    
    $('#estado_usuario').click(function(){
      if($(this).is(':checked')){
        getFechasSuspension();
      }else{
        $('#razon_suspension').val('');
        $('#div_suspension').slideUp();
      }
    });
    <?php elseif($usuario->estado == 2): ?>
      
    getFechasSuspension(<?= $usuario->estado ?>);
    
    <?php endif; ?>    
      
    $('#mostrar_pass').click(function () {
      if ($('#mostrar_pass').is(':checked')) {
        $('#contrasena').attr('type', 'text');
      } else {
        $('#contrasena').attr('type', 'password');
      }
    });
    
    
  });
</script>
