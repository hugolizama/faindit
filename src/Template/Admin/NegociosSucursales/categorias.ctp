<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Categor&iacute;as (Total '. $totalCategorias .')') ?></h3>       
    </div>
  </div>
  

  <?= $this->Form->create($categoria, ['class' => 'form-inline', 'id'=>'frmCategorias']) ?>
  
  <div class="row form-group-sm">
    <div class="col-xs-12 col-sm-4 col-lg-3">
      <h4 style="margin-top: 0px;"><?= __('Nueva categor&iacute;a') ?></h4>
      <?= $this->Form->input('nombre',[
        'id'=>'nombre',
        'div'=>false,
        'label'=>false,
        'class'=>'form-control form-control-block',
        'placeholder'=>__('Categor&iacute;a'),
        'escape'=>false,
        'required'=>false, /*necesario*/
        'autofocus'=>'autofocus'
      ]); ?>
      
      <?= $this->Form->submit(__('Agregar'),[
        'id'=>'btnAgregarCategoria',
        'name'=>'btnAgregarCategoria',
        'class'=>'btn btn-default btn-sm btn-primary btn-margin-right',
        'style'=>'min-width: 100px;'
      ]); ?>
    </div>
    
    <div class="col-xs-12 col-sm-8 col-lg-9 table-responsive">
      <div class="row">
        <div class="col-xs-12">
          <label class="control-label"><?= __('Ver') ?></label>
          <?=
          $this->Form->select('limite', $verOpciones, [
            'id' => 'limite',
            'default' => $limite,
            'class' => 'form-control'
          ]);
          ?>

          <?=
          $this->Form->select('accion', [
            3 => __('Eliminar')
            ], [
            'id' => 'selAccion',
            'empty' => 'Acciones',
            'default' => '',
            'class' => 'form-control accion-select'
          ]);
          ?>

          <?=
          $this->Form->submit(__('Aplicar'), [
            'id' => 'btnAplicarAccion',
            'name' => 'btnAplicarAccion',
            'class' => 'btn btn-default btn-sm btn-margin-right',
            'disabled' => true
          ]);
          ?>      

          <?=
          $this->Form->input('txtCategoria', [
            'div' => false,
            'label' => false,
            'class' => 'form-control',
            'placeholder' => __('Categor&iacute;a'),
            'value' => $txtCategoria,
            'escape' => false
          ]);
          ?>

          <?=
          $this->Form->submit(__('Buscar'), [
            'id' => 'btnBuscarCategoria',
            'name' => 'btnBuscarCategoria',
            'class' => 'btn btn-default btn-sm btn-margin-right',
            'escape' => false
          ]);
          ?>

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
      
      <div class="row">
        <div class="col-xs-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th style="width: 40px;"><input id="selectall" type="checkbox"></th>
                <th style="width: 40px;"><?= __('Acci&oacute;n') ?></th>
                <th>
                  <?php
                  echo $this->Paginator->sort('Categorias.nombre', 'Nombre', array(
                    'direction' => 'asc'
                  ));
                  ?>
                </th>
                <th>
                  <?php
                  echo $this->Paginator->sort('Categorias.count', 'Contador', array(
                    'direction' => 'desc'
                  ));
                  ?>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categorias as $cat): ?>
                <tr>
                  <td>
                    <?=
                    $this->Form->checkbox('seleccion[]', [
                      'class' => 'selectall',
                      'hiddenField' => false,
                      'value' => $cat->id
                    ])
                    ?>
                  </td>
                  <td>
                    <span id="<?= $cat->id ?>-editar" class="fa fa-edit fa-accion cursor-pointer editar-cat span-link"></span>
                  </td>
                  <td id="<?= $cat->id ?>-nombre"><?= $cat->nombre; ?></td>
                  <td><?= $cat->count; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="row form-group-sm">  
        <div class="col-xs-12">
          <?=
          $this->Form->select('accion2', [
            3 => __('Eliminar')
            ], [
            'id' => 'selAccion2',
            'empty' => 'Acciones',
            'default' => '',
            'class' => 'form-control'
          ]);
          ?>

          <?=
          $this->Form->submit(__('Aplicar'), [
            'id' => 'btnAplicarAccion2',
            'name' => 'btnAplicarAccion2',
            'class' => 'btn btn-default btn-sm btn-margin-right',
            'disabled' => true
          ]);
          ?>

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
      
    </div>
  </div>
  <?= $this->Form->end(); ?>
  
  
  <!-- Modal editar -->
  <div class="modal fade" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="modal-title">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-title"><?= __('Editar categor&iacute;a') ?></h4>
        </div>
        <div class="modal-body">
          <div class="container-fluid">  
            <div class="row">
              <div id="error-editar" class="col-xs-12"></div>
            </div>
            <div class="row">              
              <div class="col-xs-12">
                <label><?= __('Nombre') ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <input id="nombre-editar" class="form-control" />
                <input id="id-editar" class="form-control" style="display: none;"/>
              </div>
            </div>
          </div>  
        </div>
        <div class="modal-footer">     
          <button type="button" id="btnEditar" class="btn btn-primary" ><?= __('Guardar') ?></button>
          <button type="button" class="btn btn-danger " data-dismiss="modal"><?= __('Cancelar') ?></button>
        </div>
      </div>
    </div>
  </div>
  <!-- fin de modal editar -->
  
