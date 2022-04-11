<!DOCTYPE html>
<html>
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
      <?php 
      echo __('Login Administraci&oacute;n').' | '; 
      if (isset($config['sitio_nombre'])){ echo $config['sitio_nombre']; }else{ echo '';} 
      ?>
    </title>
		<?= $this->Html->meta('icon') ?>
    
    <?php
    echo $this->Html->css(array('admin/bootstrap.min', 'admin/style', 'font-awesome.min'));
    echo $this->Html->script(array('jquery-1.11.3.min', 'bootstrap.min', 'admin/js'));
    ?>    
  </head>
  <body> 
    <?= $this->Flash->render() ?>          
    <?= $this->fetch('content') ?>
  </body>
</html>
