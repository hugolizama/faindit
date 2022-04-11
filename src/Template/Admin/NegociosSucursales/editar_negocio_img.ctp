<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($negocio); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">  
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Im&aacute;genes de ') ?> <?= $negocio['nombre'] ?></h3>       
    </div>
  </div> 

  <div class="row">   
    <div class="col-sm-9 ">
      <?php echo $this->Form->create('Negocio', ['class' => 'form-horizontal ']); ?>  
      <div class="row">
        <div class="col-xs-12">
          <?= $this->element('Admin/menu-n-editar'); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <?php
          $img_peso = $config['negocios_img_max_peso']. 'KB';
          if($config['negocios_img_max_peso']>=1024){
            $img_peso = number_format($config['negocios_img_max_peso']/1024).'MB';
          }
          ?>

          <?= __('A continuaci&oacute;n puede cargar las im&aacute;genes que aparecer&aacute;n en el perfil de su negocio.<br/>
            M&aacute;ximo '.$config['negocios_cant_imagenes'].' im&aacute;genes de '.$img_peso.' cada una. Si el tama&ntilde;o excede de '.
            $config['negocios_img_max_ancho'].'x'.$config['negocios_img_max_altura'].' pixeles se ajustar&aacute;n hasta alcanzar
            los par&aacute;metros.') ?>
        </div>
      </div>
      <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 col-lg-10">             
          <div id="img_upload">Cargar</div>   
        </div>          
      </div>  

      <div id="div-sortable">
      <?php if(count($negocio['imagenes'])>0): ?>
      <div class="row">
        <div class="col-xs-12 col-lg-10">
          <label class="control-label control-label-left">
            <?= __('Arrastre y suelte las im&aacute;genes para ordenarlas seg&uacute;n su preferencia. Para seleccionar  o 
              deseleccionar una im&aacute;gen ') ?>

            <?php
            if($isMobile==true){
              echo __('mantengala presionada por un segundo.');
            }else{
              echo __('haga clic sobre ella.');
            }
            ?>
          </label>
        </div>
      </div>

      <div   class="row" style="text-align: center;">
        <div id="div-eliminar-img" class="col-xs-12" style=""></div>
        <div id="menu-eliminar-img" class="col-xs-12 text-center">
          <input id="selectall" type="checkbox" style="display: none;">
          <label id="label-selectall" for="selectall" class="btn btn-primary btn-sm btn-margin-right"><?= __('Seleccionar todo') ?></label>
          <button id="btn-eliminar-seleccionadas" type="submit" name="btnEliminarImagenes" class="btn btn-danger btn-sm" disabled="disabled"><?= __('Eliminar seleccionadas') ?></button>
        </div>
      </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-xs-12">
          <ul id="sortable">
            <?php foreach ($negocio['imagenes'] as $img): ?>
            <li id="li-<?= $img['id'] ?>" class="ui-state-default li-check">
              <input type="checkbox" class="chk-img selectall" value="<?= $img['id'] ?>" name="chk[]" id="chk-<?= $img['id'] ?>"/>
              <input type="text" class="img-order" value="<?= $img['orden'] ?>" name="img[<?= $img['id'] ?>]" id="img-<?= $img['id'] ?>">
              <?= $this->Html->image('neg/'.$img['negocio_id'].'/'.$img['sucursal_id'].'/'.$img['nombre'].'.jpg', [
                'class'=>'sortable-img',                  
              ]) ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>  
      </div>
      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>

<script>
  $(document).ready(function(){   
    
    var topPosition;
    
    
    $("#img_upload").uploadFile({
      url: "<?= \Cake\Routing\Router::url(['prefix'=>false,'controller'=>'Negocios','action'=>'cargarImagenes',$negocio_id, 0]) ?>",
      fileName: "file",
      returnType: "json",
      allowedTypes: "jpg,jpeg,png",
      sequential:true,
      sequentialCount:1,
      showDone: true,
      showProgress: true,
      showStatusAfterSuccess: true,
      multiple: true,
      maxFileSize: <?php echo $max_upload; ?>,
      maxFileCount: <?php echo (isset($config['negocios_cant_imagenes'])) ? $config['negocios_cant_imagenes'] : 10; ?>, 
      
      afterUploadAll: function(obj){      
        mostrarImagenes();
        
        /*agregar link para borrar historial de mensajes*/
        $('#limpiar_historial').remove();                
        contenedor = $('.ajax-file-upload-container');        
        contenedor.append('<div id="limpiar_historial" style="margin-left: 10px; cursor:pointer; text-decoration: underline;">Limpiar historial de mensajes</div>');
      }
    });
    
    
    try {
      topPosition = $('#menu-eliminar-img').offset().top;      
    } catch(err){
      topPosition = $('#div-sortable').offset().top;
    }
    
    $(window).on('scroll', function() {
      if ($(window).scrollTop() > topPosition) {        
        $('#menu-eliminar-img').addClass('fixed');
        $('#menu-eliminar-img').width($('#div-eliminar-img').width());
      } else {
        $('#menu-eliminar-img').removeClass('fixed');
        $('#menu-eliminar-img').width('');
      }
    });
     
    
    $(document).on('click','#selectall', function () {          
      var checked_status = this.checked;

      $(".selectall").each(function () {
        this.checked = checked_status;
        
        if(checked_status===true){
          $('#label-selectall').html('<?= __('Deseleccionar todo') ?>');
          $('.li-check').css('opacity', '0.5');
          $('#btn-eliminar-seleccionadas').attr('disabled',false);
        }else{
          $('#label-selectall').html('<?= __('Seleccionar todo') ?>');
          $('.li-check').css('opacity', '');
          $('#btn-eliminar-seleccionadas').attr('disabled',true);
        }
      });
    });
    
    /*function para guardar el orden de las imagenes*/
    function guardarOrden(){
      var order = new Array(); /*inicializar array*/
      
      $('.img-order').each(function(){ /*recorrer cajas de orden*/
        var idFull = $(this).attr('id').split('-');
        var id = idFull[1]; /*obtener id*/
        
        order.push({'id': id, 'orden': $(this).val()});
      });
      
      /*guardar orden mediante ajax*/    
      $.ajax({
        url: '<?= \Cake\Routing\Router::url(['prefix'=>false,'controller'=>'Negocios','action'=>'guardarOrdenImagenes']); ?>',
        data: {'order': order}, /*datos del orden*/
        dataType: 'html',
        type: 'post'
      }).done(function(data){ /*finalizo ejecucion*/
        
      });
    };
    
    /*grid de fotos*/
    function sortable(){
      $("#sortable").sortable({
        helper: "clone",
        scrollSensitivity: 1,
        /*al finalizar ordenamiento enumerar orden*/
        stop: function (e,ui){ 
          var orden = 1;
          $('.img-order').each(function(){
            $(this).val(orden);
            orden = orden + 1;
          });

          guardarOrden();  

        }
      }); 
    }
    sortable();
    
    
    /*limpiar historial de mensajes*/
    $(document).on('click','#limpiar_historial', function(){   
      $('#jupload-errors').html('');
      $('.ajax-file-upload-container').html('');
      topPosition = $('#menu-eliminar-img').offset().top;  /*tomar nueva posicion*/
    });
    
    function mostrarImagenes(){
      $.ajax({
        url: '<?= \Cake\Routing\Router::url(['prefix'=>false,'controller'=>'Negocios','action'=>'mostrarImagenes', $negocio_id, 0]); ?>',
        dataType: 'html',
        type: 'post'        
      }).done(function(data){ /*finalizo ejecucion*/
        $('#div-sortable').html(data);
        sortable();
        topPosition = $('#menu-eliminar-img').offset().top;  /*tomar nueva posicion*/
      });
    }
    
    
    /*marcar imagen al hacer click*/
    $(document).on ('click','.li-check',function(e){ 
      var idFull = $(this).attr('id').split('-');
      var id = idFull[1]; /*obtener id*/
      
      check = $('#chk-' + id);
      li = $('#li-' + id);
      
      if(check.prop('checked') === false){
        check.prop('checked', true);
        li.css('opacity','0.5');        
      }else{
        check.prop('checked', false);
        li.css('opacity','');        
      }
      
      var selected = 0;
      $(".selectall").each(function () {        
        if(this.checked===true){
          selected = 1;
          return false;
        }        
      });
      
      if(selected===1){
        $('#btn-eliminar-seleccionadas').attr('disabled',false);
      }else{
        $('#btn-eliminar-seleccionadas').attr('disabled',true);
      }
      
    });
  });
</script>