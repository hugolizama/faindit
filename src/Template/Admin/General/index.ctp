<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3>
        <?= __('Estad&iacute;sticas') ?> 
        <?php
        echo $this->Html->link('[M&aacute;s estad&iacute;sticas]', array(
          'action'=>'masEstadisticas', 'dias'
        ), array('escape'=>false, 'style'=>'font-size: 14px;'));
        ?>
      </h3>  
    </div>
  </div>
  
  <div id="general_estadisticas" class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
          <tr>
            <th><?= __('Estad&iacute;stica') ?></th>
            <th><?= __('Valor') ?></th>
            <th><?= __('Estad&iacute;stica') ?></th>
            <th><?= __('Valor') ?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th><?= __('Versi&oacute;n de php') ?></th>
            <td><?= phpversion(); ?></td>
            <th><?= __('Versi&oacute;n de Mysql') ?></th>
            <td><?= $bd_version; ?></td>
          </tr>
          
          <tr>
            <th><?= __('Tama&ntilde;o de base de datos') ?></th>
            <td><?= $bd_tamanio ?></td>
            <th><?= __('Categor&iacute;as esperando aprobaci&oacute;n') ?></th>
            <td><?= $cant_categorias['cant'] ?></td>
          </tr>
          
          <tr>
            <th><?= __('Cantidad de usuarios') ?></th>
            <td><?= number_format($cant_usuarios['cant']) ?></td>
            <th><?= __('Cantidad de negocios') ?></th>
            <td><?= $cant_negocios['cant'] ?></td>
          </tr>
          
          <tr>
            <th><?= __('Cantidad de sucursales') ?></th>
            <td><?= $cant_sucursales_habilitadas['cant'] ?> / <?= $cant_sucursales['cant'] ?></td>
            <th><?= __('Fecha de inicio') ?></th>
            <td><?= $config['sitio_fecha_inicio'] ?></td>
            
          </tr>
          
          <tr>
            <th><?= __('Cantidad de b&uacute;squedas') ?></th>
            <td><?= number_format($cant_busquedas['suma']) ?></td>
            <th><?= __('Promedio de b&uacute;squedas diaria') ?></th>
            <td><?= $promedio ?></td>            
          </tr>
          
          <tr>
            <th><?= __('Fecha con m&aacute;s b&uacute;squedas') ?></th>
            <td><?= $max_busquedas['fecha'] ?></td>
            <th><?= __('M&aacute;xima cantidad de b&uacute;squedas') ?></th>
            <td><?= number_format($max_busquedas['max']) ?></td>
          </tr>
          
          <tr>            
            <th><?= __('Busquedas hoy: ').  date('d-m-Y'); ?></th>
            <td><?= $cant_busquedas_hoy == false ? 0 : (int) $cant_busquedas_hoy['contador'] ?></td>
            <th><?= __('Tama&ntilde;o de carpeta de im&aacute;genes') ?></th>
            <td><?= $tamanioImg ?></td>
          </tr>
          
        </tbody>
      </table>
    </div>
  </div>
  
</div>
