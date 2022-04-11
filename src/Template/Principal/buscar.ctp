<!--modal para envio de correo -->
<div class="modal fade" id="modalCorreo" tabindex="-1" role="dialog" aria-labelledby="modalCorreoLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalPara"></h4>
      </div>
      <div class="modal-body">         
        <div id='modalSpinner' style="position: absolute; margin-top: 10%; left: 0; right: 0; text-align: center; display: none;">
          <span class="fa fa-spinner fa-pulse fa-5x"></span>
        </div>
        <div id='modalError' class="form-group form-group-sm">          
        </div>
        <div class="form-group form-group-sm">
          <input type="hidden" id='modalSucursalId' />
          <label class="control-label"><?= __('Para:') ?></label>
          <input type="text" class="form-control" id="modalCorreoDireccion" disabled/>
        </div>
        <div class="form-group form-group-sm">
          <label class="control-label required"><?= __('De (nombre y apellido):') ?></label>
          <input type="text" class="form-control" id="modalDeNombre" />
        </div>
        <div class="form-group form-group-sm">
          <label class="control-label required"><?= __('Su correo electr&oacute;nico:') ?></label>
          <input type="email" class="form-control" id="modalDeCorreo" />
        </div>
        <div class="form-group form-group-sm">
          <label class="control-label required"><?= __('Mensaje:') ?></label>
          <textarea class="form-control" id="modalMensaje"></textarea>
        </div>
        
        <?php if(!isset($cookieUsuario)): ?>
        <div class="form-group form-group-sm">
          <div id='gCaptcha'></div>
        </div> 
        <?php endif; ?>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancelar') ?></button>
        <button type="button" class="btn btn-primary" id='modalEnviarMensaje'><?= __('Enviar mensaje') ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modalCorreoEnviado" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body"> 
        <?= __('Mensaje enviado con &eacute;xito') ?>
      </div>      
      <div class="modal-footer modal-sm">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Aceptar') ?></button>
      </div>
    </div>
  </div>
</div>

<!-- fin modal para envio de correo -->
    
