<!-- Menu negocios editar -->
<ul class="nav nav-tabs">
  <li role="presentation" class="<?= (isset($me_general) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-info fa-fw"></span> '.__('General'), [
      'prefix'=>false,
      'controller'=>'Negocios',
      'action'=>'editar',
      $negocio_id,
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Informaci&oacute;n general')
    ]) ?>
  </li>
  <li role="presentation" class="<?= (isset($me_mapa) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-map-marker fa-fw"></span> '.__('Mapa/Ubicaci&oacute;n'), [
      'prefix'=>false,
      'controller'=>'Negocios',
      'action'=>'editarMapa',
      $negocio_id,
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Editar ubicaci&oacute;n')
    ]) ?>
  </li>
  
  <?php if (isset($perRol['cargar_elim_imagenes']) && $perRol['cargar_elim_imagenes']==1): ?>
  <li role="presentation" class="<?= (isset($me_imagenes) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-image fa-fw"></span> '.__('Im&aacute;genes'), [
      'prefix'=>false,
      'controller'=>'Negocios',
      'action'=>'editarImg',
      $negocio_id,
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Editar im&aacute;genes')
    ]) ?>
  </li>  
  <?php endif; ?>
</ul>
<!--<hr class="hr-10x" />-->