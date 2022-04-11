<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Sugerencia de categor&iacute;as') ?></h3>       
    </div>
  </div>
  
  <?= $this->Form->create($sugerenciaCategoria, ['class' => 'form-inline', 'id'=>'frmCategorias']) ?>
  
  <div class="row form-group-sm">
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
        1=>__('Aprobar'),
        2 => __('Rechazar'),
        3=>__('Eliminar')
        ], [
        'id' => 'selAccion',
        'empty' => 'Acciones',
        'default' => '',
        'class' => 'form-control'
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
    <div class="col-xs-12 table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th style="width: 40px;"><input id="selectall" type="checkbox"></th>
            <th style="width: 40px;"><?= __('Acci&oacute;n') ?></th>
            <th>
              <?php
              echo $this->Paginator->sort('CategoriasSugerencias.nombre', 'Nombre', array(
                'direction' => 'asc'
              ));
              ?>
            </th>
            <th>
              <?php
              echo $this->Paginator->sort('Usuarios.usuario', 'Usuario', array(
                'direction' => 'asc'
              ));
              ?>
            </th>
            <th>
              <?php
              echo $this->Paginator->sort('CategoriasSugerencias.fecha_creacion', 'Fecha de creación', array(
                'direction' => 'asc'
              ));
              ?>
            </th>
            <th>
              <?php
              echo $this->Paginator->sort('CategoriasSugerencias.estado', 'Estado', array(
                'direction' => 'asc'
              ));
              ?>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categorias as $cat): ?>
            <tr>
              <td>
                <?php
                if($cat->estado == 0){
                  echo $this->Form->checkbox('seleccion[]', [
                    'class' => 'selectall',
                    'hiddenField' => false,
                    'value' => $cat->id
                  ]);
                }                
                ?>
              </td>
              <td>
                <?php  if($cat->estado == 0): ?>
                <span id="<?= $cat->id ?>-editar" class="fa fa-edit fa-accion cursor-pointer editar-cat span-link"></span>
                <?php  endif; ?>
              </td>
              <td id="<?= $cat->id ?>-nombre"><?= $cat->nombre; ?></td>
              <td><?= $cat->usuario->usuario; ?></td>
              <td><?= $cat->fechaCreacionFormat; ?></td>
              <td>
                <?php
                switch ($cat->estado) {
                  case 0:
                    echo "Pendiente";
                    break;
                  case 1:
                    echo "<span style='color:green;'>Aprobada</span>";
                    break;
                  case 2:
                    echo "<span style='color:red;'>Rechazada</span>";
                    break;

                  default:
                    break;
                }
                ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>  
  <?= $this->Form->end(); ?>
  
  
  <!-- Modal editar -->
  <div class="modal fade" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="modal-title">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-title"><?= __('Editar sugerencia de categor&iacute;a') ?></h4>
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
        
        $('#frmCategorias').submit();
      }
    });
    
    
    /*editar una categoria*/
    $('.editar-cat').click(function(){
      var idFull = $(this).attr('id').split('-');
      var id = idFull[0];
      
      $.ajax({
        url: "<?= \Cake\Routing\Router::url(['prefix'=>'admin','controller'=>'NegociosSucursales',
                'action'=>'getSugerenciaCategoriaEditar']); ?>/" + id,
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
              'action'=>'editarSugerenciaCategoria']) ?>",
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