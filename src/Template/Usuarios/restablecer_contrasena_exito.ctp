<div class="container-fluid">
  <div class="container container-min-height">
    <div class="row">
      <div id="registro_exito_contenido" class="col-xs-12 text-center">
        <div class="registro_exito"><?= __('Correo para restablecer contrase&ntilde;a ha sido enviado') ?></div>
        <div>
          <?= __('En un momento recibir&aacute;s un correo electr&oacute;nico con instrucciones para que puedas recuperar el acceso a tu cuenta nuevamente.') ?>
        </div>
        <div style="margin-bottom: 10px;">
          <?= __('(Aseg&uacute;rate de revisar tambi&eacute;n tu carpeta de spam.)') ?>
        </div>
        <div>
          <?php
          echo __('Si no recibes la notificaci&oacute;n puedes solicitar otro en ');
          echo $this->Html->link(__('Restablecer mi contrase&ntilde;a '), [
            'prefix' => false,
            'controller' => 'Usuarios',
            'action' => 'restablecerContrasena'
            ], [
            'escape' => false
          ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>