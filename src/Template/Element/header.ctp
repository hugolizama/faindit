<div class="container-fluid background-orange hidden-xs">
  <div class="container">
    <div class="row" style="padding-top: 10px; padding-bottom: 10px;">
      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" >
        <a id="title" href="<?= \Cake\Routing\Router::url(['prefix'=>false, 'controller'=>'Principal', 'action'=>'index']) ?>">
          <h1>
            <span itemprop="name"><?php if (isset($config['sitio_nombre'])){ echo $config['sitio_nombre']; }else{ echo '';}  ?></span>
          </h1>
          <h2 id="subtitle"><?php echo __('El Salvador') ?></h2>
        </a>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9 text-right" style="margin-top: 35px;">
        <?php if ($mostrarPublicidad==1 && !($this->request->controller=='Principal' && $this->request->action=='index')): ?>
          <div id="ad-top-responsive">         
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Adaptable -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-3436124210477611"
                 data-ad-slot="8091302082"
                 data-ad-format="auto"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>                     
          </div>
        <?php endif; ?>  
        
        <?php
        /*Posicionar aqui los iconos sociales mientras no se active la publicidad*/
        if(isset($config['sitio_facebook']) && $config['sitio_facebook']!=''){
          echo $this->Html->link('<span class="fa fa-facebook-square fa-2x"></span>', 
            $config['sitio_facebook'], [
              'escape'=>false,
              'target'=>'_blank',
              'title'=>'Facebook',
              'class'=>'social-icon',
              'style'=>'color: #333;'
          ]);
        }
        
        if(isset($config['sitio_twitter']) && $config['sitio_twitter']!=''){
          echo $this->Html->link('<span class="fa fa-twitter-square fa-2x"></span>', 
            $config['sitio_twitter'], [
              'escape'=>false,
              'target'=>'_blank',
              'title'=>'Twitter',
              'class'=>'social-icon',
              'style'=>'color: #333;'
          ]);
        }
        ?>
        
      </div>
    </div>
  </div>
</div>