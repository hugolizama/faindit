<!-- Menu de sucursales -->
<ul class="nav nav-tabs" role="navigation">
  <?php if(isset($menu_index)): ?>
  <li>    
    <?= __('Ver') ?>
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
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'listaSucursales',
      $negocio_id,
      $config['adm_negocios_opcion_defecto']
    ],[
      'escape'=>false,
    ]) ?>
  </li> 
  
  <li role="presentation" class="<?= (isset($ms_agregar) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-plus fa-fw"></span> '.__('Agregar'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'agregarSucursal',
      $negocio_id
    ],[
      'escape'=>false
    ]) ?>
  </li>  
  
  <?php if(isset($editar_sucursal)):  ?>
  <li role="presentation" class="<?= (isset($ms_general) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-info fa-fw"></span> '.__('General'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'editarSucursal',
      $sucursal_id
    ],[
      'escape'=>false
    ]) ?>
  </li> 
  
  <li role="presentation" class="<?= (isset($ms_mapa) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-map-marker fa-fw"></span> '.__('Mapa/Ubicaci&oacute;n'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'editarSucursalMapa',
      $sucursal_id
    ],[
      'escape'=>false
    ]) ?>
  </li> 
  
  <?php if (isset($perRol['cargar_elim_imagenes']) && $perRol['cargar_elim_imagenes']==1): ?>
  <li role="presentation" class="<?= (isset($ms_imagenes) ? 'active' : ''); ?>">
    <?= $this->Html->link('<span class="fa fa-image fa-fw"></span> '.__('Im&aacute;genes'), [
      'prefix'=>'admin',
      'controller'=>'NegociosSucursales',
      'action'=>'editarSucursalImg',
      $sucursal_id
    ],[
      'escape'=>false
    ]) ?>
  </li>  
  <?php endif; ?>
  <?php endif; ?>
</ul>
<!--<hr class="hr-10x" />-->