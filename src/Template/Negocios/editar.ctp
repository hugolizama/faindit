<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>

<?php
  $logo_max_ancho = (isset($config['negocios_logo_max_ancho'])) ? $config['negocios_logo_max_ancho'] : 200;
  $logo_max_altura = (isset($config['negocios_logo_max_altura'])) ? $config['negocios_logo_max_altura'] : 120;
?>

<!--modal sugerencia de categorias -->
<div class="modal fade" id="modalSugerenciaCategorias" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalPara">Sugerencia de categor&iacute;as</h4>
      </div>
      <div class="modal-body">  
        <div id='modalError' class="form-group form-group-sm"></div>      
        <div class="form-group form-group-sm text-justify">
          <?= __('Si no encuentras una categor&iacute;a acorde a tu negocio u organizaci&oacute;n puedes hacernos la sugerencia
            en este formulario y serás notificado cuando sea aprobada. Guardar una sugerencia a la vez.<hr/>') ?>
        </div>
        <div class="form-group form-group-sm">
          <label for="modalCategoria" class="control-label required"><?= __('Categor&iacute;a:') ?></label>
          <input type="text" class="form-control" id="modalCategoria" />
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancelar') ?></button>
        <button type="button" class="btn btn-primary" id='modalGuardar'><?= __('Guardar') ?></button>
      </div>
    </div>
  </div>
</div>
<!--fin modal sugerencia de categorias -->

<div class="container-fluid">
  <div class="container">    
    <div class="row">      
      <div class="col-xs-12">
        <?php
        /*flash para mostrar que el negocio deshabilitado ha sido reportado para revision*/
        echo $this->Flash->render('notificado'); 
        ?>
        <h2 class="page-header"><?= __('Editar') ?> <?= $negocio['nombre'] ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3">
        <?= $this->element('menu-perfil'); ?>
      </div>
      <div class="col-sm-9">
        <?= $this->Form->create($negocio, ['class' => 'form-horizontal frm-sm', 'type'=>'file']); ?>
        <div class="form-group">
          <div class="col-xs-12">
            <?= $this->element('menu-n-editar'); ?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-4 col-md-4 col-lg-3 required"><?= __('Nombre del negocio') ?></label>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <?php
            echo $this->Form->input('nombre', array(
              'class' => 'form-control input-sm',
              'div' => false,
              'label' => false,
            ));
            ?>
          </div>
        </div>        
        
        <div class="form-group">
          <label class="control-label col-sm-4 col-md-4 col-lg-3 required">
            <?= __('Descripci&oacute;n del <div class="visible-md visible-lg"></div>negocio') ?> (<span id="contador">500</span>)
          </label>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <?= $this->Form->input('descripcion',[
              'id'=>'descripcion',
              'type'=>'textarea',
              'label'=>false,
              'div'=>false,
              'class'=>'form-control input-sm',
              'maxlength'=>500,
              'escape'=>false,
            ]); ?>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-4 col-md-4 col-lg-3"><?= __('Tel&eacute;fonos') ?></label>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <div id="tel_adicional">
              <?php 
                /*Generar campos en cado de error en la pagina y no perder los datos llenados*/
                if(!empty($negocio['sucursales'][0]['telefonos']) && count($negocio['sucursales'][0]['telefonos'])>0){
                  $telefonos = (!isset($data['sucursales'][0]['telefonos'])) ? $negocio['sucursales'][0]['telefonos'] : $data['sucursales'][0]['telefonos'];
                  foreach($telefonos as $key=>$tel){
              ?>
                    <div id="div-tel-<?= $key ?>" class="input-group margin-bottom-5x">
                      <div class="input-group-btn">  
                        <?php  
                        echo $this->Form->input('sucursales.0.telefonos.'.$key.'.id', [ 
                          'id'=>'telefonos-'.$key.'-id',
                          'type'=>'hidden',
                          'class'=>'form-control'
                        ]);
                        ?>
                        <?= $this->Form->select('sucursales.0.telefonos.'.$key.'.tipo',[
                            1 => _('Tel.'),
                            2 => __('Fax'),
                            3 => __('Cel.')
                          ],[
                            'id'=>'telefonos.'.$key.'.tipo',
                            'class'=>'form-control input-sm telefono-tipo',
                            'style'=>'width: 70px;',
                            'default' => $telefonos[$key]['tipo']
                          ]); ?>
                      </div>
                      <?= $this->Form->input('sucursales.0.telefonos.'.$key.'.numero',[
                        'id'=>'telefonos.'.$key.'.numero',
                        'class'=>'form-control input-sm telefono-numero',
                        'label'=>false,
                        'div'=>false,
                        'placeholder'=>'0000-0000'
                      ]); ?>  
                      <span class="input-group-addon input-sm">
                        <span id="tel_quitar.<?= $key ?>" title="<?= __('Eliminar este registro'); ?>" class="fa fa-close cursor-pointer tel_quitar"></span>
                      </span>
                    </div>
              <?php
                  }
                } 
              ?>
            </div>
            <div>              
              <span id="agregar_telefono" class="fa fa-plus cursor-pointer" title="<?= __('Agregar tel&eacute;fono'); ?>"></span>                         
            </div>
            
            <?= $this->Form->input('borrarTelefonos', [
              'type'=>'hidden',
              'id'=>'borrarTelefonos'
            ]) ?>
            
          </div>         
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-4 col-md-4 col-lg-3"><?= __('Correo electr&oacute;nico') ?></label>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <?php
            echo $this->Form->input('correo', array(
              'type'=>'email',
              'class' => 'form-control input-sm',
              'div' => false,
              'label' => false,
            ));
            ?>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-4 col-md-4 col-lg-3"><?= __('Direcci&oacute;n del sitio web') ?></label>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <?php
            echo $this->Form->input('url', array(
              'class' => 'form-control input-sm',
              'div' => false,
              'label' => false,
              'placeholder'=>'Ej: www.faindit.com'
            ));
            ?>
          </div>
        </div>
        
        <div class="form-group">
          <div class="control-label col-sm-4 col-md-4 col-lg-3">
            <label class="control-label"><?= __('Categor&iacute;as') ?> (max. <?php echo (isset($config['negocios_max_num_cat'])) ? $config['negocios_max_num_cat'] : 5 ?>)</label>
            <a tabindex="0" role="button" data-toggle="popover" 
               data-trigger="focus" data-placement="top" data-html="true"
               data-content=' 
               <div style="width: 200px;">
               <?= __('Digite y seleccione en el texto predictivo las categor&iacute;as que mejor correspondan con el rubro de su negocio.') ?>
               </div>
               ' >
              <span title="<?= __('Indicaci&oacute;n') ?>" class="fa fa-question-circle indicacion-icono"></span>
            </a>
          </div>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <?php
            echo $this->Form->select('categorias._ids',[],[
              'id'=>'categorias',
              'class'=>'form-control input-sm',
              'multiple'=>true,
              'data-role'=>'tagsinput',
              'placeholder'=>'Ej.: Abogados, M&eacute;dicos, etc.'
            ]);
            ?>            
            <div>
              <a id="cat-sugerencias" href="#" data-toggle="modal" data-target="#modalSugerenciaCategorias">Sugi&eacute;renos una categor&iacute;a</a>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <div class="control-label col-sm-4 col-md-4 col-lg-3">
            <label class="control-label"><?= __('Logo') ?> (max. <?= $logo_max_ancho ?>x<?= $logo_max_altura ?>)</label>
            <a tabindex="0" role="button" data-toggle="popover" 
              data-trigger="focus" data-placement="top" data-html="true"
              data-content=' 
              <div style="width: 240px;">
              Logo m&aacute;ximo de <?= $config['negocios_logo_max_peso']; ?>KB. Si supera los
              <?= $logo_max_ancho ?>x<?= $logo_max_altura ?> pixeles sera redimensionado hasta estar dentro 
              de los par&aacute;metros.
              </div>
              ' >
             <span title="<?= __('Indicaci&oacute;n') ?>" class="fa fa-question-circle indicacion-icono"></span>
            </a>
          </div>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <?php
            echo $this->Form->input('logo', array(
              'type'=>'file',
              'class' => 'form-control input-sm',
              'div' => false,
              'label' => false
            ));
            ?>
          </div>
        </div>
        
        <div class="form-group">
          <div class="control-label col-sm-4 col-md-4 col-lg-3">
            <label class="control-label"><?= __('Eliminar logo ') ?></label>            
            <?= $this->Form->checkbox('eliminar_logo', [
              'class'=>'check_eliminar_logo'              
            ]) ?>
            <a tabindex="0" role="button" data-toggle="popover" 
              data-trigger="focus" data-placement="top" data-html="true"
              data-content=' 
              <div style="width: 240px;">
              Si desea cambiar su logo no necesita marcar esta opci&oacute;n, s&oacute;lo cargar el nuevo.<br/>
              Esta opci&oacute;n es para eliminar su logo personalizado.
              </div>
              ' >
             <span title="<?= __('Indicaci&oacute;n') ?>" class="fa fa-question-circle indicacion-icono"></span>
            </a>
          </div>
          <div class="col-sm-8 col-md-8 col-lg-7">  
            <?php
            $ruta_img = 'neg/'.$negocio_id.'/logo.jpg';
            
            if(!file_exists('img/'.$ruta_img)){
              $ruta_img = 'no_logo.jpg';
            }
            
            echo $this->Html->image($ruta_img, ['class'=>'logo-negocio']);
            ?>
          </div>
        </div>
        
        
        <div class="form-group">
          <div class="col-xs-12 col-lg-11">
            <h5 style="border-bottom: 1px solid #DDD;">
              <?= __('Editar redes sociales') ?>           
            </h5>
          </div>
        </div>
        
        <div id="neg-redes">
          <div class="form-group">
            <div class="control-label col-sm-4 col-md-4 col-lg-3">
              <label class="control-label"><?= __('Facebook') ?></label>
              <a tabindex="0" role="button" data-toggle="popover" 
                 data-trigger="focus" data-placement="top" data-html="true"
                 data-content=' 
                 <div style="width: 240px;">
                 <?= __('Copie y pegue la direcci&oacute;n web de su fanpage') ?><br/>
                 <br/>
                 <?php
                 echo $this->Html->link(__('Ver indicaciones'), [
                   'prefix'=>false,
                   'controller' => 'Ayuda',
                   'action' => 'redesSociales#Facebook'
                   ], [
                   'target' => '_blank'
                 ]);
                 ?>
                 </div>
                 ' >
                <span title="<?= __('Indicaci&oacute;n') ?>" class="fa fa-question-circle indicacion-icono"></span>
              </a>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-7">
              <?php
              echo $this->Form->input('facebook', array(
                'class' => 'form-control input-sm',
                'div' => false,
                'label' => false,
                'placeholder' => __('Ej: https://www.facebook.com/faindit')
              ));
              ?>
            </div>
          </div>

          <div class="form-group">
            <div class="control-label col-sm-4 col-md-4 col-lg-3">
              <label class="control-label"><?= __('Twitter') ?></label> 
              <a tabindex="0" role="button" data-toggle="popover" 
                 data-trigger="focus" data-placement="top" data-html="true"
                 data-content=' 
                 <div style="width: 160px;">
                 <?= __('Digite el nombre de usuario de su cuenta de Twitter.') ?><br/>
                 <br/>
                 <?php
                 echo $this->Html->link(__('Ver indicaciones'), [
                   'prefix'=>false,
                   'controller' => 'Ayuda',
                   'action' => 'redesSociales#Twitter'
                   ], [
                   'target' => '_blank'
                 ]);
                 ?>
                 </div>
                 ' >
                <span title="<?= __('Indicaci&oacute;n') ?>" class="fa fa-question-circle indicacion-icono"></span>
              </a>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-7">
              <?php
              echo $this->Form->input('twitter', array(
                'class' => 'form-control input-sm',
                'div' => false,
                'label' => false,
                'placeholder' => __('Ej: faindit')
              ));
              ?>
            </div>
          </div>

          <div class="form-group">
            <div class="control-label col-sm-4 col-md-4 col-lg-3">
              <label class="control-label"><?= __('Google +') ?></label>
              <a tabindex="0" role="button" data-toggle="popover" 
                 data-trigger="focus" data-placement="top" data-html="true"
                 data-content=' 
                 <div style="width: 165px;">
                 <?= __('Digite el nombre de usuario o id de su p&aacute;gina en Google +.') ?><br/>
                 <br/>
                 <?php
                 echo $this->Html->link(__('Ver indicaciones'), [
                   'prefix'=>false,
                   'controller' => 'Ayuda',
                   'action' => 'redesSociales#GooglePlus'
                   ], [
                   'target' => '_blank'
                 ]);
                 ?>
                 </div>
                 ' >
                <span title="<?= __('Indicaci&oacute;n') ?>" class="fa fa-question-circle indicacion-icono"></span>
              </a>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-7">
              <?php
              echo $this->Form->input('google_plus', array(
                'class' => 'form-control input-sm',
                'div' => false,
                'label' => false,
                'placeholder' => __('Ej: +faindit &oacute; 102745894110736266558'),
                'escape' => false
              ));
              ?>
            </div>
          </div>

          <div class="form-group">
            <div class="control-label col-sm-4 col-md-4 col-lg-3">
              <label class="control-label"><?= __('Instagram') ?></label> 
              <a tabindex="0" role="button" data-toggle="popover" 
                 data-trigger="focus" data-placement="top" data-html="true"
                 data-content=' 
                 <div style="width: 165px;">
                 <?= __('Digite el nombre de usuario de su cuenta en Instagram.') ?><br/>
                 <br/>
                <?php
                echo $this->Html->link(__('Ver indicaciones'), [
                  'prefix'=>false,
                  'controller' => 'Ayuda',
                  'action' => 'redesSociales#Instagram'
                  ], [
                  'target' => '_blank'
                ]);
                ?>
                 </div>
                 ' >
                <span title="<?= __('Indicaci&oacute;n') ?>" class="fa fa-question-circle indicacion-icono"></span>
              </a>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-7">
              <?php
              echo $this->Form->input('instagram', array(
                'class' => 'form-control input-sm',
                'div' => false,
                'label' => false,
                'placeholder' => __('Ej: faindit'),
                'escape' => false
              ));
              ?>
            </div>
          </div>
        </div>            
        
        <div class="form-group text-center">
          <div class="col-md-9">
            <?php
            echo $this->Form->submit(__('Guardar'), array(
              'class' => 'btn btn-success btn-margin-right',
              'div' => false,          
            ));

            echo $this->Html->link(__('Cancelar'), array(
              'prefix'=>false,
              'controller' => 'Negocios',
              'action' => 'editar',
              $negocio_id,
              $tokenFalso
              ), array(
              'div' => false,
              'class' => 'btn btn-danger',
              'escape' => false
            ));
            ?>
          </div>
        </div>
        <div class="form-group text-right" style="margin: 10px 0px 10px 0px; padding: 0px; font-size: 12px;">
          <div class="col-xs-12 col-lg-10">
            <?= $this->Html->link(__('Eliminar este negocio'),[              
              'prefix' => false,
              'controller'=>'Negocios',
              'action'=>'eliminarNegocio',
              $negocio_id,
              $tokenFalso
            ], [
              'confirm' => __('¿Desea eliminar este negocio? Esta acción es irreversible.')
            ]); ?>
          </div>
        </div>
        <?= $this->Form->end(); ?>
        
      </div>
    </div>
  </div>
</div>
<script> 
/*get categorias*/
var categorias = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: '',
  limit: <?php echo (isset($config['negocios_cat_visibles'])) ? $config['negocios_cat_visibles'] : 10 ?>,
  remote: {
    url: '<?= \Cake\Routing\Router::url([
        'controller'=>'Negocios',
        'action'=>'getCategorias'
      ]); ?>/%QUERY'
  }
});    
      