<?= $this->Form->create(null, ['class' => 'form-horizontal', 'action'=>'buscarEncode', 'id' => 'frmBuscar', 'type' => 'get']); ?>
<div class="container-fluid">
  <div class="container buscador-2">
    <div style="margin-top: 10px;"></div>
    <div class="form-group">
      <div class="txt-buscador-2 col-sm-2 col-md-1 col-lg-1" style="padding-top: 10px;">
        <?= __('Buscador') ?>
      </div>
      
      <div class="col-sm-4 col-lg-3">
        <?php
        echo $this->Form->input('que', [
          'id' => 'txtQue',
          'label' => false,
          'div' => false,
          'class' => 'form-control',
          'placeholder' => __('Busca. Ej: Abogados'),
          'value' => $que,
          'required'=>true /*necesario*/
        ]);

        echo $this->Form->input('tq', [
            'type'=>'hidden',
            'id' => 'tq',
            'value'=>$tq
          ]);
        ?>
      </div>
      
      <div class="col-sm-4 col-lg-3">
        <?php
        echo $this->Form->input('en', [
          'id' => 'txtEn',
          'label' => false,
          'div' => false,
          'class' => 'form-control',
          'placeholder' => __('Donde. Ej: San Salvador'),
          'value' => $en
        ]);

        echo $this->Form->input('te', [
            'type'=>'hidden',
            'id' => 'te',
            'value'=>$te
          ]);
        ?> 
      </div>
      
      <div class="col-sm-2 col-lg-1">
        <?php
        echo $this->Form->submit($config['sitio_nombre'], [
          'id'=>'btnBuscarIndex',
          'class' => 'btn btn-info'
        ]);
        ?>
      </div>
    
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="container">
    <button type="button" id="button-toggle" class="btn btn-default visible-xs visible-sm">        
      <span class="fa fa-bars"></span>
    </button>
    <div></div>
    <div id="buscar-container"  class="sidebar-activo">
      <div id="buscar-sidebar">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?= __('Opciones') ?></h3>
          </div>
          <div id="menu-cuerpo" class="panel-body">
            <div class="form-group">
              <div><?= __('Ver') ?></div>
              <?php
              echo $this->Form->input('ver', [
                'id' => 'slVer',
                'type' => 'select',
                'class' => 'form-control input-sm form-control-block',
                'label' => false,
                'options' => $opciones_visibles,
                'default'=> $ver
              ]);
              ?>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
              <div><?= __('Orden') ?></div>
              <?php
              echo $this->Form->input('orden', [
                'id'=>'slOrden',
                'type' => 'select',
                'class' => 'form-control input-sm form-control-block',                
                'label' => false,
                'options' => $opciones_orden,
                'default'=>$orden
              ]);
              ?>
            </div>
          </div>
        </div>  

        <?php if($mostrarPublicidad==1): ?>
        <div id="ad-buscar-menu">
          <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
          <!-- Adaptable -->
          <ins class="adsbygoogle"
               style="display:block"
               data-ad-client="ca-pub-3436124210477611"
               data-ad-slot="8091302082"
               data-ad-format="auto"></ins>
          <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
        <?php endif; ?>
      </div>

      <div id="buscar-contenido">
        <nav>    
          <ul class="pagination pagination-buscador-top">
            <?php            
            /*primer pagina*/
            echo $this->Paginator->first('<<', array(
              'tag' => 'li',
              'disabledTag' => 'a'
            ));

            /*anterior*/
            echo $this->Paginator->prev('<', array(
              'tag' => 'li',
              'disabledTag' => 'a'), null, array(
              'tag' => 'li',
              'disabledTag' => 'a',
              'class' => 'prev disabled'
            ));

            /*numeros de paginas*/
            echo $this->Paginator->numbers(array(
              'separator' => '', 
              'tag' => 'li', 
              'currentClass' => 'active', 
              'currentTag' => 'a',
              'modulus'=>4
            ));

            /*siguiente*/
            echo $this->Paginator->next('>', array(
              'tag' => 'li',
              'disabledTag' => 'a'), null, array(
              'tag' => 'li',
              'disabledTag' => 'a',
              'class' => 'prev disabled'
            ));

            /*ultima*/
            echo $this->Paginator->last('>>', array(
              'tag' => 'li', 
              'disabledTag' => 'a'
            ));
            ?>
          </ul>
        </nav>
        
        <?php
        /*mensaje para busqueda sin resultados*/
        if(empty($resultado)){
          $texto_en = '';
          if($en!=''){
            $texto_en = "en <span style='font-weight: bold;'>$en</span>";
          }
          echo __("<h3>Coincidencias no encontradas para:<br/><span style='font-weight: bold;'>$que</span> $texto_en</h3>");
        }
        ?>
      
        <?php $i=0; ?>
        <?php foreach ($resultado as $item): ?>
        <?php
        $i+=1;
        $negocioUrl = Cake\Routing\Router::url([
          'controller'=>'N', 
          'action'=>'index', 
          $item['id'], 
          $item['_matchingData']['Negocios']['nombre_slug'],          
          'tq'=>$tq, 
          'en'=>$en, 
          'te'=>$te
        ], true);
        ?>
          <div class="panel panel-primary">
            <div class="panel-heading panel-popover">
              <a class="link-share" tabindex="0" role="button" data-toggle="popover" 
                 data-trigger="focus" data-placement="left" data-html="true"
                 data-content="
                 <a class='social-share' data-url='https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($negocioUrl) ?>' title='Facebook'><span class='fa fa-2x fa-facebook-square share-item'></span></a>
                 <a class='social-share' data-url='https://twitter.com/intent/tweet?text=<?= urlencode($item['_matchingData']['Negocios']['nombre']) ?>&url=<?= urlencode($negocioUrl) ?>&hashtags=<?= $config['sitio_nombre'] ?>ElSalvador' title='Twitter'><span class='fa fa-2x fa-twitter-square share-item'></span><a>
                 <a class='social-share' data-url='https://plus.google.com/share?url=<?= urlencode($negocioUrl) ?>' title='Google +'><span class='fa fa-2x fa-google-plus-square share-item'></span><a>
                 <?php if($this->request->is('mobile')): ?><a href='whatsapp://send?text=<?= urlencode($negocioUrl) ?>' data-action='share/whatsapp/share'><span class='fa fa-2x fa-whatsapp share-item'></span></a><?php endif; ?>">
                <span title="<?= __('Compartir') ?>" class="fa fa-share-alt-square fa-1-5x"></span>
              </a>
              <?= $this->Html->link('<h3 class="panel-title">'.$item['_matchingData']['Negocios']['nombre'].'</h3>', [
                'prefix'=>false,
                'controller'=>'N',
                'action'=>'index',
                $item['id'],
                $item['_matchingData']['Negocios']['nombre_slug'],
                'tq'=>$tq,
                'en'=>urlencode($en),
                'te'=>$te
              ],['escape'=>false]); ?>
              
            </div>
            <div class="panel-body">
              <div class="row result-row">
                <div class="col-sm-12">
                  <?php
                    $rutaImg='neg/'.$item['negocio_id'].'/logo.jpg';                    
                    if(!file_exists('img/'.$rutaImg)){
                      $rutaImg='no_logo.jpg';
                    }
                    
                    echo $this->Html->image($rutaImg, ['class'=>'result-img', 'alt'=>$item['_matchingData']['Negocios']['nombre']]);
                  ?>
                  
                  <div class="result-content">
                    <?= $this->Text->truncate($item['_matchingData']['Negocios']['descripcion'], 300) ?>
                    
                    <div class="result-address">
                      <span class="fa fa-map-marker"></span> 
                      <?php 
                      echo $item['_matchingData']['Departamentos']['nombre'].', '.$item['_matchingData']['Municipios']['nombre'].'. '.
                        $item['direccion']; 
                      ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <span class="result-contact">
                    <b><?= __('Contacto: ') ?></b> <br/>                       
                    <?php
                    $v=0;
                    $count_telefonos = count($item['telefonos']);
                    foreach($item['telefonos'] as $tel){
                      $v = $v + 1;
                      switch ($tel['tipo']){
                        case 1:
                          $tipo = 'Tel:';
                          break;
                        case 2:
                          $tipo = 'Fax:';
                          break;
                        default:
                          $tipo = 'Cel:';
                          break;
                      }
                      
                      echo $tipo.'('.$item['_matchingData']['Paises']['codigo_telefono'].') '.$tel['numero'];
                      
                      echo ' <div class="hidden-xs visible-sm-inline visible-md-inline visible-lg-inline">|</div> 
                            <div class="visible-xs"><!-- Separador --></div>';
                      
                      if($v>2){
                        break;
                      }
                    }
                    ?>           
                  </span>    
                  
                  <span class="result-social">
                    <?php if($item['_matchingData']['Negocios']['url']!=''): ?>
                    <a href="<?= $item['_matchingData']['Negocios']['url'] ?>" target="_blank"><span class="fa fa-globe fa-1-2x" title="Sitio web"></span></a>
                    <?php endif; ?>
                    
                    <?php 
                      /*facebook*/
                      if($item['facebook']!='' || $item['_matchingData']['Negocios']['facebook']!=''): 
                        $rutaFacebook = $item['facebook'];
                        if($rutaFacebook==''){
                          $rutaFacebook = $item['_matchingData']['Negocios']['facebook'];
                        }
                    ?>
                    <a href="<?= $config['negocios_url_facebook'].$rutaFacebook ?>" target="_blank"><span class="fa fa-facebook fa-1-2x" title="Facebook"></span></a>
                    <?php endif; ?>
                    
                    <?php 
                      /*twitter*/
                      if($item['twitter']!='' || $item['_matchingData']['Negocios']['twitter']!=''): 
                        $rutaTwitter = $item['twitter'];
                        if($rutaTwitter==''){
                          $rutaTwitter = $item['_matchingData']['Negocios']['twitter'];
                        }
                    ?>                    
                    <a href="<?= $config['negocios_url_twitter'].$rutaTwitter ?>" target="_blank"><span class="fa fa-twitter fa-1-2x" title="Twitter"></span></a> 
                    <?php endif; ?>
                    
                    <?php 
                      /*google plus*/
                      if($item['google_plus']!='' || $item['_matchingData']['Negocios']['google_plus']!=''): 
                        $rutaGooglePlus = $item['google_plus'];
                        if($rutaGooglePlus==''){
                          $rutaGooglePlus = $item['_matchingData']['Negocios']['google_plus'];
                        }
                    ?>                     
                    <a href="<?= $config['negocios_url_google_plus'].$rutaGooglePlus ?>" target="_blank"><span class="fa fa-google-plus fa-1-2x" title="Google +"></span></a>
                    <?php endif; ?>
                    
                    <?php 
                      /*instagram*/
                      if($item['instagram']!='' || $item['_matchingData']['Negocios']['instagram']!=''): 
                        $rutaInstagram = $item['instagram'];
                        if($rutaInstagram==''){
                          $rutaInstagram = $item['_matchingData']['Negocios']['instagram'];
                        }
                    ?> 
                    <a href="<?= $config['negocios_url_instagram'].$rutaInstagram ?>" target="_blank"><span class="fa fa-instagram fa-1-2x" title="Instagram"></span></a>
                    <?php endif; ?>
                    
                     <?php                      
                      /*correo*/
                      if($item['correo']!=''): 
                        $rutaCorreo = $item['correo'];                        
                    ?>                     
                    <a href="#" data-toggle="modal" data-sucursal='<?= $item['id'] ?>' data-target="#modalCorreo" data-correo="<?= $rutaCorreo ?>" data-para='<?= h($item['_matchingData']['Negocios']['nombre']) ?>'>
                      <span class="fa fa-envelope fa-1-2x" title="<?= __('Enviar correo a ').h($item['_matchingData']['Negocios']['nombre']) ?>"></span>
                    </a>
                    <?php endif; ?>
                  </span>
                </div>                
              </div>
            </div>
          </div>          
          <?php if($i==2 && $publicidadResultados==1): ?>
          <div id="ad-buscar-resultados">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Adaptable -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-3436124210477611"
                 data-ad-slot="8091302082"
                 data-ad-format="auto"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
          </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
    
  </div>
