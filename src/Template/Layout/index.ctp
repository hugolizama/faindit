<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebPage">
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--<link rel="alternate" hreflang="es" href="http://www.faindit.com/" />-->
    <meta name="google-site-verification" content="m8W-g4vXmv8cWhiLYuzj57l-miev13Aa9Ne1SIHSXVw" />
    <meta name="description" content="<?= $config['sitio_descripcion'] ?>" />
    
		<title>
      <?php 
      if (isset($config['sitio_nombre'])){ echo $config['sitio_nombre']. " El Salvador - Directorio de negocios"; }else{ echo '';} 
      ?>
    </title>
		<?= $this->Html->meta('icon') ?>
		<?php 
    echo $this->Html->css(array('bootstrap.min', 'font-awesome.min', 'custom')); 
    echo $this->Html->script(array('jquery-1.11.3.min'));
    if(isset($jquery_ui)){
      echo $this->Html->css(array('jquery-ui/jquery-ui.min'));
    }
    ?> 
    
    <!-- facebook -->
    <meta property="og:url"           content="<?= \Cake\Routing\Router::url(['controller'=>'Principal', 'action'=>'index'], true) ?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="Directorio <?= $config['sitio_nombre']." El Salvador" ?>" />
    <meta property="og:description"   content="<?= $config['sitio_descripcion'] ?>" />        
    <meta property="og:image"         content="<?= \Cake\Routing\Router::url('/img/faindit_logo.jpg', true) ?>" />
    <!-- google+ -->
    <meta itemprop="name" content="Directorio <?= $config['sitio_nombre']. " El Salvador" ?>" />
    <meta itemprop="description" content="<?= $config['sitio_descripcion'] ?>" />
    <meta itemprop="image" content="<?= \Cake\Routing\Router::url('/img/faindit_logo.jpg', true) ?>" />
    
    <!-- Facebook Pixel Code 
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','//connect.facebook.net/en_US/fbevents.js');

    fbq('init', '1702679449986097');
    fbq('track', "PageView");</script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1702679449986097&ev=PageView&noscript=1"
    /></noscript>
    End Facebook Pixel Code -->
	</head>
	<body>
    
    <?php if(isset($config['mantenimiento_activar']) && $config['mantenimiento_activar']==1): ?>
    <div class="alert alert-warning text-center alert-mantenimiento" role="alert">            
        <strong><?= $config['mantenimiento_mensaje'] ?></strong>
      </div>
    <?php endif; ?>
    
		<?= $this->element('header'); ?>
    <?= $this->element('menu'); ?>
		
    <?php if(isset($config['deshabilitar_sitio']) && $config['deshabilitar_sitio']==1): ?>
      <div class="alert alert-warning text-center" role="alert">            
        <strong><?= __('AVISO: Sitio web deshabilitado') ?></strong>
      </div>
    <?php endif; ?> 
    
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
    
    <?= $this->element('footer'); ?>
    
    <?php 
    echo $this->Html->script(array('bootstrap.min')); 
    if(isset($jquery_ui)){
      echo $this->Html->script(array('jquery-ui/jquery-ui.min'/*,'jquery-ui/jquery.ui.touch-punch.min'*/)); 
    }
    ?>
    
    <?php  if(isset($js_buscador) && $js_buscador == 1): ?>
    <script>
      var urlQue = '<?= $urlQue; ?>';
      var urlEn = '<?= $urlEn; ?>';
    </script>
    <?php echo $this->Html->script(array('buscador'));
    endif;
    ?>
	</body>
</html>
