<div class="container-fluid" style="margin-top: 20px;">
  <div class="container container-min-height">
    <div class="row">
      <div class="col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?= __('Login Administrador') ?> - <?= $config['sitio_nombre']; ?></h3>
          </div>
          <div class="panel-body">
            <?= $this->Flash->render('error'); ?>
            <?php echo $this->Form->create($usuario); ?>

            <div class="form-group">
              <?=
              $this->Form->input('usuario', array(
                'id' => 'usuario',
                'div' => false,
                'label' => false,
                'class' => 'form-control',
                'placeholder' => 'Usuario o correo electr&oacute;nico',
                'autofocus'=>'autofocus',
                'escape'=>false
              ));
              ?>
            </div>
            <div class="form-group">
              <?=
              $this->Form->input('contrasena', array(
                'id' => 'contrasena',
                'div' => false,
                'label' => false,
                'class' => 'form-control',
                'placeholder' => 'Contrasena',
                'type' => 'password',
                'value' => ''
              ));
              ?>
            </div>		
            <div class="form-group text-uppercase text-center text-danger">
            <?php echo (isset($error)) ? $error : ''; ?>
            </div>
            <?=
            $this->Form->submit('Iniciar sesi&oacute;n', array(
              'escape' => false,
              'class' => 'btn btn-lg btn-success btn-block'
            ));
            ?>
<?php echo $this->Form->end(); ?>
          </div>	
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function () {    
    $('#usuario').val('');
    $('#contrasena').val('');
  });
</script>
