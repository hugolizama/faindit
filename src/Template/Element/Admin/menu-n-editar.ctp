<!-- Menu negocios editar -->
<ul class="nav nav-tabs">
  <li role="presentation">
    <?= $this->Html->link('<span class="fa fa-share fa-fw"></span> '.__('Ver'), [
      'prefix'=>false,
      'controller'=>'N',
      'action'=>'index',
      $sucursalPrincipal, 
      $negocio['nombre_slug']
    ],[
      'escape'=>false,
      'target'=>'_blank'
    ]) ?>
  </li>
  
  <li role="presentation" class="<?= (isset($me_general) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-info fa-fw"></span> '.__('General'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'editarNegocio',
      $negocio_id,                
    ],[
      'escape'=>false
    ]) ?>
  </li>
  <li role="presentation" class="<?= (isset($me_mapa) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-map-marker fa-fw"></span> '.__('Mapa/Ubicaci&oacute;n'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'editarNegocioMapa',
      $negocio_id
    ],[
      'escape'=>false
    ]) ?>
  </li>


  <li role="presentation" class="<?= (isset($me_imagenes) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-image fa-fw"></span> '.__('Im&aacute;genes'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'editarNegocioImg',
      $negocio_id
    ],[
      'escape'=>false
    ]) ?>
  </li>
  
  <li role="presentation" class="<?= (isset($me_sucursales) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-building fa-fw"></span> '.__('Sucursales'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'listaSucursales',
      $negocio_id
    ],[
      'escape'=>false
    ]) ?>
  </li>
</ul>
<!--<hr class="hr-10x" />-->