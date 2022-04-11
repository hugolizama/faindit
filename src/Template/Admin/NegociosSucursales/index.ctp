<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">  
  <div class="row">
    <div class="col-xs-12">
      <h3>
        <?= __('Negocios. (Total '.$countNegocios.')') ?>
        <?php
        echo $this->Html->link('<i class="fa fa-plus"></i> ' . __('Nuevo'), [
          'prefix'=>'admin', 'controller'=>'NegociosSucursales', 'action'=>'agregarNegocio'
        ], [
          'class' => 'btn btn-default btn-sm btn-header',
          'escape' => false           
        ]);
        ?>
      </h3>       
    </div>
  </div>  
  
  
  <?php
  echo $this->Form->create('Negocios', ['class' => 'form-inline', 'id'=>'frmNegocios']);
  ?>
  
  <!-- Modal de suspension -->
  <div class="modal fade" id="modal-suspension" tabindex="-1" role="dialog" aria-labelledby="modal-title">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-title"><?= __('Deshabilitar negocio') ?></h4>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-xs-12">
                <label><?= __('Raz&oacute;n para deshabilitar') ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <?php
                echo $this->Form->textarea('razon_deshabilitado', [
                  'id' => 'razon_deshabilitado',
                  'class' => 'form-control',
                  'cols'=>25
                ]);
                ?>
              </div>
            </div>
          </div>  
        </div>
        <div class="modal-footer">     
          <?= $this->Form->submit(__('Aplicar'),[
            'id'=>'btnAplicarAccion3',
            'name'=>'btnAplicarAccion3',
            'class'=>'btn btn-primary'
          ]); ?>
          
          <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancelar') ?></button>
        </div>
      </div>
    </div>
  </div>
  <!-- fin de modal de suspension -->

  <div class="row form-group-sm">  
    <div class="col-xs-12">
      <label class="control-label"><?= __('Ver') ?></label>
      <?= $this->Form->select('limite', $verOpciones,[
        'id'=>'limite',
        'default'=>$limite,
        'class'=>'form-control'
      ]); ?>
      
      <?php 
      $arrayAcciones[-1] = __('Acciones');
      $arrayAcciones[1] = __('Habilitar');
      $arrayAcciones[0] = __('Deshabilitar');
      $arrayAcciones[2] = __('Eliminar');
      
      ?>  
      
      <?= $this->Form->select('accion', $arrayAcciones,[
        'id'=>'selAccion',
        'default'=>'-1',
        'class'=>'form-control accion-select'
      ]); ?>
      
      <?= $this->Form->submit(__('Aplicar'),[
        'id'=>'btnAplicarAccion',
        'name'=>'btnAplicarAccion',
        'class'=>'btn btn-default btn-sm btn-margin-right',
        'disabled'=>true
      ]); ?>
      
      <?= $this->Form->input('txtNegocio',[
        'div'=>false,
        'label'=>false,
        'class'=>'form-control',
        'placeholder'=>__('Negocio'),
        'value'=>$textoBuscarNegocio
      ]); ?>
      
      <?= $this->Form->input('txtUsuario',[
        'div'=>false,
        'label'=>false,
        'class'=>'form-control',
        'placeholder'=>__('Usuario'),
        'value'=>$textoBuscarUsuario
      ]); ?>
      
      <?= $this->Form->submit(__('Buscar'),[
        'id'=>'btnBuscar',
        'name'=>'btnBuscar',
        'class'=>'btn btn-default btn-sm btn-margin-right'
      ]); ?>
      
      <nav class="pagination-top pagination-usuarios">    
        <ul class="pagination">
          <?php          
          /*primer pagina*/
          echo $this->Paginator->first('<<');

          /*anterior*/
          echo $this->Paginator->prev('<');

          /*numeros de paginas*/
          echo $this->Paginator->counter(
            '<li><span><input type="text" name="pag1" id="pag1" class="pag-input" value="{{page}}" /> de {{pages}}</span></li>'
          );

          /*siguiente*/
          echo $this->Paginator->next('>');

          /*ultima*/
          echo $this->Paginator->last('>>');
          ?>
        </ul>
      </nav>
    </div>
  </div>
  <div class="separador"></div>
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th style="width: 40px;"><input id="selectall" type="checkbox"></th>
            <th style="width: 60px;"></th>
            <th style="min-width: 140px;">
              <?php
              echo $this->Paginator->sort('Negocios.nombre', 'Nombre', ['direction'=>'asc']);
              ?>
            </th>    
            <th>
              <?php
              echo $this->Paginator->sort('Negocios.descripcion', 'Descripción');
              ?>
            </th> 
            <th style="min-width: 100px;">
              <?php
              echo $this->Paginator->sort('Negocios.count', 'Sucursales', ['direction'=>'desc']);
              ?>
            </th>
            <th style="min-width: 100px;">
              <?php
              echo $this->Paginator->sort('Negocios.countCat', h('Categorías'), ['direction'=>'asc']);
              ?>
            </th>
            <th style="min-width: 80px;">
              <?php
              echo $this->Paginator->sort('Usuarios.usuario', 'Usuario');
              ?>
            </th>
            <th style="min-width: 150px;">
              <?php
              echo $this->Paginator->sort('Negocios.fecha_creacion', 'Fecha de creación', ['direction'=>'desc']);
              ?>
            </th> 
          </tr>
        </thead>
        <tbody>
          <?php foreach($negocios as $neg): ?>
          <tr class="<?= ($neg['admin_habilitado']==0) ? 'reg-deshabilitado' : ''; ?>">
            <td>
              <?=
              $this->Form->checkbox('seleccion[]', [
                'class' => 'selectall',
                'hiddenField'=>false,
                'value' => $neg->id
              ])
              ?>
            </td>
            <td>
              <?=
              $this->Html->link('<span class="fa fa-edit fa-accion"></span>', [
                'prefix' => 'admin',
                'controller' => 'NegociosSucursales',
                'action' => 'editarNegocio',
                $neg->id
                ], [
                'escape' => false
              ]);
              ?>
              
              <?= $this->Html->link('<span class="fa fa-share fa-accion"></span>',[
                'prefix'=>false,
                'controller'=>'N',
                'action'=>'index',
                $neg['Sucursales']['id'],
                $neg['nombre_slug']
              ],[
                'escape'=>false,
                'title'=>__('Ver Sucursal'),
                'target'=>'_blank'
              ]); ?>
            </td>
            <td>
              <?=
              $this->Html->link($neg->nombre, [
                'prefix' => 'admin',
                'controller' => 'NegociosSucursales',
                'action' => 'editarNegocio',
                $neg->id
                ], [
                'escape' => false
              ]);
              ?>
            </td>            
            <td><?= $this->Text->truncate($neg->descripcion, 100) ?></td>   
            <td>
            <?php
            echo $this->Html->link($neg['count'], ['prefix'=>'admin', 'controller'=>'NegociosSucursales', 
              'action'=>'listaSucursales',$neg->id]) 
            ?>
            </td>
            <td><?= $neg['countCat'] ?></td>
            <td><?= $neg['Usuarios']['usuario'] ?></td> 
            <td><?= $neg->fechaCreacionFormat ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>    
  </div>
  <div class="row form-group-sm">  
    <div class="col-xs-12">
            
      <?= $this->Form->select('accion2', $arrayAcciones,[
        'id'=>'selAccion2',
        'default'=>'-1',
        'class'=>'form-control'
      ]); ?>
      
      <?= $this->Form->submit(__('Aplicar'),[
        'id'=>'btnAplicarAccion2',
        'name'=>'btnAplicarAccion2',
        'class'=>'btn btn-default btn-sm btn-margin-right',
        'disabled'=>true
      ]); ?>
      
      <nav class="pagination-top">    
        <ul class="pagination">
          <?php          
          /*primer pagina*/
          echo $this->Paginator->first('<<');

          /*anterior*/
          echo $this->Paginator->prev('<');

          /*numeros de paginas*/
          /*echo $this->Paginator->numbers(['first' => 2, 'last' => 2, 'modulus'=>2]);*/
          echo $this->Paginator->counter(
            '<li><span><input type="text" name="pag2" id="pag2" class="pag-input" value="{{page}}" /> de {{pages}}</span></li>'
          );

          /*siguiente*/
          echo $this->Paginator->next('>');

          /*ultima*/
          echo $this->Paginator->last('>>');
          ?>
        </ul>
      </nav>
    </div>
  </div>
  <?php echo $this->Form->end(); ?>
