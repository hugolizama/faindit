<!DOCTYPE html>
<html>
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      <?php 
      echo __('Administraci&oacute;n').' | '; 
      if (isset($config['sitio_nombre'])){ echo $config['sitio_nombre']; }else{ echo '';} 
      ?>
    </title>
		<?= $this->Html->meta('icon') ?>      
  
    <?php
    echo $this->Html->css(array('admin/bootstrap.min', 'admin/style', 'font-awesome.min'));
    echo $this->Html->script(array('jquery-1.11.3.min', 'bootstrap.min', 'admin/js')); 
    if(isset($jsupload)){
      echo $this->Html->script(array('jquery-upload/jquery.form.min','jquery-upload/jquery.uploadfile')); 
      echo $this->Html->css(array('jquery-upload/uploadfile'));
    } 
    if(isset($jquery_ui)){
      echo $this->Html->script(array('jquery-ui/jquery-ui.min','jquery-ui/jquery.ui.touch-punch.min')); 
      echo $this->Html->css(array('jquery-ui/jquery-ui.theme.min','jquery-ui/jquery-ui.structure.min','jquery-ui/jquery-ui.min'));
    }    
    if(isset($jquery_tags)){
      echo $this->Html->css(array('jquery-tags/bootstrap-tagsinput', 'jquery-tags/app.css'));
      echo $this->Html->script(array('jquery-tags/typeahead.bundle.min','jquery-tags/bootstrap-tagsinput.min'));
    }
    if(isset($jquery_mask)){
      echo $this->Html->script(array('jquery.mask.min'));
    }
    if(isset($mapa_nuevo)){
      echo $this->Html->css(array('mapa-nuevo'));
    }
    if(isset($amcharts)){
      echo $this->Html->script(array('amcharts/amcharts','amcharts/serial', 'amcharts/themes/light')); 
    } 
    ?>      
  </head>
  <body>
    <div id="wrapper" style="margin-bottom: 40px;">
        <?= $this->element('Admin/menu'); ?>

      <div id="page-wrapper">   
        <?php if(isset($config['mantenimiento_activar']) && $config['mantenimiento_activar']==1): ?>
        <div class="alert alert-warning text-center alert-mantenimiento" role="alert">            
            <strong><?= $config['mantenimiento_mensaje'] ?></strong>
          </div>
        <?php endif; ?>
        <?php if(isset($config['deshabilitar_sitio']) && $config['deshabilitar_sitio']==1): ?>
          <div class="alert alert-warning text-center" role="alert">            
            <strong><?= __('AVISO: Sitio web deshabilitado') ?></strong>
          </div>
        <?php endif; ?>          
        <?= $this->Flash->render() ?>          
        <?= $this->fetch('content') ?>
      </div>

    </div>
  </body>
</html>