</div>

<script>
  $(document).ready(function(){
    
    /*cambio de limite*/
    $('#limite').change(function(){      
      $('#frmCategorias').submit();
    });
    
    
    /*acciones*/
    $('#selAccion').change(function(){
      var accion = $(this).val();
      if (accion !== ''){
        $('#btnAplicarAccion').attr('disabled',false);
      }else{
        $('#btnAplicarAccion').attr('disabled',true);
      }
    });
    
     $('#selAccion2').change(function(){
      var accion = $(this).val();
      if (accion !== ''){
        $('#btnAplicarAccion2').attr('disabled',false);
      }else{
        $('#btnAplicarAccion2').attr('disabled',true);
      }
    });
    
    $('#selectall').on('click', function () {          
      var checked_status = this.checked;

      $(".selectall").each(function () {
        this.checked = checked_status;
      });
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
        $('#frmCategorias').submit();
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
        $('#frmCategorias').submit();
      }
    });    
    /*fin control de paginacion*/
    
    $('#txtcategoria').keypress(function (e){      
      tecla = (document.all) ? e.keyCode : e.which; /*capturar tecla*/
      if(tecla===13){        
        $('#btnBuscarCategoria').click();
      }
    });
    
    
    /*editar una categoria*/
    $('.editar-cat').click(function(){
      var idFull = $(this).attr('id').split('-');
      var id = idFull[0];
      
      $.ajax({
        url: "<?= \Cake\Routing\Router::url(['prefix'=>'admin','controller'=>'NegociosSucursales',
                'action'=>'getCategoriaEditar']); ?>/" + id,
        dataType: 'json',
        type: 'POST'
      }).done(function(data){  
        var obj = $.parseJSON(JSON.stringify(data));
               
        $('#nombre-editar').val(obj.nombre);        
        $('#id-editar').val(obj.id);
        
        $('#modal-editar').modal('show');        
        
      });
    });
    
    /*focus en input cuando se muestre el modal*/
    $('#modal-editar').on('shown.bs.modal', function () {
      $('#nombre-editar').focus();
    });
    
    /*guardar cambio*/
    $('#btnEditar').click(function(){
      var id = $('#id-editar').val();
      var nombre = $('#nombre-editar').val();
      
      $.ajax({
        data: {'id':id, 'nombre':nombre},
        url: "<?= \Cake\Routing\Router::url(['prefix'=>'admin', 'controller'=>'NegociosSucursales', 
              'action'=>'editarCategoria']) ?>",
        dataType: 'html',
        type: 'POST'
      }).done(function(data){
        if(data==1){
          $('#' + $('#id-editar').val() + '-nombre').html($('#nombre-editar').val()); 
          $('#modal-editar').modal('hide'); 
        }else{
          var error = '<div class="alert alert-danger" role="alert" style="padding: 5px; margin-bottom: 5px"><?= __('Error al actualizar') ?></div>';
          
          $('#error-editar').html(error);
        }
      });
    });
    
    /*focus en input cuando se muestre el modal*/
    $('#modal-editar').on('hidden.bs.modal', function () {
      $('#error-editar').html('');
    });
    
    
    $('#nombre-editar').keypress(function (e){      
      tecla = (document.all) ? e.keyCode : e.which; /*capturar tecla*/
      if(tecla===13){        
        $('#btnEditar').click();
      }
    });
    
  });
</script>