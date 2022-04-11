<!-- Menu general de administrador -->
<li class="menu-title"><?= __('Acceso r&aacute;pido') ?></li>
<?php if (isset($perRol['admin_usuarios']) && $perRol['admin_usuarios']==1): ?>
<li>
  <?=
  $this->Html->link(__('Nuevo usuario'), [
    'prefix' => 'admin',
    'controller' => 'usuarios',
    'action' => 'nuevo'
  ]);
  ?>
</li>
<?php endif; ?>
<li>
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

<?php
if(isset($perRol['ver_panel_neg_sucursales']) && $perRol['ver_panel_neg_sucursales']==1 && isset($perRol['admin_neg_categorias']) && $perRol['admin_neg_categorias']==1){
?>

<li>
  <?=
  $this->Html->link(__('Categorias'), [
    'prefix' => 'admin',
    'controller' => 'negocios_sucursales',
    'action' => 'categorias'
  ],[
    'escape'=>false
  ]);
  ?>
</li>

<li>
  <?=
  $this->Html->link(__('Sugerencia categorias'), [
    'prefix' => 'admin',
    'controller' => 'negocios_sucursales',
    'action' => 'sugerenciaCategorias'
  ],[
    'escape'=>false
  ]);
  ?>
</li>

<?php
}
?>
