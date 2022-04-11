<?= $this->element('correo_header'); ?>

<?php
$url = \Cake\View\Helper\UrlHelper::build([
    'prefix' =>false,
    'controller'=>'negocios',
    'action'=>'agregar',      
  ],['full'=>true]);
?>
<p><?php echo __('Saludos!!'); ?></p>

<p><?php echo __('Gracias por registrarse en ').'<a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a>'; ?></p>

<p>
  <?php 
  echo __('Le recordamos que a&uacute;n no ha registrado su negocio en el sitio web. Solo debe ingresar a la 
  siguiente direcci&oacute;n web:')
  ?>
</p>

<p>
  <?php echo $this->Html->link($url,$url,['escape'=>false]); ?>
</p>

<p>
  <?php 
  echo __('Y completar la informaci&oacute;n de su negocio o servicio para publicarlo y dejar que posibles
  clientes le encuentren.') ?>
</p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
