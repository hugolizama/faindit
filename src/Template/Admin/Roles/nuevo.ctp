<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3>
        <?= $this->Html->link(__('Roles'),[
          'prefix'=>'admin',
          'controller'=>'roles',
          'action'=>'index'
        ]); ?> / 
        <?= __('Nuevo rol') ?>       
      </h3> 
    </div>
  </div>

  <?php echo $this->Form->create($rolesEntity, ['class' => 'form-horizontal']); ?>
  <div class="form-group">
    <div class="col-md-6 col-lg-4">
      <?php
      echo $this->Form->input('nombre', [
        'div' => false,
        'label' => false,
        'placeholder' => __('Nombre (requerido)'),
        'class' => 'form-control',
        'autofocus' => true
      ]);
      ?>
    </div>	
  </div>  
  
  <div class="form-group">
    <div class="col-md-6 col-lg-4">
      <?php
      echo $this->Form->input('descripcion', [
        'div' => false,
        'label' => false,
        'placeholder' => __('Descripción (requerido)'),
        'class' => 'form-control'
      ]);
      ?>
    </div>	
  </div> 

  <div class="form-group">
    <div class="col-xs-12">
      <label><?= __('Seleccione los permisos para este rol') ?></label>
    </div>
  </div>

  <div class="form-group">
    <div class="col-xs-12">
      <div id="tabs">
        <ul>
          <?php foreach ($permisos_array as $grupo_llave => $grupo): /*mostrar los nombres de los grupos de permisos*/ ?>
          <li><a href="#tabs-<?= $grupo_llave; ?>"><?= $grupo['nombre']; ?></a></li>
          <?php endforeach; ?>
        </ul>
        
        
        <?php foreach ($permisos_array as $grupo_llave => $grupo): /*recorrer los grupos de permisos*/ ?>
        <div id="tabs-<?= $grupo_llave; ?>">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th style="width: 40px;">        
                      <input id="per_<?= $grupo_llave; ?>" class="selectPermisos" data-target='<?= $grupo_llave; ?>'  type="checkbox">                    
                  </th>                  
                  <th><?php echo __('Permiso') ?></th>            
                </tr>
              </thead>
              <tbody>
                
                <?php foreach ($grupo['permisos'] as $per_llave => $per): /*imprimir los permisos individuales*/ ?>
                <tr>
                  <td>                        
                    <?php                    
                    echo $this->Form->input('permisos.'.$per_llave, array(
                      'type'=>'checkbox',
                      'id'=>$per_llave,
                      'class' => 'select_'.$grupo_llave,
                      'label'=>'',
                      'div'=>false,
                      'escape'=>false
                    ));
                    ?>
                  </td>                  
                  <td><label for="<?= $per_llave ?>"><?= $per ?></label></td>            
                </tr>
                <?php endforeach; ?>
                
              </tbody>
            </table>
          </div>
        </div>
        <?php endforeach; ?>
        
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-xs-12">
      <?php
      echo $this->Form->button(__('Guardar'), [
        'type' => 'submit',
        'class' => 'btn btn-md btn-success btn-margin-right',
      ]);


      echo $this->Html->link(__('Cancelar'), array(
        'prefix' => 'admin',
        'controller' => 'roles',
        'action' => 'index'
        ), array(
        'div' => false,
        'class' => 'btn btn-md btn-danger',
      ));
      ?>
    </div>      
  </div>   

  <?php echo $this->Form->end(); ?>
</div>

<script>
  $(document).ready(function(){
    $( "#tabs" ).tabs();
    
    
    $('.selectPermisos').on('click', function () { 
      var target = $(this).attr('data-target');
      var checked_status = this.checked;

      $(".select_" + target).each(function () {
        this.checked = checked_status;
      });
    });
  });
</script>