<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Ajustes de correo') ?></h3>  
    </div>
  </div>
  
  <?= $this->Form->create($configsEntity, ['class'=>'form-horizontal', 'autocomplete'=>'off']); ?>
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Modo de encriptaci&oacute;n SMTP') ?>  
      <div class="label-descripcion">
        <?= __('Encriptaci&oacute;n necesaria para comunicarse con el servidor SMTP.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->select('correo_smtp_encript', [
        '' => 'Sin encriptaci&oacute;n',
        'ssl://' => 'SSL',
        'tls://' => 'TLS'
      ],[
        'id'=>'encript',
        'escape'=>false,
        'class'=>'form-control',
        'default'=> (isset($config['correo_smtp_encript'])) ? $config['correo_smtp_encript'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Servidor SMTP') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('El servidor SMTP que se usar&aacute; para enviar los correos a trav&eacute;s de &eacute;l') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('correo_smtp_servidor',[    
        'id'=>'servidor',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['correo_smtp_servidor'])) ? $config['correo_smtp_servidor'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Puerto SMTP') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Puerto que utiliza el servidor SMTP para enviar correos.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('correo_smtp_puerto',[
        'id'=>'puerto',
        'type'=>'number',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['correo_smtp_puerto'])) ? $config['correo_smtp_puerto'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Usuario SMTP') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Nombre de usuario para autentificarse con el servidor SMTP.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('correo_smtp_usuario',[
        'id'=>'usuario',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['correo_smtp_usuario'])) ? $config['correo_smtp_usuario'] : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="form-group form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Contrase&ntilde;a SMTP') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Contrase&ntilde;a para autentificarse con el servidor SMTP.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('correo_smtp_contrasena',[
        'id'=>'contrasena',
        'type'=>'password',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false,
        'value' => (isset($config['correo_smtp_contrasena'])) ? base64_decode($config['correo_smtp_contrasena']) : '',
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="row">
    <div class="col-xs-12 text-center">
      <?= $this->Form->submit(__('Guardar ajustes'), array(
        'class' => 'btn btn-primary btn-margin-right',
        'div' => false,          
      )); ?>
    </div>
  </div>
  <?= $this->Form->end(); ?>
  
  
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Realizar prueba de correo') ?></h3>  
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12">
      <?= __('En esta secci&oacute;n puede probar la configuraci&oacute;n de correo antes de guardarla.') ?>
    </div>
  </div>
  
  <div class="row form-group-sm">
    <label class="control-label-left control-label-desc col-xs-12 col-md-6 col-lg-5">
      <?= __('Direcci&oacute;n de correo:') ?><span class="required"></span>
      <div class="label-descripcion">
        <?= __('Correo electr&oacute;nico al cual enviar la prueba.') ?>
      </div>
    </label>
    <div class="col-xs-12 col-md-6 col-lg-4">
      <?= $this->Form->input('correo_prueba',[
        'type'=>'email',
        'id'=>'correo_prueba',
        'class'=>'form-control',
        'label'=>false,
        'div'=>false        
      ]); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  <div class="row">
    <div id="correo_mensaje" class="col-xs-12 text-danger">
      
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12 text-center">
      <?= $this->Form->submit(__('Enviar correo de prueba'), array(
        'id'=>'enviarCorreoPrueba',
        'class' => 'btn btn-primary btn-margin-right',
        'div' => false,          
      )); ?>
    </div>
  </div>
  <hr class="form-group-hr"/>
  
  
  
</div>

<script>
  $(document).ready(function(){
    $('#enviarCorreoPrueba').click(function(){
      $('#correo_mensaje').html('');
      var correo = $('#correo_prueba').val();
      var encript = $('#encript').val();
      var servidor = $('#servidor').val();
      var puerto = +$('#puerto').val();
      var usuario = $('#usuario').val();
      var contrasena = $('#contrasena').val();
      
      $.ajax({
        type: 'post',
        data: {correo:correo, configs:{encript:encript, servidor:servidor, puerto:puerto, usuario:usuario, contrasena:contrasena}},
        dataType: 'json',
        url: '<?= \Cake\Routing\Router::url(['prefix'=>'admin', 'controller'=>'Configs', 'action'=>'correoPrueba']); ?>'
      }).done(function(data){
        
        var color = 'text-danger';
        
        if(+data.codigo === 1){
          color = 'text-success';
        }
        
        $('#correo_mensaje').html('<div class="' + color + '">' + data.mensaje + '</div>');
        
      });
    });
  });
</script>