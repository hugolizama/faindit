<?= $this->element('correo_header'); ?>

<p><?php echo __('Este es un correo para realizar pruebas desde 
  <a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a>'); ?></p>

<p><?= __('Esta solicitud se gener&oacute; desde el navegador: ') ?><b><?= $ops['navegador'] ?> (<?= $ops['ip'] ?>)</b></p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
