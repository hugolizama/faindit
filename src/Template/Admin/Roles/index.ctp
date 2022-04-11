<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($roles); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3>
        <?= __('Roles') ?>
        <?php
        echo $this->Html->link('<i class="fa fa-plus"></i> ' . __('Nuevo'), [
          'prefix' => 'admin',
          'controller' => 'roles',
          'action' => 'nuevo'
          ], [
          'class' => 'btn btn-default btn-sm btn-header',
          'escape' => false
        ]);
        ?>
      </h3>       
    </div>
  </div>
  
  <?= $this->Form->create('Rol',['class'=>'form-inline']); ?>
  <div class="row form-group-sm">
    <div class="col-xs-12">
      <?= $this->Form->select('accion', [
        1=>__('Eliminar')
      ],[
        'id'=>'selAccion',
        'empty'=>'Acciones',
        'default'=>'',
        'class'=>'form-control'
      ]); ?>
      <?= $this->Form->submit('Aplicar',[
        'id'=>'btnAplicar',
        'name'=>'btnAplicar',
        'class'=>'btn btn-default btn-sm',
        'disabled'=>true
      ]); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 40px;"><input id="selectall" type="checkbox"></th>
            <th style="width: 40px;"></th>
            <th><?= __('Rol') ?></th>
            <th><?= __('Descripci&oacute;n') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($roles as $rol): ?>
          <tr>
            <td>
              <?= $this->Form->checkbox('seleccion[]', [
                'class'=>'selectall',
                'hiddenField' => false,
                'value'=>$rol->id
              ]) ?>
            </td>
            <td>
              <?= $this->Html->link('<span class="fa fa-edit fa-accion"></span>',[
                'prefix'=>'admin',
                'controller'=>'roles',
                'action'=>'editar',
                $rol->id
              ],[
                'escape'=>false,
                'title'=>__('Editar')
              ]); ?>
            </td>
            <td>
              <?= $this->Html->link($rol->nombre,[
                'prefix'=>'admin',
                'controller'=>'roles',
                'action'=>'ver',
                $rol->id
              ],[
                'escape'=>false,
                'title'=>__('Editar')
              ]); ?>
            </td>
            <td>
              <?= $rol->descripcion ?>
            </td>
          </tr>
          <?php endforeach; ?>          
        </tbody>
      </table>
    </div>
  </div>
  <?= $this->Form->end(); ?>
</div>
  
<script>
  $(document).ready(function(){
    $('#selAccion').change(function(){
      var accion = $(this).val();
      if (accion !==''){
        $('#btnAplicar').attr('disabled',false);
      }else{
        $('#btnAplicar').attr('disabled',true);
      }
    });
    
    
    $('#selectall').on('click', function () {          
      var checked_status = this.checked;

      $(".selectall").each(function () {
        this.checked = checked_status;
      });
    });
  });
</script>    


