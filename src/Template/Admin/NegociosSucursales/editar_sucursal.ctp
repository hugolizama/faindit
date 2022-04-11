<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Editar sucursal') ?> <?= $sucursal['nombre'] ?> de <?= $this->Html->link($sucursal['negocio']['nombre'], [
        'prefix'=>'admin', 'controller'=>'NegociosSucursales', 'action'=>'editarNegocio', $negocio_id
      ]); ?></h3>       
    </div>
  </div>

  <div class="row">    
    <div class="col-xs-12">
      <?= $this->Form->create($sucursal, ['class' => 'form-horizontal ']); ?>
      <div class="row">
        <div class="col-xs-12">
          <?= $this->element('Admin/menu-sucursales'); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-4 col-md-4 col-lg-3 required"><?= __('Nombre de la sucursal') ?></label>
        <div class="col-sm-8 col-md-8 col-lg-7">  
          <?php
          echo $this->Form->input('nombre', array(
            'class' => 'form-control ',
            'div' => false,
            'label' => false,
          ));
          ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-4 col-md-4 col-lg-3"><?= __('Tel&eacute;fonos') ?></label>
        <div class="col-sm-8 col-md-8 col-lg-7">            
          <div id="tel_adicional">
            <?php 
              /*Generar campos en cado de error en la pagina y no perder los datos llenados*/
              if(!empty($sucursal['telefonos']) && count($sucursal['telefonos'])>0){
                $telefonos = (!isset($data['telefonos'])) ? $sucursal['telefonos'] : $data['telefonos'];
                foreach($telefonos as $key=>$tel){
            ?>
                  <div id="div-tel-<?= $key ?>" class="input-group margin-bottom-5x">
                    <div class="input-group-btn">  
                      <?php  
                      echo $this->Form->input('telefonos.'.$key.'.id', [ 
                        'type'=>'hidden',
                        'class'=>'form-control'
                      ]);
                      ?>
                      <?= $this->Form->select('telefonos.'.$key.'.tipo',[
                          1 => _('Tel.'),
                          2 => __('Fax'),
                          3 => __('Cel.')
                        ],[
                          'id'=>'telefonos.'.$key.'.tipo',
                          'class'=>'form-control  telefono-tipo',
                          'style'=>'width: 70px;',
                          'default' => $telefonos[$key]['tipo']
                        ]); ?>
                    </div>
                    <?= $this->Form->input('telefonos.'.$key.'.numero',[
                      'id'=>'telefonos.'.$key.'.numero',
                      'class'=>'form-control  telefono-numero',
                      'label'=>false,
                      'div'=>false,
                      'placeholder'=>'0000-0000'
                    ]); ?>  
                    <span class="input-group-addon ">
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
        <div class="control-label-indicacion col-sm-4 col-md-4 col-lg-3">
          <label class="control-label"><?= __('Correo electr&oacute;nico') ?></label>          
        </div>
        <div class="col-sm-8 col-md-8 col-lg-7">  
          <?php
          echo $this->Form->input('correo', array(
            'type'=>'email',
            'class' => 'form-control ',
            'div' => false,
            'label' => false,
          ));
          ?>
        </div>
      </div>

      <div class="form-group">
        <div class="col-xs-12 col-lg-11">
          <h5 style="border-bottom: 1px solid #DDD;">
            <?= __('Editar redes sociales de la sucursal') ?>          
          </h5>
        </div>
      </div>

      <div id="neg-redes" class="margin-bottom-10x">
        <div class="row">
            <div class="col-xs-12 col-lg-10">
            <label class="control-label" style="text-align: left;">
            <?= __('<b>Opcional:</b> En esta secci&oacute;n puede agregar las redes sociales espec&iacute;ficas de la sucursal. 
              Si deja los campos vac&iacute;os en su lugar aparecer&aacute;n las espec&iacute;ficadas en la ficha
              principal de su negocio.') ?>
            </label>
          </div>
        </div>

        <div class="form-group">
          <div class="control-label-indicacion col-sm-4 col-md-4 col-lg-3">
            <label class="control-label"><?= __('Facebook') ?></label> 
            <a tabindex="0" role="button" data-toggle="popover" 
               data-trigger="focus" data-placement="top" data-html="true"
               data-content=' 
               <div style="width: 240px;">
               <?= __('Copie y pegue la direcci&oacute;n web de su fanpage') ?><br/>
               <br/>
               <?php
               echo $this->Html->link(__('Ver indicaciones'), [
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
              'class' => 'form-control ',
              'div' => false,
              'label' => false,
              'placeholder' => __('Ej: https://www.facebook.com/faindit')
            ));
            ?>
          </div>
        </div>

        <div class="form-group">
          <div class="control-label-indicacion col-sm-4 col-md-4 col-lg-3">
            <label class="control-label"><?= __('Twitter') ?></label> 
            <a tabindex="0" role="button" data-toggle="popover" 
               data-trigger="focus" data-placement="top" data-html="true"
               data-content=' 
               <div style="width: 160px;">
               <?= __('Digite el nombre de usuario de su cuenta de Twitter.') ?><br/>
               <br/>
               <?php
               echo $this->Html->link(__('Ver indicaciones'), [
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
              'class' => 'form-control ',
              'div' => false,
              'label' => false,
              'placeholder' => __('Ej: faindit')
            ));
            ?>
          </div>
        </div>

        <div class="form-group">
          <div class="control-label-indicacion col-sm-4 col-md-4 col-lg-3">
            <label class="control-label"><?= __('Google +') ?></label> 
            <a tabindex="0" role="button" data-toggle="popover" 
               data-trigger="focus" data-placement="top" data-html="true"
               data-content=' 
               <div style="width: 165px;">
               <?= __('Digite el nombre de usuario o id de su p&aacute;gina en Google +.') ?><br/>
               <br/>
               <?php
               echo $this->Html->link(__('Ver indicaciones'), [
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
              'class' => 'form-control ',
              'div' => false,
              'label' => false,
              'placeholder' => __('Ej: +faindit &oacute; 102745894110736266558'),
              'escape' => false
            ));
            ?>
          </div>
        </div>

        <div class="form-group">
          <div class="control-label-indicacion col-sm-4 col-md-4 col-lg-3">
            <label class="control-label"><?= __('Instagram') ?></label> 
            <a tabindex="0" role="button" data-toggle="popover" 
               data-trigger="focus" data-placement="top" data-html="true"
               data-content=' 
               <div style="width: 165px;">
               <?= __('Digite el nombre de usuario de su cuenta en Instagram.') ?><br/>
               <br/>
              <?php
              echo $this->Html->link(__('Ver indicaciones'), [
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
              'class' => 'form-control ',
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
            'prefix'=>'admin',
            'controller' => 'NegociosSucursales',
            'action' => 'editarSucursal',
            $sucursal_id
            ), array(
            'div' => false,
            'class' => 'btn btn-danger',
            'escape' => false
          ));
          ?>
        </div>
      </div>

      <?= $this->Form->end(); ?>

    </div>
  </div>
</div>

<script>  
$(document).ready(function(){  
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
        'prefix'=>false,
        'controller'=>'Sucursales',
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
  
  $('#agregar_telefono').click(function(){
    var id = $('.telefono-tipo:last').attr('id');  
    tel_agregar(id);
  });
  
  $(document).on('click', '.tel_quitar',function(){
    var id = $(this).attr('id');
    tel_quitar(id);
  });
  
});
</script>