</div>

<script>
  $(document).ready(function () {
    
    /*cambio de limite*/
    $('#limite').change(function(){      
      $('#frmNegocios').submit();
    });
    
    
    /*acciones*/
    $('#selAccion').change(function(){
      var accion = $(this).val();
      if (accion !== '-1'){
        $('#btnAplicarAccion').attr('disabled',false);
      }else{
        $('#btnAplicarAccion').attr('disabled',true);
      }
    });
    
     $('#selAccion2').change(function(){
      var accion = $(this).val();
      if (accion !== '-1'){
        $('#btnAplicarAccion2').attr('disabled',false);
      }else{
        $('#btnAplicarAccion2').attr('disabled',true);
      }
    });    
    
    /*control de paginacion*/
    $('#pag1').click(function(){
      $(this).select();
    });
    
    $('#pag1').keypress(function (e){
      tecla = (document.all) ? e.keyCode : e.which; /*capturar tecla*/
      var pag = $(this).val();
      
      if(tecla===13){
        if(pag=='' || pag<1){
          $('#pag1').val(1);
        }
      
        $('#pag2').attr('name','');
        $('#frmNegocios').submit();
      }
    });
    
    $('#pag2').click(function(){
      $(this).select();
    });
    
    $('#pag2').keypress(function (e){
      tecla = (document.all) ? e.keyCode : e.which; /*capturar tecla*/
      var pag = $(this).val();
      
      if(tecla===13){
        if(pag=='' || pag<1){
          $('#pag2').val(1);
        }
      
        $('#pag1').attr('name','');
        $('#frmNegocios').submit();
      }
    });    
    /*fin control de paginacion*/
    
    
    $('#txtusuario').keypress(function (e){
      tecla = (document.all) ? e.keyCode : e.which; /*capturar tecla*/
            
      if(tecla===13){        
        $('#btnBuscar').click();
      }
    });
    
    
    function mostrar_modal(e){
      if($('#selAccion').val()==0 || $('#selAccion2').val()==0){
        e.preventDefault();
        $('#modal-suspension').modal('show');
      } 
    }    
    
    $('#btnAplicarAccion').click(function(e){
      mostrar_modal(e);      
    });
    
    $('#btnAplicarAccion2').click(function(e){
      mostrar_modal(e);      
    });
    
    $('#selectall').on('click', function () {          
      var checked_status = this.checked;

      $(".selectall").each(function () {
        this.checked = checked_status;
      });
    });
    
  });
</script>