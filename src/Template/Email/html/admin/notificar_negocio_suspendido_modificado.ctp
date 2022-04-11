<?= $this->element('correo_header'); ?>

<?php
$url = \Cake\View\Helper\UrlHelper::build([
    'prefix' =>'admin',
    'controller'=>'NegociosSucursales',
    'action'=>'editarNegocio',   
    $negocio_id
  ],['full'=>true]);

?>
<p><?php echo __('Un negocio deshabilitado ha sido modificado por su usuario.'); ?></p>

<p><?php echo __('NEGOCIO: <a href="'.$url.'">'.$negocio_nombre.'</a>. Raz&oacute;n por haber sido deshabilitado:'); ?></p>

<p>----------</p>

<p>
  <?php 
  echo nl2br($razon_deshabilitado);
  ?>
</p>

<p>----------</p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
