<?= $this->element('correo_header'); ?>

<?php
$url = \Cake\View\Helper\UrlHelper::build([
      'prefix'=>false,
      'controller'=>'usuarios',
      'action'=>'activarCuenta',
      $usuario_id,
      $token
    ],['full'=>true]);
?>
<p><?php echo __('Saludos ').$usuario; ?></p>

<p><?php echo __('Gracias por registrarse en ').'<a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a>'; ?></p>

<p>
  <?php 
  echo __('Le recordamos que a&uacute;n no ha activado su cuenta de usuario en el sitio web. Solo debe ingresar a la 
  siguiente direcci&oacute;n web para confirmarla:')
  ?>
</p>

<p>
  <?php echo $this->Html->link($url,$url,['escape'=>false]); ?>
</p>

<p>
  <?php 
  echo __('Con este paso ya podr&aacute; ingresar y registrar la informaci&oacute;n de su negocio.') ?>
</p>

<p>
  <?php 
  echo __('<b>NOTA:</b> Las cuentas no activadas de m&aacute;s de tres meses ser&aacute;n liberadas del sistema.') ?>
</p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
