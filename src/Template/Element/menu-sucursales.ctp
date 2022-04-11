<!-- Menu de sucursales -->
<ul class="nav nav-tabs" role="navigation">
  <?php if(isset($menu_index)): ?>
  <li>
     <label class="control-label"><?= __('Ver') ?></label>
      <?= $this->Form->select('limite', $verOpciones, [
        'id'=>'limite',
        'default'=>$limite,
        'class'=>'form-control pills-select display-inline-block width-auto margin-right-5x input-sm margin-bottom-5x-xs'
      ]) ?>
  </li>
  <li>
      <?= $this->Form->select('accion',[
        1=>__('Habilitar'),
        0=>__('Deshabilitar'),
        2=>__('Eliminar')
      ],[
        'id'=>'selAccion',
        'empty'=>__('Acciones'),
        'class'=>'form-control pills-select display-inline-block width-auto margin-right-5x input-sm margin-bottom-5x-xs',
        'escape'=>false
      ]) ?>
  </li>
  <li>
    <?php
    echo $this->Form->submit(__('Aplicar'), array(
      'id'=>'btnAplicar',
      'name'=>'btnAplicar',
      'class' => 'btn btn-success btn-sm margin-bottom-5x-xs',
      'disabled'=>true,
      'div' => false,          
    ));            
    ?>
  </li>
  <li class="hidden-xs"><div class="separador-vertical" style="border: 0px;"></div></li>
  <?php endif; ?>
  <li role="presentation" class="<?= (isset($ms_lista) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-building fa-fw"></span> '.__('Lista'), [
      'prefix'=>false,
      'controller'=>'Sucursales',
      'action'=>'index',
      $negocio_id,
      $config['negocios_opcion_defecto'],
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Lista de sucursales')
    ]) ?>
  </li> 
  <li role="presentation" class="<?= (isset($ms_agregar) ? 'active' : ''); ?> tab-agregar">
    <?= $this->Html->link('<span class="fa fa-plus fa-fw"></span> '.__('Agregar'), [
      'prefix'=>false,
      'controller'=>'Sucursales',
      'action'=>'agregar',
      $negocio_id,
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Agregar una sucursal')
    ]) ?>
  </li>  
  <?php if(isset($editar_sucursal)):  ?>
  <li role="presentation" class="<?= (isset($ms_general) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-info fa-fw"></span> '.__('General'), [
      'prefix'=>false,
      'controller'=>'Sucursales',
      'action'=>'editar',
      $sucursal_id,
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Informaci&oacute;n general')
    ]) ?>
  </li> 
  <li role="presentation" class="<?= (isset($ms_mapa) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-map-marker fa-fw"></span> '.__('Mapa/Unicaci&oacute;n'), [
      'prefix'=>false,
      'controller'=>'Sucursales',
      'action'=>'editarMapa',
      $sucursal_id,
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Editar ubicaci&oacute;n')
    ]) ?>
  </li> 
  
  <?php if (isset($perRol['cargar_elim_imagenes']) && $perRol['cargar_elim_imagenes']==1): ?>
  <li role="presentation" class="<?= (isset($ms_imagenes) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-image fa-fw"></span> '.__('Im&aacute;genes'), [
      'prefix'=>false,
      'controller'=>'Sucursales',
      'action'=>'editarImg',
      $sucursal_id,
      $tokenFalso
    ],[
      'escape'=>false,
      'title'=>__('Editar im&aacute;genes')
    ]) ?>
  </li>  
  <?php endif; ?>
  <?php endif; ?>
</ul>
<!--<hr class="hr-10x" />-->