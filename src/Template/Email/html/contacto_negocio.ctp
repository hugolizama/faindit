<?= $this->element('correo_header'); ?>

<p><?php echo __('Este mensaje de contacto ha sido enviado desde ').'<a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a>'; ?></p>

<p><?= __('Nombre: ').$nombreDe; ?></p>
<p><?= __('Correo: ').$correoDe; ?></p>
<p><?= __('Mensaje: ').'<br/>'.$mensaje; ?></p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
