<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Excluir nombres de usuario') ?></h3>       
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12 text-justify">
      <?= __('La siguientes exclusiones evitar&aacute;n el registro y utilizaci&oacute;n de nombres de usuario. El uso de * 
        indica un comod&iacute;n para generalizar una exclusi&oacute;n, ej.: micro*') ?>       
    </div>
  </div>
  
  <?php
  echo $this->Form->create($exclusion, array(
    'class' => 'form-inline',
    'autocomplete' => 'off'
  ));
  ?>
  
  <div class="row form-group-sm">
    <div class="col-xs-12">
      <label class="control-label required">
        <?= __('Usuario') ?>
      </label>

       <?php
      echo $this->Form->input('valor', array(
        'id'=>'txtValor',
        'class' => 'form-control',
        'div' => false,
        'label' => false,
      ));
      ?>

      <?php
      echo $this->Form->submit('Guardar', [
        'class'=>'btn btn-primary btn-sm'
      ]);
      ?> 
    </div>       
  </div>
  
  <?php echo $this->Form->end(); ?>
  
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th style="width: 40px;"><?= __('Acci&oacute;n') ?></th>
            <th><?= __('Valor') ?></th>
            <th><?= __('Fecha de creaci&oacute;n') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($exclusiones as $exclusion): ?>
          <tr>
            <td>
              <?=
              $this->Form->postlink('<span class="fa fa-times fa-accion"></span>', [
                'prefix' => 'admin',
                'controller' => 'usuarios',
                'action' => 'eliminarExclusion',
                $exclusion->id
                ], [
                  'escape' => false,
                  'title'=>__('Eliminar'),
                  'confirm' => __('??Confirma eliminar esta exclusi??n?')
              ]);
              ?>
            </td>
            <td><?= $exclusion->valor; ?></td>
            <td><?= $exclusion->fecha_creacion; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  
</div>