</div>
<?= $this->Form->end(); ?>
<script>  
  <?php if(!isset($cookieUsuario)): ?>
  var widgetId1;
  function generarCaptcha(){
    widgetId1 = grecaptcha.render('gCaptcha', {
      'sitekey' : '6LeI6Q8TAAAAAL_yOH82ttIyccRrIMwK7Ki784YV'
    });
  }
  <?php endif; ?>
  
  $(document).ready(function () {
    /*Toggle sidebar*/
    $('#button-toggle').click(function (e) {
      e.preventDefault();
      $(this).toggleClass('menu-show');
      $('#buscar-container').toggleClass('sidebar-activo');
    });


    $('#slVer').change(function () {
      $('#frmBuscar').submit();
    });
    
    $('#slOrden').change(function () {
      $('#frmBuscar').submit();
    });
    
    $(function () {
      $('[data-toggle="popover"]').popover();
    });
        
    
    $('#modalCorreo').on('show.bs.modal', function (event) {
      
      var button = $(event.relatedTarget); /*Button that triggered the modal*/
      var para = button.data('para'); /*Extract info from data-* attributes*/
      var correo = button.data('correo'); /*Extract info from data-* attributes*/
      var sucursal_id = button.data('sucursal'); /*Extract info from data-* attributes*/
      
      var modal = $(this);
      modal.find('#modalPara').text('<?= __('Mensaje para') ?> ' + para);
      modal.find('.modal-body #modalSucursalId').val(sucursal_id);
      modal.find('.modal-body #modalCorreoDireccion').val(correo);
      modal.find('.modal-body #modalMensaje').val('');   
    });
    
    $('#modalCorreo').on('shown.bs.modal', function(){
       $('#modalDeNombre').focus();
    });
    
    
    $('#modalEnviarMensaje').click(function(){
      /*mostrar spinner*/
      $('#modalSpinner').show();
      
      var cap;
      <?php if(!isset($cookieUsuario)): ?>
      cap = $('#g-recaptcha-response').val();      
      <?php else: ?>
      cap=-1; 
      <?php endif; ?>      
      
      var sucursal_id = $('#modalSucursalId').val();
      var correo = $('#modalCorreoDireccion').val();;
      var deNombre = $('#modalDeNombre').val();
      var deCorreo = $('#modalDeCorreo').val();
      var mensaje = $('#modalMensaje').val();
      
      $.ajax({
        type: 'POST',
        data: {captcha: cap, sucursal_id:sucursal_id, correo:correo, deNombre:deNombre, deCorreo:deCorreo, mensaje:mensaje},
        dataType: 'json',
        url: "<?= Cake\Routing\Router::url(['prefix'=>false, 'controller'=>'Principal', 'action'=>'enviarMensaje']) ?>"
      }).done(function(data){
        var obj = $.parseJSON(JSON.stringify(data));
        var cod = parseInt(obj.cod);
        var mensaje = obj.mensaje;
        var modalError = $('#modalError');
        
        switch(cod){
          case 0:
            $('#modalSpinner').hide();
            modalError.html(mensaje);
            <?php if(!isset($cookieUsuario)): ?>
            grecaptcha.reset(widgetId1); 
            <?php endif; ?>
            break;
            
          case 1:
            $('#modalCorreo').modal('hide');
            $('#modalCorreoEnviado').modal('show');
            break;
        }        
      });
    });
    
    
    $('#modalCorreo').on('hidden.bs.modal', function (event) {
      $('#modalSpinner').hide();
      $('#modalError').html('');
      <?php if(!isset($cookieUsuario)): ?>
      grecaptcha.reset(widgetId1); 
      <?php endif; ?>
      
    });
    
    /*abrir popup para compartir un elemento*/
    $(document).on('click', '.social-share', function(e){ 
      e.preventDefault();
      
      var url = $(this).data('url');
      window.open(url, "", "width=500, height=400");
    });
  });
</script>
<?php if(!isset($cookieUsuario)): ?>
<script src='https://www.google.com/recaptcha/api.js?onload=generarCaptcha&render=explicit' defer async></script>
<?php endif; ?>