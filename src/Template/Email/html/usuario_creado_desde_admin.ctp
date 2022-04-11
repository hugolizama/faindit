<?= $this->element('correo_header'); ?>

<p><?php echo __('Un usuario ha sido creado para usted en ').'<a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a>'; ?></p>

<?php if($estado == 0): /* texto en caso de NO haber activado el usuario*/ ?>
<p><?php echo __('Debe hacer clic en el siguiente enlace o copiarlo y pegarlo en su 
  navegador para verificar su cuenta de correo electr&oacute;nico:') ?></p>

<?php
$url = \Cake\View\Helper\UrlHelper::build([
      'prefix'=>false,
      'controller'=>'Usuarios',
      'action'=>'activarCuenta',
      $usuario_id,
      $token
    ],['full'=>true]);
?>

<p>
  <?php  
  echo $this->Html->link($url,$url,['escape'=>false]);
  ?>
</p>

<p>
  <?php
  echo __('Una vez verificado podr&aacute; ingresar con la siguiente informaci&oacute;n:');
  ?>
</p>

<?php else: /*texto en caso de SI haber activado el usuario*/ ?>

<p>
  <?php
  echo __('Para ingresar haga clic en el siguiente enlace o copie y p&eacute;guelo en su navegador; y utilice 
    la informaci&oacute;n detallada para iniciar sesi&oacute;n:');
  ?>
</p>

<?php
$url = \Cake\View\Helper\UrlHelper::build([
      'prefix'=>false,
      'controller'=>'Usuarios',
      'action'=>'login'
    ],['full'=>true]);
?>

<p>
  <?php  
  echo $this->Html->link($url,$url,['escape'=>false]);
  ?>
</p>

<?php endif; /*FIN*/ ?>

<p>
  <?php
  echo __('<b>Usuario: </b>'). $usuario;
  ?>
</p>

<p>
  <?php
  echo __('<b>Correo electr&oacute;nico: </b>'). $correo;
  ?>
</p>

<p>
  <?php
  echo __('<b>Contrase&ntilde;a inicial: </b>'). $contrasena;
  ?>
</p>

<p>
  <?php
  echo __('Se recomienda cambiar esta contrase&ntilde;a una vez que inicie sesi&oacute;n.');
  ?>
</p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
