<?= $this->element('correo_header'); ?>

<p><?php echo __('Mensaje enviado desde formulario de comentarios '); ?></p>

<p><?= __('Nombre: ').$nombreDe; ?></p>
<p><?= __('Correo: ').$correoDe; ?></p>
<p><?= __('Mensaje: ').'<br/>'.$mensaje; ?></p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $nombreDe ?>
</p>
