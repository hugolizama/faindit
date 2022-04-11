<?= $this->element('correo_header'); ?>

<?php
$url = \Cake\View\Helper\UrlHelper::build([
    'prefix' =>false,
    'controller'=>'negocios',
    'action'=>'editar',   
    $negocio_id,
    $token
  ],['full'=>true]);

?>
<p><?php echo __('Saludos ').$usuario. '.'; ?></p>

<p><?php echo __('Es nuestro deber informarle que su negocio <a href="'.$url.'">'.$negocio_nombre.'</a> ha sido suspendido de 
  publicaci&oacute;n por la siguiente raz&oacute;n:'); ?></p>

<p>----------</p>

<p>
  <?php 
  echo nl2br($razon_deshabilitado);
  ?>
</p>

<p>----------</p>

<p>
  <?php echo __('Para volver a publicarlo ingrese a su cuenta, realice las modificaciones necesarias y una vez guardadas
    las revisaremos en el menor tiempo posible.'); ?>
</p>

<p>
  <?php 
  echo __('Si considera que esto puede ser una equivocaci&oacute;n puede responder este correo para hac&eacute;rnoslo 
    saber.') ?>
</p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
