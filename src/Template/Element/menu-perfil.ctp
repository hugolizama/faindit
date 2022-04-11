<!-- Menu de perfiles -->
<div class="sidebar-nav">
  <div class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <span class="visible-xs navbar-brand"><?= __('Menu') ?></span>
    </div>
    <div class="navbar-collapse collapse sidebar-navbar-collapse">
      <ul class="nav navbar-nav menu-perfil" id="menu-perfil">        
        <li class="<?= (isset($activo_perfil)) ? 'active' : ''; ?>">
          <?= $this->Html->link(__('Perfil'), ['prefix'=>false,'controller' => 'usuarios', 'action' => 'perfil']) ?>
        </li>
        <li class="divider-menu"></li>
        <li class="dropdown-header">
          Negocios
        </li>
        <?php if(isset($perRol['crear_negocios']) && $perRol['crear_negocios']==1): ?>
        <li class="<?= (isset($activo_agregar_neg)) ? 'active' : ''; ?>">
          <?= 
          $this->Html->link(__('<span class="fa fa-plus"></span> Agregar'), [
            'prefix'=>false,
            'controller'=>'Negocios',
            'action'=>'agregar'
          ], [
            'escape'=>false,
            'title'=>__('Agregar un nuevo negocio')
          ]);
          ?>
        </li>
        <?php endif; ?>
        
        <?php foreach($listaNegocios as $negocio): ?>
        <li class="<?php echo (isset($negocio_id) && $negocio_id==$negocio['id']) ? 'active':'' ?>">
          
          <a role="button" href="#menu-<?= $negocio['id'] ?>" aria-controls="menu-<?= $negocio['id'] ?>" data-toggle="collapse" class="<?php echo (!isset($negocio_id) || $negocio_id!=$negocio['id']) ? 'collapsed':'' ?>">
            <b class="<?php echo ($negocio['admin_habilitado']==0) ? 'reg-deshabilitado':''; ?>"><?= $negocio['nombre'] ?></b> <span class="fa arrow"></span>
          </a>
          <ul id="menu-<?= $negocio['id'] ?>" class="nav navbar-nav collapse <?php echo (isset($negocio_id) && $negocio_id==$negocio['id']) ? 'in':'' ?>">
            <li class="">
              <?= 
              $this->Html->link(__('<span class="fa fa-share"></span> Ver'), [
                'prefix'=>false,
                'controller'=>'N',
                'action'=>'index',
                $negocio['sucursales'][0]['id'], $negocio['nombre_slug']
              ], [
                'escape'=>false,
                'title'=>__('Ver el perfil de este negocio')
              ]);
              ?>                  
            </li>
            <li class="">
              <?= 
              $this->Html->link(__('<span class="fa fa-pencil"></span> Editar'), [
                'prefix'=>false,
                'controller'=>'Negocios',
                'action'=>'editar',
                $negocio['id'],
                $tokenFalso
              ], [
                'escape'=>false,
                'title'=>__('Editar la informaci&oacute;n de este negocio')
              ]);
              ?>                  
            </li>
            <?php if(isset($perRol['crear_sucursales']) && $perRol['crear_sucursales']==1): ?>
            <li>
              <?= 
              $this->Html->link(__('<span class="fa fa-building"></span> Sucursales'), [
                'prefix'=>false,
                'controller'=>'Sucursales',
                'action'=>'index',
                $negocio['id'],
                $config['negocios_opcion_defecto'],
                $tokenFalso
              ], [
                'escape'=>false,       
                'title'=>__('Editar las sucursales de este negocio')
              ]);
              ?> 
            </li>  
            <?php endif; ?>
          </ul>          
        </li>
        <?php endforeach; ?>
        
<!--        <li class="divider-menu"></li>
        <li>          
          <?= $this->Html->link(__('Ayuda'), '#') ?>
        </li>-->
      </ul>
    </div>
  </div>
  
  <?php if($mostrarPublicidad==1): ?>
  <div id="ad-menu-perfil" class="text-center">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Adaptable -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-3436124210477611"
         data-ad-slot="8091302082"
         data-ad-format="auto"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
  </div>
  <?php endif; ?>  
</div>