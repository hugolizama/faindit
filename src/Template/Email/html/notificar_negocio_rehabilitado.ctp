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

<p><?php echo __('Le informamos que su negocio <a href="'.$url.'">'.$negocio_nombre.'</a> ha sido revisado y nuevamente
  habilitado para publicaci&oacute;n.'); ?></p>

<p>
  <?php echo __('Agradecemos su colaboraci&oacute;n en esta situaci&oacute;n y le extendemos las disculpas por cualquier
    inconveniente.'); ?>
</p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
