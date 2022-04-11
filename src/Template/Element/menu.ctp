<?php
$menuUsuario = 0;
$menuRegistro = 1;
if(isset($cookieUsuario)){
  $menuUsuario = 1;
  $menuRegistro = 0;
}
?>
<!-- Menu general del sitio -->
<nav class="nav navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" style="float: left;" data-toggle="collapse" data-target="#nav-main">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>      
      <?= $this->Html->link($config['sitio_nombre']." El Salvador", [
        'prefix'=>false, 'controller'=>'Principal', 'action'=>'index'
      ], [
        'id'=>'menu-principal-titulo',
        'style'=>'float: right',
        'class'=>'navbar-brand visible-xs'
      ]) ?>
    </div>    
    <div class="collapse navbar-collapse" id="nav-main">
      <ul class="nav navbar-nav">
        <li class="active">          
          <?=
          $this->Html->link(__('Inicio'),[
            'prefix'=>false,
            'controller'=>'Principal',
            'action'=>'index'
          ],['escape'=>false]);
          ?>
        </li> 
      </ul>

      <?php if(isset($mostrar_buscador_en_navbar)): ?>
      <?= $this->Form->create('search', array(
        'class' => 'navbar-form navbar-left buscador-menu', 'url'=>array(
          'controller'=>'Principal',
          'action'=>'buscarEncode'
        ),'type'=>'get', 'autocomplete'=>'off'
      )); ?> 
        <div class="form-group">
          <?php
          echo $this->Form->input('que', [
            'id' => 'txtQue',
            'label' => false,
            'div' => false,
            'class' => 'form-control',
            'placeholder' => __('Busca. Ej: Abogados'),
            'required'=>true, /*necesario*/
            'value'=> $this->request->session()->check('sessionQue') ? $this->request->session()->read('sessionQue') : ''
          ]);

          echo $this->Form->input('tq', [
            'type'=>'hidden',
            'id' => 'tq',
            'value'=>$tq
          ]);
          ?>
        </div>
        <div class="form-group">
          <?php
          echo $this->Form->input('en', [
            'id' => 'txtEn',
            'label' => false,
            'div' => false,
            'class' => 'form-control',
            'placeholder' => __('Donde. Ej: San Salvador'),
            'value'=> $this->request->session()->check('sessionEn') ? $this->request->session()->read('sessionEn') : ''
          ]);

          echo $this->Form->input('te', [
            'type'=>'hidden',
            'id' => 'te',
            'value'=>$te
          ]);
          ?> 
        </div>
        <?php        
        echo $this->Form->button('<span class=\'fa fa-search\'></span>', [
          'type'=>'submit',
          'title'=>$config['sitio_nombre'],
          'id'=>'btnBuscarIndex',
          'class' => 'btn btn-sm btn-info',
          'escape'=>false
        ]);
        ?>
      <?= $this->Form->end(); ?>
      <?php endif; ?>
      
      <ul class="nav navbar-nav navbar-right">
        <?php if($menuRegistro == 1): ?>
        <li>
          <?= $this->Html->link(__('Iniciar Sesi&oacute;n'), [
            'prefix'=>false,
            'controller'=>'Usuarios',
            'action'=>'login'            
          ],['escape'=>false]); ?>
        </li>
        
        <?php if(isset($config['usuarios_deshabilitar_registros']) && $config['usuarios_deshabilitar_registros']==0): ?>
        <li>
          <p class="navbar-btn">                
            <?= $this->Html->link(__('Registro'), [
              'prefix'=>false,
              'controller'=>'Usuarios',
              'action'=>'registro'
            ], ['class' => 'btn btn-primary']); ?>
          </p>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        
        <?php if($menuUsuario==1): ?>
        
        <?php if(1==0): /*ocultar este menu hasta asignar funcionalidad*/ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <span class="fa fa-bell" title="<?= __('Notificaciones') ?>">
              <span class="badge" style="background-color: red; margin-top: -3px;">4</span>                    
            </span>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#"><span class="fa fa-envelope"></span> Message from...</a></li>
            <li><a href="#"><span class="fa fa-comment"></span> Comment</a></li>
            <li><a href="#"><span class="fa fa-bookmark"></span> Favorite</a></li>
            <li><a href="#"><span class="fa fa-money"></span> Bought/Donation</a></li>
            <li><a href="#"><span class="fa fa-twitter"></span> Follow</a></li>
            <li class="divider"></li>
            <li><a href="#" class="text-center"><strong>See all</strong></a></li>
          </ul>
        </li>
        <?php endif; ?>
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= $cookieUsuario['usuario']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php if(isset($perRol['acceso_admin']) && $perRol['acceso_admin']==1): ?>
            <li><?= $this->Html->link(__('Administrador'), ['prefix'=>'admin', 'controller'=>'general','action'=>'index']) ?></li>
            <?php endif; ?>
            
            <li><?= $this->Html->link(__('Perfil'), ['prefix'=>false,'controller'=>'usuarios','action'=>'perfil']) ?></li>
            
            <?php if(isset($perRol['cambiar_contrasena']) && $perRol['cambiar_contrasena']==1): ?>
            <li><?= $this->Html->link(__('Cambiar contraseña'), ['prefix'=>false,'controller'=>'usuarios','action'=>'perfil#cambiar-contrasena']) ?></li>
            <?php endif; ?>
            
            <li class="divider"></li>
            <li><?= $this->Html->link(__('Cerrar sesi&oacute;n'), ['prefix'=>false,'controller'=>'usuarios','action'=>'logout'],['escape'=>false]) ?></li>            
          </ul>
        </li>
        <?php endif; ?>
        
      </ul>
    </div>
  </div>
</nav>