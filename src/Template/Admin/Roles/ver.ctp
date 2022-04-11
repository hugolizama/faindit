<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($rol); ?>
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
        <?= __('Permisos del rol') ?>       
      </h3> 
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-6 col-lg-4">
      <b><?= $rol['nombre'] ?></b>
      <?php       
      if(isset($perRol['administrar_roles']) && $perRol['administrar_roles']==1){
        echo $this->Html->link('<span class="fa fa-edit fa-movil"></span>', array(
          'prefix'=>'admin',
          'controller'=>'roles',
          'action'=>'editar',
          $rol_id
        ), array(
          'escape'=>false,
          'title'=>__('Editar')
        ));
      }
      ?>
    </div>	
  </div>  
  
  <div class="row">
    <div class="col-md-6 col-lg-4">
      <?= $rol['descripcion'] ?>
    </div>	
  </div> 

  <div class="row" style="margin-top: 20px;">
    <div class="col-xs-12">
      <label><?= __('Permisos asignados a este rol') ?></label>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div id="tabs" class="ver-permisos">
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
                  <th><?= __('Permiso') ?></th>                   
                  <th style="width: 100px;"><?= __('Si/No') ?></th>
                </tr>
              </thead>
              <tbody>
                
                <?php foreach ($grupo['permisos'] as $per_llave => $per): /*imprimir los permisos individuales*/ ?>
                <tr>                                    
                  <td><label for="<?= $per_llave ?>"><?= $per ?></label></td>                   
                  <td class="permiso-<?= (isset($rol[$per_llave])) ? $rol[$per_llave]: 0; ?>"></td>                  
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

</div>
<script>
  $(document).ready(function(){
    $( "#tabs" ).tabs();
  });
</script>