<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Usuarios inactivos. (Total '.$countUsuarios.')') ?></h3>       
    </div>
  </div>    
  <?php
  echo $this->Form->create('Usuario', ['class' => 'form-inline', 'id'=>'frmUsuarios']);
  ?>
  
  <div class="row form-group-sm">  
    <div class="col-xs-12">
      <label class="control-label"><?= __('Ver') ?></label>
      <?= $this->Form->select('limite', $verOpciones,[
        'id'=>'limite',
        'default'=>$limite,
        'class'=>'form-control'
      ]); ?>
      
      <?php 
      $arrayAcciones[1] = __('Activar');
      /*verificar permiso para eliminar usuarios*/
      if(isset($perRol['eliminar_usuarios']) && $perRol['eliminar_usuarios']==1){
        $arrayAcciones[3] = __('Eliminar');
      } 
      ?> 
      
      <?= $this->Form->select('accion', $arrayAcciones,[
        'id'=>'selAccion',
        'empty'=>'Acciones',
        'default'=>'',
        'class'=>'form-control accion-select'
      ]); ?>
      
      <?= $this->Form->submit(__('Aplicar'),[
        'id'=>'btnAplicarAccion',
        'name'=>'btnAplicarAccion',
        'class'=>'btn btn-default btn-sm btn-margin-right',
        'disabled'=>true
      ]); ?>
           
      <?= $this->Form->input('txtUsuario',[
        'div'=>false,
        'label'=>false,
        'class'=>'form-control',
        'placeholder'=>__('Usuario o correo'),
        'value'=>$txtUsuario
      ]); ?>
      
      <?= $this->Form->submit(__('Buscar usuario'),[
        'id'=>'btnBuscarUsuario',
        'name'=>'btnBuscarUsuario',
        'class'=>'btn btn-default btn-sm btn-margin-right'
      ]); ?>
      
      <nav class="pagination-top">    
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
            <th style="width: 40px;"></th>
            <th>
              <?php
              echo $this->Paginator->sort('Usuarios.usuario', 'Nombre de usuario', array(
                'direction' => 'asc'
              ));
              ?>
            </th>    
            <th>
              <?php
              echo $this->Paginator->sort('Usuarios.correo', 'Correo');
              ?>
            </th>                
            <th>
              <?php
              echo $this->Paginator->sort('Usuarios.fecha_registro', 'Fecha de registro');
              ?>
            </th> 
            <th><?= __('Tiempo transcurrido') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($usuarios as $usuario): ?>
          <tr>
            <td>
              <?=
              $this->Form->checkbox('seleccion[]', [
                'class' => 'selectall',
                'hiddenField'=>false,
                'value' => $usuario->id
              ])
              ?>
            </td>
            <td>
              <?=
              $this->Html->link('<span class="fa fa-edit fa-accion"></span>', [
                'prefix' => 'admin',
                'controller' => 'usuarios',
                'action' => 'editar',
                $usuario->id
                ], [
                'escape' => false
              ]);
              ?>
            </td>
            <td>
              <?=
              $this->Html->link($usuario->usuario, [
                'prefix' => 'admin',
                'controller' => 'usuarios',
                'action' => 'editar',
                $usuario->id
                ], [
                'escape' => false
              ]);
              ?>
            </td>
            <td><?= $usuario->correo ?></td>                       
            <td><?= $usuario->fechaRegistroFormat ?></td>
            <td><?= $usuario->tiempoTranscurrido ?></td>
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
        'empty'=>'Acciones',
        'default'=>'',
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
      
      $('#frmUsuarios').submit();
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
    
    /*aplicar rol*/
    $('#selRol').change(function(){
      var accion = $(this).val();
      if (accion !== ''){
        $('#btnAplicarRol').attr('disabled',false);
      }else{
        $('#btnAplicarRol').attr('disabled',true);
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
        $('#frmUsuarios').submit();
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
        $('#frmUsuarios').submit();
      }
    });    
    /*fin control de paginacion*/
    
    
    $('#txtusuario').keypress(function (e){
      tecla = (document.all) ? e.keyCode : e.which; /*capturar tecla*/
            
      if(tecla===13){        
        $('#btnBuscarUsuario').click();
      }
    });
  });
</script>