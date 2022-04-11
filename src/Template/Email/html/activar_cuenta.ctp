<?= $this->element('correo_header'); ?>

<p><?php echo __('Gracias por registrarte en ').'<a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a>'; ?></p>

<p><?php echo __('Para completar su registro haga clic en el siguiente enlace o copie y p&eacute;guelo en la barra de 
  direcciones de su navegador:') ?></p>

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
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