/*iniciar categorias*/
categorias.initialize();

var cat = $('#categorias');
cat.tagsinput({      
  itemValue: 'id',
  itemText: 'nombre',
  typeaheadjs: {
    name: 'categorias',
    displayKey: 'nombre',
    source: categorias.ttAdapter()
  },
  maxTags: <?php echo (isset($config['negocios_max_num_cat'])) ? $config['negocios_max_num_cat'] : 5 ?>
}); 


/*Inicializar con las categorias asignadas*/
<?php foreach($negocio['categorias'] as $categoria): ?>
cat.tagsinput('add', { "id": <?= $categoria['id'] ?> , "nombre": "<?= $categoria['nombre'] ?>"  });
<?php endforeach; ?>
/*fin get categorias*/
  
  
$(document).ready(function(){  
  /*contador de caracteres disponibles*/
  function descripcion_contar(){
    var max_texto = 500;

    var longitud = $('#descripcion').val().length;
    var queda = max_texto - longitud;

    $('#contador').html(queda);  
  }
  
  /*Agregar - quitar telefono adicional*/
  function tel_agregar(id){
    if(id!==undefined){
      var idAll = id.split('.');
      var index = parseInt(idAll[1]) + 1;
    }else{
      var index = 0;
    }
    
    $.ajax({
      url: '<?= \Cake\Routing\Router::url(array(
        'controller'=>'Negocios',
        'action'=>'addTelToForm')
      ); ?>/' + index,
      dataType: 'html'
    }).done(function(data){
      $('#tel_adicional').append(data);
      $('.telefono-numero').unmask().mask('####-####');
    });    
    
  }
  
  function tel_quitar(id){
    var idAll = id.split('.');
    var index = idAll[1];
    
    /*obtener id de telefono eliminado*/
    borrarTel = $('#borrarTelefonos');
    idTel = $('#telefonos-' + index + '-id').val();    
        
    if(idTel !== undefined){
      if(borrarTel.val()==''){
        borrarTel.val(idTel);
      }else{
        borrarTel.val(borrarTel.val() + ',' + idTel);
      }
    }    
    /*fin obtener id de telefono eliminado*/    
    
    $('#div-tel-' + index).remove();
  }
  /*fin agregar - quitar telefono adicional*/
  
  
  $('.telefono-numero').mask('####-####');
  
  /*activar popover*/
  $('[data-toggle="popover"]').popover();

  $('#descripcion').keyup(function(){
    descripcion_contar();
  }); 

  $('#descripcion').mouseenter(function(){
    descripcion_contar();
  });
  
  descripcion_contar();
  
  $('#agregar_telefono').click(function(){
    var id = $('.telefono-tipo:last').attr('id');     
    tel_agregar(id);
  });
  
  $(document).on('click', '.tel_quitar',function(){
    var id = $(this).attr('id');    
    tel_quitar(id);
  });
  
  
  /*Modal de sugerencias*/
  $('#modalSugerenciaCategorias').on('shown.bs.modal', function(){
    $('#modalCategoria').focus();
  });
  
  $('#modalCategoria').keypress(function(e){      
      if(e.keyCode==13){
        $('#modalGuardar').click();
      }
  });
  
  $('#modalGuardar').click(function(){ 
    var categoria = $('#modalCategoria').val();

    $.ajax({
      type: 'POST',
      data: {categoria:categoria},
      dataType: 'json',
      url: "<?= Cake\Routing\Router::url(['prefix'=>false, 'controller'=>'Negocios', 'action'=>'sugerenciaCategorias']) ?>"
    }).done(function(data){
      var obj = $.parseJSON(JSON.stringify(data));
      var cod = parseInt(obj.cod);
      var mensaje = obj.mensaje;
      var modalError = $('#modalError');

      switch(cod){
        case 0:          
          modalError.html(mensaje);          
          break;

        case 1:
          modalError.html(mensaje);
          $('#modalCategoria').val('');   
          $('#modalCategoria').focus();
          break;
      }        
    });
  });
  
  $('#modalSugerenciaCategorias').on('hidden.bs.modal', function(){
    $('#modalCategoria').val('');
    $('#modalError').html('');
  });
  
  /*fin modal de sugerencias*/
  
});

</script>
