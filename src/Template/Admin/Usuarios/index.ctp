<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3>
        <?= __('Usuarios') ?>
        <?php
        echo $this->Html->link('<i class="fa fa-plus"></i> ' . __('Nuevo'), [
          'prefix' => 'admin',
          'controller' => 'usuarios',
          'action' => 'nuevo'
          ], [
          'class' => 'btn btn-default btn-sm btn-header',
          'escape' => false           
        ]);
        ?>
      </h3>       
    </div>
  </div>  
  <div class="row">     
    <div class="col-xs-12" >
      <?php
      ($rol_id==-1) ? $tag='b' : $tag='';
      echo $this->Html->link($this->Html->tag($tag, __('Todos').' <span class="badge">'.$usuariosTotal.'</span>',['style'=>'color:black;']),[
        'prefix'=>'admin',
        'controller'=>'usuarios',
        'action'=>'index',
        $limite, -1, $txtUsuario
      ],[        
        'escape'=>false
      ]); 
      
      
      
      foreach ($countRoles as $rol) {
        echo ' | ';

        ($rol_id==$rol->id) ? $tag='b' : $tag='';
        echo $this->Html->link($this->Html->tag($tag, $rol->nombre . ' <span class="badge">'.count($rol->usuarios).'</span>',['style'=>'color:black;']), [
            'prefix' => 'admin',
            'controller' => 'usuarios',
            'action' => 'index',
            $limite, $rol->id, $txtUsuario
          ], [
            'escape' => false
          ]);
      }
      ?>
    </div>  
  </div>
  
  <?php
  echo $this->Form->create('Usuario', ['class' => 'form-inline', 'id'=>'frmUsuarios']);
  ?>
  
  <!-- Modal de suspension -->
  <div class="modal fade" id="modal-suspension" tabindex="-1" role="dialog" aria-labelledby="modal-title">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-title"><?= __('Suspensi&oacute;n de usuario') ?></h4>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-xs-12">
                <label><?= __('Raz&oacute;n de suspensi&oacute;n') ?></label>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <?php
                echo $this->Form->textarea('razon_suspension', [
                  'id' => 'razon_suspension',
                  'class' => 'form-control',
                  'cols'=>25
                ]);
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <label style="margin-top: 10px;"><?= __('Suspender hasta') ?></label>
              </div>
            </div>
            <div class="row">
              <div id="div-fecha-suspension" class="col-xs-12">
                <?php            
                /*echo $this->Form->select('fecha_termina_suspension', $opcionesFecha, [
                  'class' => 'form-control',
                  'escape'=>false
                ])*/
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
      $arrayAcciones[2] = __('Suspender');
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

      <?= $this->Form->select('rol_id', $listaRoles,[
        'id'=>'selRol',
        'empty'=>'Roles',
        'default'=>'',
        'class'=>'form-control usuario-rol-id',
      ]); ?>
      
      <?= $this->Form->submit(__('Aplicar rol'),[
        'id'=>'btnAplicarRol',
        'name'=>'btnAplicarRol',
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
              Negocios
            </th>
            <th>
              <?php
              echo $this->Paginator->sort('Roles.nombre', 'Rol');
              ?>
            </th>     
            <th>
              <?php
              echo $this->Paginator->sort('Usuarios.fecha_registro', 'Fecha de registro');
              ?>
            </th> 
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
            <td style="word-break: break-all;"><?= $usuario->correo ?></td>
            <td>              
              <?php 
              $i=0;
              foreach ($usuario['negocios'] as $neg){ 
                $i++;
                if($i>2){
                  echo $this->Html->link(__("[Ver todos]"), [
                    'prefix'=>'admin',
                    'controller'=>'Usuarios',
                    'action'=>'editar', $usuario->id
                  ]);
                  break;
                }else{
                  echo "<div>".$neg['nombre']."</div>";
                }
              }
              ?>
            </td>
            <td><?= $usuario->rol->nombre ?></td>
            <td style="min-width: 150px;"><?= $usuario->fechaRegistroFormat ?></td>
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