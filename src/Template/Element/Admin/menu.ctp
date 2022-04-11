<!-- Menu general del administrador -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>    
    <?= $this->Html->link('ADM Faindit',[
      'prefix'=>'admin',
      'controller'=>'general',
      'action'=>'index'
    ],[
      'escape'=>false,
      'title'=>__('Administraci&oacute;n Faindit'),
      'class'=>'navbar-brand'
    ]); ?>
    
    <div id="menu-xs" class="hidden visible-xs visible-sm">
      <div class="dropdown">
        <a class="dropdown-toggle navbar-brand" data-toggle="dropdown" href="#">
          <i class="fa fa-envelope fa-fw"></i><i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-messages">
          <li>
            <a href="#">
              <div>
                <strong>John Smith</strong>
                <span class="pull-right text-muted">
                  <em>Yesterday</em>
                </span>
              </div>
              <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <strong>John Smith</strong>
                <span class="pull-right text-muted">
                  <em>Yesterday</em>
                </span>
              </div>
              <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <strong>John Smith</strong>
                <span class="pull-right text-muted">
                  <em>Yesterday</em>
                </span>
              </div>
              <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a class="text-center" href="#">
              <strong>Read All Messages</strong>
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>
      </div>
      
      <div class="dropdown">
        <a class="dropdown-toggle navbar-brand" data-toggle="dropdown" href="#">
          <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
          <li>
            <?= $this->Html->link('<span class="fa fa-arrow-right fa-fw"></span> '.$config['sitio_nombre_secundario'].' <span class="fa fa-external-link"></span>', [
              'prefix'=>false,
              'controller'=>'Principal',
              'action'=>'index'
            ],[
              'escape'=>false,
              'target'=>'_blank'
            ]); ?>
          </li>
          <li>
            <?= $this->Html->link(__('<i class="fa fa-pencil fa-fw"></i> Perfil - <b>').$cookieUsuarioAdmin['usuario'].'</b>', [
              'prefix'=>false, 'controller'=>'Usuarios', 'action'=>'perfil'
            ], ['escape'=>false, 'target'=>'_blank']) ?>
          </li>
          <li class="divider"></li>
          <li>
            <?= $this->Html->link(__('<i class="fa fa-sign-out fa-fw"></i> Cerrar sesi&oacute;n'), [
              'prefix'=>'admin', 'controller'=>'Usuarios', 'action'=>'logout'
            ], ['escape'=>false]) ?>
          </li>
        </ul>
      </div>
      
    </div>
  </div>
  <!-- /.navbar-header -->

  <div id="menu-collapse" class="collapse navbar-collapse" role="navigation">
    <ul class="nav navbar-top-links navbar-right">
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fa fa-envelope fa-fw"></i><i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-messages">
          <li>
            <a href="#">
              <div>
                <strong>John Smith</strong>
                <span class="pull-right text-muted">
                  <em>Yesterday</em>
                </span>
              </div>
              <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <strong>John Smith</strong>
                <span class="pull-right text-muted">
                  <em>Yesterday</em>
                </span>
              </div>
              <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <strong>John Smith</strong>
                <span class="pull-right text-muted">
                  <em>Yesterday</em>
                </span>
              </div>
              <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a class="text-center" href="#">
              <strong>Read All Messages</strong>
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>
        <!-- /.dropdown-messages -->
      </li>
      <!-- /.dropdown -->
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fa fa-tasks fa-fw"></i><i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-tasks">
          <li>
            <a href="#">
              <div>
                <p>
                  <strong>Task 1</strong>
                  <span class="pull-right text-muted">40% Complete</span>
                </p>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                    <span class="sr-only">40% Complete (success)</span>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <p>
                  <strong>Task 2</strong>
                  <span class="pull-right text-muted">20% Complete</span>
                </p>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                    <span class="sr-only">20% Complete</span>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <p>
                  <strong>Task 3</strong>
                  <span class="pull-right text-muted">60% Complete</span>
                </p>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                    <span class="sr-only">60% Complete (warning)</span>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <p>
                  <strong>Task 4</strong>
                  <span class="pull-right text-muted">80% Complete</span>
                </p>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                    <span class="sr-only">80% Complete (danger)</span>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a class="text-center" href="#">
              <strong>See All Tasks</strong>
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>
        <!-- /.dropdown-tasks -->
      </li>
      <!-- /.dropdown -->
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-alerts">
          <li>
            <a href="#">
              <div>
                <i class="fa fa-comment fa-fw"></i> New Comment
                <span class="pull-right text-muted small">4 minutes ago</span>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                <span class="pull-right text-muted small">12 minutes ago</span>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <i class="fa fa-envelope fa-fw"></i> Message Sent
                <span class="pull-right text-muted small">4 minutes ago</span>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <i class="fa fa-tasks fa-fw"></i> New Task
                <span class="pull-right text-muted small">4 minutes ago</span>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a href="#">
              <div>
                <i class="fa fa-upload fa-fw"></i> Server Rebooted
                <span class="pull-right text-muted small">4 minutes ago</span>
              </div>
            </a>
          </li>
          <li class="divider"></li>
          <li>
            <a class="text-center" href="#">
              <strong>See All Alerts</strong>
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>
        <!-- /.dropdown-alerts -->
      </li>
      <!-- /.dropdown -->
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
          <li>
            <?= $this->Html->link('<span class="fa fa-arrow-right fa-fw"></span> '.$config['sitio_nombre_secundario'].' <span class="fa fa-external-link"></span>', [
              'prefix'=>false,
              'controller'=>'principal',
              'action'=>'index'
            ],[
              'escape'=>false,
              'target'=>'_blank'
            ]); ?>
          </li>
          <li>
            <?= $this->Html->link(__('<i class="fa fa-pencil fa-fw"></i> Perfil - <b>').$cookieUsuarioAdmin['usuario'].'</b>', [
              'prefix'=>false, 'controller'=>'Usuarios', 'action'=>'perfil'
            ], ['escape'=>false, 'target'=>'_blank']) ?>
          </li>
          <li class="divider"></li>
          <li>
            <?= $this->Html->link(__('<i class="fa fa-sign-out fa-fw"></i> Cerrar sesi&oacute;n'), [
              'prefix'=>'admin', 'controller'=>'Usuarios', 'action'=>'logout'
            ], ['escape'=>false]) ?>
          </li>
        </ul>
        <!-- /.dropdown-user -->
      </li>
      <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->
    <ul class="nav navbar-top-menu navbar-nav">
      <li class="<?php echo (isset($general_activo)) ? 'active':''; ?>">
        <?= $this->Html->link(__('<span class="fa fa-home"></span> General'),[
          'prefix'=>'admin',
          'controller'=>'general',
          'action'=>'index'
        ],[
          'escape'=>false
        ]); ?>
      </li>
      
      <?php if(isset($perRol['ver_panel_config']) && $perRol['ver_panel_config']==1): ?>
      <li class="<?php echo (isset($configuracion_activo)) ? 'active':''; ?>">
        <?= $this->Html->link(__('<span class="fa fa-wrench"></span> Configuraci&oacute;n'),[
          'prefix'=>'admin',
          'controller'=>'configs',
          'action'=>'index'
        ],[
          'escape'=>false
        ]); ?>
      </li>
      <?php endif; ?>
      
      <?php if(isset($perRol['ver_panel_neg_sucursales']) && $perRol['ver_panel_neg_sucursales']==1): ?>
      <li class="<?php echo (isset($negocios_activo)) ? 'active':''; ?>">
        <?= $this->Html->link(__('<span class="fa fa-building"></span> Negocios y sucursales'),[
          'prefix'=>'admin',
          'controller'=>'NegociosSucursales',
          'action'=>'index'
        ],[
          'escape'=>false
        ]); ?>
      </li>
      <?php endif; ?>
      
      <?php if(isset($perRol['ver_panel_usua_roles']) && $perRol['ver_panel_usua_roles']==1): ?>
      <li class="<?php echo (isset($usuarios_activo)) ? 'active':''; ?>">
        <?= $this->Html->link(__('<span class="fa fa-users"></span> Usuarios y roles'),[
          'prefix'=>'admin',
          'controller'=>'usuarios',
          'action'=>'index'
        ],[
          'escape'=>false
        ]); ?>
      </li>    
      <?php endif; ?>
      
      <li class="<?php echo (isset($mantenimiento_activo)) ? 'active':''; ?>">
        <?= $this->Html->link(__('<span class="fa fa-server"></span> Mantenimiento'),[
          'prefix'=>'admin',
          'controller'=>'usuarios',
          'action'=>'index'
        ],[
          'escape'=>false
        ]); ?>
      </li>
    </ul>
  </div>
  
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav" id="side-menu">        
        <li class="sidebar-search">
          <?= __('Conectado como: ') ?><b><?= $cookieUsuarioAdmin['usuario'] ?></b>
<!--          <div class="input-group custom-search-form">
            <input type="text" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">
                <i class="fa fa-search"></i>
              </button>
            </span>
          </div>          -->
        </li>
        <?php echo $this->element('Admin/'.$menu_admin); ?>
      </ul>
    </div>
    <!-- /.sidebar-collapse -->
  </div>
  <!-- /.navbar-static-side -->
</nav>