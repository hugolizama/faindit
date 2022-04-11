<!-- Menu de configuracion de usuarios -->
<li class="menu-title"><?= __('Negocios') ?></li>

<?php if(isset($perRol['admin_neg_sucursales']) && $perRol['admin_neg_sucursales']==1): ?>
<li class="<?php echo (isset($neg_negocios_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Negocios y sucursales'), [
    'prefix' => 'admin',
    'controller' => 'NegociosSucursales',
    'action' => 'index'
  ], [
    'escape'=>false
  ]);
  ?>
</li> 
<?php endif; ?>

<?php if(isset($perRol['admin_neg_categorias']) && $perRol['admin_neg_categorias']==1): ?>
<li class="<?php echo (isset($neg_categorias_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Categor&iacute;as'), [
    'prefix' => 'admin',
    'controller' => 'NegociosSucursales',
    'action' => 'categorias'
  ], [
    'escape'=>false
  ]);
  ?>
</li> 

<li class="<?php echo (isset($neg_sugerencia_categorias_activo)) ? 'active':''; ?>">
  <?=
  $this->Html->link(__('Sugerencia de Categor&iacute;as'), [
    'prefix' => 'admin',
    'controller' => 'NegociosSucursales',
    'action' => 'sugerenciaCategorias'
  ], [
    'escape'=>false
  ]);
  ?>
</li> 
<?php endif; ?>