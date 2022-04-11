<!-- Menu de configuracion de usuarios -->
<li class="menu-title"><?= __('Usuarios') ?></li>
<?php if(isset($perRol['admin_usuarios']) && $perRol['admin_usuarios']==1): ?>
<li class="<?php echo (isset($usuarios_lista_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Usuarios'), [
    'prefix' => 'admin',
    'controller' => 'usuarios',
    'action' => 'index'
  ]);
  ?>
</li> 
<li class="<?php echo (isset($usuarios_suspendidos_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Usuarios suspendidos'), [
    'prefix' => 'admin',
    'controller' => 'usuarios',
    'action' => 'suspendidos'
  ]);
  ?>
</li> 
<li class="<?php echo (isset($usuarios_inactivos_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Usuarios inactivos'), [
    'prefix' => 'admin',
    'controller' => 'usuarios',
    'action' => 'inactivos'
  ]);
  ?>
</li>
<li class="<?php echo (isset($usuarios_notificaciones_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Notificaciones de usuarios'), [
    'prefix' => 'admin',
    'controller' => 'usuarios',
    'action' => 'notificacionesUsuarios'
  ]);
  ?>
</li>
<?php endif; ?>

<li class="menu-title"><?= __('Roles') ?></li>
<?php if(isset($perRol['administrar_roles']) && $perRol['administrar_roles']==1): ?>
<li class="<?php echo (isset($roles_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Roles'), [
    'prefix' => 'admin',
    'controller' => 'roles',
    'action' => 'index'
  ]);
  ?>
</li>
<?php endif; ?>

<li class="menu-title">
<?= __('Seguridad') ?>
</li>

<?php if(isset($perRol['admin_exclu_correo']) && $perRol['admin_exclu_correo']==1): ?>
<li class="<?php echo (isset($excluir_email_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Excluir email'), [
    'prefix' => 'admin',
    'controller' => 'usuarios',
    'action' => 'excluirEmail'
  ]);
  ?>
</li>
<?php endif; ?>

<?php if(isset($perRol['admin_exclu_ip']) && $perRol['admin_exclu_ip']==1): ?>
<li class="<?php echo (isset($excluir_ip_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Excluir IP'), [
    'prefix' => 'admin',
    'controller' => 'usuarios',
    'action' => 'excluirIp'
  ]);
  ?>
</li>
<?php endif; ?>

<?php if(isset($perRol['admin_exclu_nom_usua']) && $perRol['admin_exclu_nom_usua']==1): ?>
<li class="<?php echo (isset($excluir_usuarios_activo)) ? 'active':''; ?>">
<?=
$this->Html->link(__('Excluir nombres de usuario'), [
  'prefix' => 'admin',
  'controller' => 'usuarios',
  'action' => 'excluirUsuarios'
]);
?>
</li>
<?php endif; ?>
