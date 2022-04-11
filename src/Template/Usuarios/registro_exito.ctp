<div class="container-fluid">
  <div class="container container-min-height">
    <div class="row">
      <div id="registro_exito_contenido" class="col-xs-12 text-center">
        <div class="registro_exito"><?= __('Tus datos han sido registrado con &eacute;xito.') ?></div>
        <div>
          <?= __('En un momento recibir&aacute;s un correo electr&oacute;nico para que puedas activar tu cuenta y empezar a utilizar nuestros servicios.') ?>
        </div>
        <div style="margin-bottom: 10px;">
          <?= __('(Aseg&uacute;rate de revisar tambi&eacute;n tu carpeta de spam.)') ?>
        </div>
        <div>
          <?php
          echo __('Si no recibes la notificaci&oacute;n puedes solicitar el ');
          echo $this->Html->link(__('reenv&iacute;o activaci&oacute;n de cuenta '), [
            'prefix' => false,
            'controller' => 'Usuarios',
            'action' => 'reenviarActivacion'
            ], [
            'escape' => false
          ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  fbq('track', 'CompleteRegistration');
</script>