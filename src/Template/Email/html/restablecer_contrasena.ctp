<?= $this->element('correo_header'); ?>

<p><?php echo __('Se ha generado una solicitud para restablecer su contrase&ntilde;a en ').
  '<a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a>'; ?></p>

<p><?php echo __('Para hacerlo haga clic en el siguiente enlace o copie y p&eacute;guelo 
  en la barra de direcciones de su navegador:') ?></p>

<?php
$url = \Cake\View\Helper\UrlHelper::build([
      'prefix'=>false,
      'controller'=>'Usuarios',
      'action'=>'restableciendoContrasena',
      $usuario_id,
      $token
    ],['full'=>true]);
?>

<p>
  <?php  
  echo $this->Html->link($url,$url,['escape'=>false]);
  ?>
</p>

<p><?= __('Este enlace es v&aacute;lido por 24 horas.') ?></p>

<p><?= __('Esta solicitud se gener&oacute; desde el navegador: ') ?><b><?= $navegador ?> (<?= $ip ?>)</b></p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
