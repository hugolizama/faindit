<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($notificacionesUsuarios); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Notificaciones de usuarios. (Total '.$countNotificacionesUsuarios.')') ?></h3>       
    </div>
  </div>  
    
  <?php
  echo $this->Form->create('Usuario', ['class' => 'form-inline', 'id'=>'frmUsuarios']);
  ?>
  
  <div class="row">
    <div class="col-xs-12">
      Usuarios sin negocio <span class="badge"><?= $countUsuariosSinNegocio; ?></span> | 
      Usuarios sin activar <span class="badge"><?= $countUsuariosSinActivar; ?></span>    
    </div>
  </div> 
  
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <tr>            
            <th>
              <?php
              echo $this->Paginator->sort('Usuarios.usuario', 'Nombre de usuario', array(
                'direction' => 'asc'
              ));
              ?>
            </th>  
            <th>
              <?php
              echo $this->Paginator->sort('tipo_notificacion', 'Tipo notificacion');
              ?>
            </th> 
            <th>
              <?php
              echo $this->Paginator->sort('Usuarios.correo', 'Correo');
              ?>
            </th> 
            <th>
              <?php
              echo $this->Paginator->sort('fecha_registro', 'Fecha registro');
              ?>
            </th>                
            <th>
              <?php
              echo $this->Paginator->sort('fecha_insert', 'Fecha Insert');
              ?>
            </th> 
          </tr>
        </thead>
        <tbody>
          <?php foreach($notificacionesUsuarios as $notificacion): ?>
          <tr>   
            <td>
              <?=
              $this->Html->link($notificacion->usuario->usuario, [
                'prefix' => 'admin',
                'controller' => 'usuarios',
                'action' => 'editar',
               $notificacion->usuario->id
                ], [
                'escape' => false
              ]);
              ?>
            </td>            
            <td><?= $notificacion->tipo_notificacion ?></td>
            <td><?= $notificacion->usuario->correo ?></td>
            <td><?= $notificacion->usuario->fechaRegistroFormat ?></td>
            <td><?= $notificacion->fechaInsertFormat ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>    
  </div>  
  <?php echo $this->Form->end(); ?>
</div>

<script>
  $(document).ready(function () {
    
    function mostrar_modal(e){
      if($('#selAccion').val()==2 || $('#selAccion2').val()==2){
        e.preventDefault();
        
        $.ajax({
          url: "<?= \Cake\Routing\Router::url(['prefix'=>'admin', 'controller'=>'Usuarios', 'action'=>'getFechasSuspension']) ?>",
          type: 'POST',
          dataType: 'html'
        }).done(function(data){
          $('#div-fecha-suspension').html(data);
          $('#modal-suspension').modal('show');
        });    
      } 
    }    
    
    $('#btnAplicarAccion').click(function(e){
      mostrar_modal(e);      
    });
    
    $('#btnAplicarAccion2').click(function(e){
      mostrar_modal(e);      
    });
    
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