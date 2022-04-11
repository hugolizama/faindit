<!-- Menu configuraciones del sitio -->
<li class="menu-title"><?= __('Configuraci&oacute;n del sitio') ?></li>

<?php if(isset($perRol['cambiar_config_sitio']) && $perRol['cambiar_config_sitio']==1): ?>
<li class="<?php echo (isset($configuracion_sidebar_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Configuraci&oacute;n del sitio'), [
    'prefix' => 'admin',
    'controller' => 'configs',
    'action' => 'index'
  ],[
    'escape'=>false
  ]);
  ?>
</li> 
<?php endif; ?>

<?php if(isset($perRol['deshabilitar_sitio']) && $perRol['deshabilitar_sitio']==1): ?>
<li class="<?php echo (isset($deshabilitar_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Deshabilitar sitio'), [
    'prefix' => 'admin',
    'controller' => 'configs',
    'action' => 'deshabilitar'
  ],[
    'escape'=>false
  ]);
  ?>
</li> 
<?php endif; ?>

<?php if(isset($perRol['cambiar_config_sitio']) && $perRol['cambiar_config_sitio']==1): ?>
<li class="<?php echo (isset($mantenimiento_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Mantenimiento programado'), [
    'prefix' => 'admin',
    'controller' => 'configs',
    'action' => 'mantenimientoProgramado'
  ],[
    'escape'=>false
  ]);
  ?>
</li> 
<?php endif; ?>

<?php if(isset($perRol['cambiar_ajustes_correo']) && $perRol['cambiar_ajustes_correo']==1): ?>
<li class="<?php echo (isset($correo_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Ajustes de correo electr&oacute;nico'), [
    'prefix' => 'admin',
    'controller' => 'configs',
    'action' => 'correo'
  ],[
    'escape'=>false
  ]);
  ?>
</li>
<?php endif; ?>

<?php if(isset($perRol['cambiar_ajustes_usuario_registro']) && $perRol['cambiar_ajustes_usuario_registro']==1): ?>
<li class="<?php echo (isset($usuarios_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Ajustes de usuario y registro'), [
    'prefix' => 'admin',
    'controller' => 'configs',
    'action' => 'usuarios'
  ],[
    'escape'=>false
  ]);
  ?>
</li>
<?php endif; ?>

<?php if(isset($perRol['cambiar_ajustes_administrador']) && $perRol['cambiar_ajustes_administrador']==1): ?>
<li class="<?php echo (isset($administrador_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Ajustes del administrador'), [
    'prefix' => 'admin',
    'controller' => 'configs',
    'action' => 'administrador'
  ],[
    'escape'=>false
  ]);
  ?>
</li>
<?php endif; ?>

<?php if(isset($perRol['cambiar_ajustes_negocios_sucursales']) && $perRol['cambiar_ajustes_negocios_sucursales']==1): ?>
<li class="<?php echo (isset($negocios_sucursales_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Ajustes de negocios y sucursales'), [
    'prefix' => 'admin',
    'controller' => 'configs',
    'action' => 'negociosSucursales'
  ],[
    'escape'=>false
  ]);
  ?>
</li>
<?php endif; ?>
