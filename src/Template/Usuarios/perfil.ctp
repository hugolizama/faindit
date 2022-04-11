<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col-xs-12"><h2 class="page-header"><?= __('Perfil de usuario') ?></h2></div>
    </div>


    <div class="row">
      <div class="col-sm-4 col-md-3 col-lg-3">
        <?= $this->element('menu-perfil'); ?>
      </div>
      <div class="col-sm-8 col-md-9 col-lg-9">
        <?= $this->Form->create($usuario, ['id'=>'frm-perfil', 'class' => 'form-horizontal']); ?>
        <div class="form-group">
          <?php if(isset($perRol['cambiar_usuario']) && $perRol['cambiar_usuario']==1): ?>
          <label class="control-label col-md-4 col-lg-3 required"><?= __('Nombre de usuario') ?></label>
          <div class="col-md-6 col-lg-5">  
            <?php
            echo $this->Form->input('usuario', array(
              'class' => 'form-control',
              'div' => false,
              'label' => false,
            ));
            ?>
          </div>
          <?php else: ?>
          
          <label class="control-label col-md-4 col-lg-3"><?= __('Nombre de usuario') ?></label>
          <label class="control control-label-left col-md-6 col-lg-5">  
            <?php
            echo $usuario->usuario;
            ?>
          </label>
          
          <?php endif; ?>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-4 col-lg-3 required"><?= __('Correo electr&oacute;nico') ?></label>
          <div class="col-md-6 col-lg-5">  
             <?= $this->Form->input('correo',[
              'label'=>false,
              'div'=>false,
              'class'=>'form-control',
              'escape'=>false
            ]); ?>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-4 col-lg-3"><?= __('Nombres') ?></label>
          <div class="col-md-6 col-lg-5">
            <?php
            echo $this->Form->input('nombres', array(
              'class' => 'form-control',
              'div' => false,
              'label' => false
            ));
            ?>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-4 col-lg-3"><?= __('Apellidos') ?></label>
          <div class="col-md-6 col-lg-5">
            <?php
            echo $this->Form->input('apellidos', array(
              'class' => 'form-control',
              'div' => false,
              'label' => false
            ));
            ?>
          </div>
        </div>        
                
        <div class="form-group text-center">
          <div class="col-md-9 col-lg-7">
            <?php
            echo $this->Form->submit(__('Guardar'), array(
              'name'=>'btnGeneral',
              'class' => 'btn btn-success btn-margin-right',
              'div' => false,          
            ));

            echo $this->Form->button(__('Cancelar'), array(
              'type'=>'reset',
              'class' => 'btn btn-danger',
              'div' => false,          
            ));
            ?>
          </div>
        </div>
        <div class="form-group text-right" style="margin: 0px; padding: 0px; font-size: 12px;">
          <div class="col-xs-12">
            <?= $this->Html->link(__('Eliminar mi cuenta'),[              
              'prefix' => false,
              'controller'=>'Usuarios',
              'action'=>'eliminarCuenta'
            ]); ?>
          </div>
        </div>
        
        <?= $this->Form->end(); ?>
        
        
        <?php if(isset($perRol['cambiar_contrasena']) && $perRol['cambiar_contrasena']==1): ?>
        
        <div class="row">
          <div id="cambiar-contrasena" class="col-xs-12"><h4 class="page-header"><?= __('Cambiar contrase&ntilde;a') ?></h4></div>
        </div>
        
        <?= $this->Form->create($usuario,['id'=>'frm-contrasena','class'=>'form-horizontal' ,'autocomplete'=>'off']); ?>
        <div class="form-group">
          <label class="control-label col-md-4 col-lg-3 required"><?= __('Contrase&ntilde;a actual') ?></label>
          <div class="col-md-6 col-lg-5">  
            <input type="password" style="display: none;" />
            <?php
            echo $this->Form->input('contrasena_actual', array(
              'id' => 'contrasena',
              'class' => 'form-control',
              'div' => false,
              'label' => false,
              'type' => 'password',
              'value'=>''				
            ));
            ?>              
          </div> 
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-4 col-lg-3 required"><?= __('Nueva contrase&ntilde;a') ?></label>
          <div class="col-md-6 col-lg-5">            
            <?php
            echo $this->Form->input('contrasena', array(
              'id' => 'contrasena',
              'class' => 'form-control',
              'div' => false,
              'label' => false,
              'type' => 'password',
              'value'=>''				
            ));
            ?>              
          </div> 
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-4 col-lg-3 required"><?= __('Confirmar contrase&ntilde;a') ?></label>
          <div class="col-md-6 col-lg-5">            
            <?php
            echo $this->Form->input('confirmar', array(
              'id' => 'contrasena',
              'class' => 'form-control',
              'div' => false,
              'label' => false,
              'type' => 'password',
              'value'=>''				
            ));
            ?>              
          </div> 
        </div>
        
        <div class="form-group text-center">
          <div class="col-md-9 col-lg-7">
            <?php
            echo $this->Form->submit(__('Guardar'), array(
              'name'=>'btnContrasena',
              'class' => 'btn btn-success btn-margin-right',
              'div' => false,          
            ));

            echo $this->Form->button(__('Cancelar'), array(
              'type'=>'reset',
              'class' => 'btn btn-danger',
              'div' => false,          
            ));
            ?>
          </div>
        </div>
        <?= $this->Form->end(); ?>
        
        <?php endif; ?>
        
        
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    
  });
  
  $(window).load(function(){
    $('input[type=password]').val('');
  });
</script>
