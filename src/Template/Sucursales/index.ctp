<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col-xs-12"><h2 class="page-header"><?= __('Sucursales de ') ?> <?= $negocio['nombre'] ?></h2></div>
    </div>

    <div class="row">
      <div class="col-sm-3">
        <?= $this->element('menu-perfil'); ?>
      </div>
      <div class="col-sm-9 frm-sm">
        <?php echo $this->Form->create($sucursalEntity,['id'=>'frmSucursales', 'class'=>'form-horizontal']); ?>
        <div class="row">
          <div class="col-xs-12">
            <?= $this->element('menu-sucursales'); ?>
          </div>
        </div>
          
        <div class="row form-group-sm">
          <div class="col-xs-12 text-right">
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
        
        <div class="row" style="margin-bottom: 20px;">
          <div class="col-xs-12">              
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th style="width: 30px;"><input id="selectall" type="checkbox"></th>
                      <th style="width: 80px;">Acci&oacute;n</th>
                      <th>
                        <?php
                        echo $this->Paginator->sort('Sucursales.nombre', 'Sucursal', array(
                          'direction' => 'desc'
                        ));
                        ?>
                      </th>
                      <th>
                        <?php
                        echo $this->Paginator->sort('Paises.nombre', 'Pa&iacute;s', array(
                          'escape'=>false,
                          'direction' => 'desc'
                        ));
                        ?>
                      </th>
                      <th>
                        <?php
                        echo $this->Paginator->sort('Departamentos.nombre', 'Departamento', array(
                          'escape'=>false,
                          'direction' => 'desc'
                        ));
                        ?>
                      </th>
                      <th>
                        <?php
                        echo $this->Paginator->sort('Municipios.nombre', 'Municipio', array(
                          'escape'=>false,
                          'direction' => 'desc'
                        ));
                        ?>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($sucursales as $sucursal): ?>
                    <tr class="<?= ($sucursal['habilitado']==0) ? 'reg-deshabilitado' : ''; ?>">
                      <td>
                        <?=
                        $this->Form->checkbox('seleccion[]', [
                          'class' => 'selectall',
                          'hiddenField' => false,
                          'value' => $sucursal['id']
                        ])
                        ?>
                      </td>
                      <td>
                        <?= $this->Html->link('<span class="fa fa-edit fa-accion"></span>',[
                          'controller'=>'Sucursales',
                          'action'=>'editar',
                          $sucursal['id'],
                          $tokenFalso
                        ],[
                          'escape'=>false,
                          'title'=>__('Editar Sucursal')
                        ]); ?>                        
                        <?= $this->Html->link('<span class="fa fa-share fa-accion"></span>',[
                          'controller'=>'N',
                          'action'=>'index',
                          $sucursal['id'],
                          $sucursal['negocio']['nombre_slug']
                        ],[
                          'escape'=>false,
                          'title'=>__('Ver Sucursal'),
                          
                        ]); ?>
                      </td>
                      <td><?= $sucursal['nombre'] ?></td>
                      <td><?= $sucursal['pais']['nombre'] ?></td>
                      <td><?= $sucursal['departamento']['nombre'] ?></td>
                      <td><?= $sucursal['municipio']['nombre'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>          
        </div> 
        
        <div class="row form-group-sm">
          <div class="col-xs-12">
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
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){ 
    
    $('#selectall').on('click', function () {
      /*verificar el estado de ese checkbox si esta marcado o no*/
      var checked_status = this.checked;

      /*
       * asignarle ese estatus a cada uno de los checkbox
       * que tengan la clase "selectall"
       */
      $(".selectall").each(function () {
          this.checked = checked_status;
      });
    });
    
    /*limite de objetos por pagina*/
    $('#limite').change(function(){
      $('#accion').val('');
      var limite = $(this).val();
      
      $('#frmSucursales').submit();
    });
    
    $('#selAccion').change(function(){      
      var accion_id=$(this).val(); 
      var btnAplicar = $('#btnAplicar');
      
      if(accion_id!=''){
        btnAplicar.attr('disabled',false);
      }else{
        btnAplicar.attr('disabled',true);
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
        $('#frmSucursales').submit();
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
        $('#frmSucursales').submit();
      }
    });    
    /*fin control de paginacion*/
    
  });
</script>