<!DOCTYPE html>
<html>
  <head>
    <?= $this->Html->charset() ?>
    <?= $this->Html->css(array('admin/bootstrap.min', 'error')); ?>
    <title>   
      <?= $this->fetch('title') ?>
      <?php 
      $config = \Cake\Core\Configure::read('config'); 
      if (isset($config['sitio_nombre_secundario'])){ echo ' - '.$config['sitio_nombre_secundario']; }else{ echo '';} 
      ?>
    </title>    
  </head>
  <body>   
    <div class="container">
      <div id="error-header" class="row">
        <div class="col-xs-12 text-center">
          <h1 id="title">Faindit</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 col-lg-offset-3 col-lg-6"> 
          <?= $this->Flash->render() ?>
          <?= $this->fetch('content') ?>  
        </div>
      </div>
    </div>
  </body>
</html>
