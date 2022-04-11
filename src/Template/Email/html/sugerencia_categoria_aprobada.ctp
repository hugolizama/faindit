<?= $this->element('correo_header'); ?>

<p><?= __('Hola '.$nombrePara.'.'); ?></p>

<p><?= __('Hemos recibido tu sugerencia de incluir una categor&iacute;a a '.$sitio_nombre.', y estamos complacidos de
  notificarle que ha sido agregada a nuestro sistema.'); ?></p>

<p><?= __('La categor&iacute;a que ha sido agregada a partir de su sugerencia es: <b>'.$categoria.'</b>.') ?></p>

<p><?= __('Puede iniciar sesi&oacute;n ahora mismo en '.'<a href="http://'.$sitio_nombre_secundario.'">'.$sitio_nombre_secundario.'</a> 
  y asignarla a su negocio.')  ?></p>


<p><?= __('Agradecemos el tiempo que ha tomado en ayudarnos a mejorar nuestro servicio.') ?></p>

<p>
  <hr/>
  <?= __('Atentamente') ?><br/>
  <?= $sitio_nombre ?>
</p>
