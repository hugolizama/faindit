<?= $this->element('correo_header'); ?>

<p><?php echo __('Este mensaje ha sido enviado desde el formulario de contacto'); ?></p>

<p><?= __('Nombre: ').$nombreDe; ?></p>
<p><?= __('Correo: ').$correoDe; ?></p>
<p><?= __('Mensaje: ').'<br/>'.$mensaje; ?></p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $nombreDe ?>
</p>
