<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebPage">
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <?= $this->Html->meta('icon') ?>
		<title>
      <?php 
      echo $titulo.' | Directorio '; 
      if (isset($config['sitio_nombre'])){ echo $config['sitio_nombre']." El Salvador"; }else{ echo '';} 
      ?>
    </title>		
    
    <?php if(isset($no_cache)): ?>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <?php endif; ?>
    
    <?php
    echo $this->Html->css(array('bootstrap.min', 'font-awesome.min', 'custom'));
    echo $this->Html->script(array('jquery-1.11.3.min', 'bootstrap.min'));
    
    if(isset($buscar_sidebar)){
      echo $this->Html->css(array('buscar-sidebar'));      
    }     
    
    if(isset($jquery_tags)){
      echo $this->Html->css(array('jquery-tags/bootstrap-tagsinput', 'jquery-tags/app.css'));
      echo $this->Html->script(array('jquery-tags/typeahead.bundle.min','jquery-tags/bootstrap-tagsinput.min'));
    }
    if(isset($photoswipe)){      
      echo $this->Html->css(array('photoswipe/photoswipe', 'photoswipe/default-skin/default-skin')); 
      echo $this->Html->script(array('photoswipe/photoswipe.min', 'photoswipe/photoswipe-ui-default.min'));
    }
    if(isset($jquery_mask)){
      echo $this->Html->script(array('jquery.mask.min'));
    }
    if(isset($mapa_nuevo)){
      echo $this->Html->css(array('mapa-nuevo'));
    }
    if(isset($jsupload)){
      echo $this->Html->script(array('jquery-upload/jquery.form.min','jquery-upload/jquery.uploadfile')); 
      echo $this->Html->css(array('jquery-upload/uploadfile'));
    }    
    if(isset($jquery_ui)){
      echo $this->Html->css(array('jquery-ui/jquery-ui.min'));
      echo $this->Html->script(array('jquery-ui/jquery-ui.min','jquery-ui/jquery.ui.touch-punch.min'));       
    }
    
    $meta_description = $config['sitio_descripcion'];
    ?>    
    
    <?php if(isset($meta_busqueda)): /*aparece en busquedas */
      /*mensaje para busqueda con resultados en el descriptor para facebook y google plus*/
      $mensaje_descripcion = '';
      if(!empty($resultado)){
        $texto_en = '';

        if($en!=''){
          $texto_en = "en $en";
        }

        $mensaje_descripcion = __("Resultados para: $que $texto_en");
      }
      
      $meta_description = $mensaje_descripcion;
    ?>
    <meta itemprop="name" content="<?= $que ?>">
    
    <!-- facebook -->
    <meta property="og:url"           content="<?= \Cake\Routing\Router::url(['controller'=>'Principal', 'action'=>'buscar', '?'=>$this->request->query], true) ?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="<?= $config['sitio_nombre']." El Salvador" ?>" />
    <meta property="og:description"   content="<?= $mensaje_descripcion ?>" />        
    <meta property="og:image"         content="<?= \Cake\Routing\Router::url('/img/faindit_logo.jpg', true) ?>" />
    <!-- google+ -->
    <meta itemprop="name" content="<?= $config['sitio_nombre']." El Salvador" ?>" />
    <meta itemprop="description" content="<?= $mensaje_descripcion ?>" />
    <meta itemprop="image" content="<?= \Cake\Routing\Router::url('/img/faindit_logo.jpg', true) ?>" />
    
    <?php endif; /*fin aparece en busquedas*/ ?>
    
    
    <?php if(isset($meta_facebook)): /*aparece en perfil de un negocio*/
    /*evaluar si tiene logo personalizado*/
    $imagen_ruta = 'img/neg/'.$negocio['id'].'/logo.jpg';

    if(!file_exists($imagen_ruta)){
      $imagen_ruta = 'img/no_logo.jpg';
    }
    
    $meta_description = $negocio['descripcion'];
    ?>  
    <!-- facebook -->
    <meta property="og:url"           content="<?= \Cake\Routing\Router::url(['controller'=>'N', 'action'=>'index', $sucursal_id, $negocio['nombre_slug']], true) ?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="<?= $negocio['nombre'] ?>" />
    <meta property="og:description"   content="<?= $this->Text->truncate($negocio['descripcion'], 400) ?>" />        
    <meta property="og:image"         content="<?= \Cake\Routing\Router::url('/'.$imagen_ruta, true) ?>" />
    <!-- google+ -->
    <meta itemprop="name" content="<?= $negocio['nombre'] ?>" />
    <meta itemprop="description" content="<?= $this->Text->truncate($negocio['descripcion'], 400) ?>" />
    <meta itemprop="image" content="<?= \Cake\Routing\Router::url('/'.$imagen_ruta, true) ?>" />
    <?php endif; /*fin aparece en perfil de un negocio*/ ?>
    
    <?php
    $thisUrl = \Cake\Routing\Router::url(null, true);
    if(!preg_match('/localhost/i', $thisUrl) && \Cake\Core\Configure::read('debug')!=true && isset($facebookPixelCode)):
    ?>    
    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','//connect.facebook.net/en_US/fbevents.js');

    fbq('init', '1702679449986097');
    fbq('track', "PageView");</script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1702679449986097&ev=PageView&noscript=1" alt="pixel"/></noscript>
    <!-- End Facebook Pixel Code -->
    <?php endif; ?>
    
    <meta name="description" content="<?= $meta_description; ?>" />
    
	</head>
	<body>
    <?php if(isset($config['mantenimiento_activar']) && $config['mantenimiento_activar']==1): ?>
    <div class="alert alert-warning text-center alert-mantenimiento" role="alert">            
        <strong><?= $config['mantenimiento_mensaje'] ?></strong>
      </div>
    <?php endif; ?>
    
		<?= $this->element('header'); ?>
    <?= $this->element('menu'); ?>
		
    <div class="container" style="position: relative;">
      <?php if(isset($config['deshabilitar_sitio']) && $config['deshabilitar_sitio']==1): ?>
        <div class="alert alert-warning text-center" role="alert">            
          <strong><?= __('AVISO: Sitio web deshabilitado') ?></strong>
        </div>
      <?php endif; ?> 
      <?= $this->Flash->render() ?>
    </div>
    
    <?= $this->fetch('content') ?>
    
    <?= $this->element('footer'); ?>
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
