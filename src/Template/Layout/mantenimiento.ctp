<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebPage">
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="m8W-g4vXmv8cWhiLYuzj57l-miev13Aa9Ne1SIHSXVw" />
		<title>
      <?php 
      echo (isset($titulo)) ? $titulo.' | Directorio ':'Mantenimiento '.' | Directorio '; 
      if (isset($nombre_sitio->valor)){ echo $nombre_sitio->valor." El Salvador"; }else{ echo 'Faindit El Salvador';} 
      ?>
    </title>
		<?= $this->Html->meta('icon') ?>
    <?php
    echo $this->Html->css(array('bootstrap.min', 'font-awesome.min', 'custom'));
    ?>	    
	</head>
	<body>    
		<div class="container-fluid background-orange">
      <div class="container">
        <div class="row" style="padding-top: 10px; padding-bottom: 10px;">
          <div class="col-xs-12 text-center">
            <h1 id="title" style="">
              <?php if (isset($nombre_sitio->valor)){ echo $nombre_sitio->valor; }else{ echo 'Faindit';}  ?>
            </h1>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container" style="position: relative;">
      <?= $this->Flash->render() ?>
    </div>    
    <?= $this->fetch('content') ?>
	</body>
</html>
