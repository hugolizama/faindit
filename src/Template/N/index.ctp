<!--modal para envio de correo -->
<div class="modal fade" id="modalCorreo" tabindex="-1" role="dialog" aria-labelledby="modalCorreoLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalPara"></h4>
      </div>
      <div class="modal-body">         
        <div id='modalSpinner' style="position: absolute; margin-top: 10%; left: 0; right: 0; text-align: center; display: none;">
          <span class="fa fa-spinner fa-pulse fa-5x"></span>
        </div>
        <div id='modalError' class="form-group form-group-sm">          
        </div>
        <div class="form-group form-group-sm">
          <input type="hidden" id='modalSucursalId' />
          <label class="control-label"><?= __('Para:') ?></label>
          <input type="text" class="form-control" id="modalCorreoDireccion" disabled/>
        </div>
        <div class="form-group form-group-sm">
          <label class="control-label required"><?= __('De (nombre y apellido):') ?></label>
          <input type="text" class="form-control" id="modalDeNombre" />
        </div>
        <div class="form-group form-group-sm">
          <label class="control-label required"><?= __('Su correo electr&oacute;nico:') ?></label>
          <input type="email" class="form-control" id="modalDeCorreo" />
        </div>
        <div class="form-group form-group-sm">
          <label class="control-label required"><?= __('Mensaje:') ?></label>
          <textarea class="form-control" id="modalMensaje"></textarea>
        </div>
        
        <?php if(!isset($cookieUsuario)): ?>
        <div class="form-group form-group-sm">
          <div id='gCaptcha'></div>
        </div> 
        <?php endif; ?>  
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancelar') ?></button>
        <button type="button" class="btn btn-primary" id='modalEnviarMensaje'><?= __('Enviar mensaje') ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modalCorreoEnviado">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body"> 
        <?= __('Mensaje enviado con &eacute;xito') ?>
      </div>      
      <div class="modal-footer modal-sm">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Aceptar') ?></button>
      </div>
    </div>
  </div>
</div>

<!-- fin modal para envio de correo -->

<div class="container-fluid">
  <div class="container">
    <?php if((isset($cookieUsuario['id']) && $cookieUsuario['id']==$sucursal['usuario_id']) ||
      (isset($perRol['admin_neg_sucursales']) && $perRol['admin_neg_sucursales']==1)): ?>
    <div class="row">
      <div class="col-xs-12">
        <?= $this->Html->link('Editar negocio', $url_editar, array(
          'class'=>'btn btn-link'
        ));
        ?>
      </div>
    </div>
    <?php endif; ?>
    
    <div class="row">
      <div class="col-xs-12 text-center">
        <h3 class="page-header"><?= h($sucursal['negocio']['nombre']); ?></h3>
      </div>
    </div>
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 text-center">
        <?php
          $rutaImg='neg/'.$sucursal['negocio_id'].'/logo.jpg';                    
          if(!file_exists('img/'.$rutaImg)){
            $rutaImg='no_logo.jpg';
          }
          
          list ($w, $h) = getimagesize(Cake\Routing\Router::url('/img/', true).$rutaImg);

          echo $this->Html->image($rutaImg, ['class'=>'logo-negocio', "style"=>"width: $w; height:$h",
                                            "alt"=>h($sucursal['negocio']['nombre'])]);
        ?>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid" style="padding-bottom: 10px;">
  <div class="container">
    <div class="row">
      <?php
      $negocioUrl = Cake\Routing\Router::url([
        'controller'=>'N', 
        'action'=>'index', 
        $sucursal['id'], 
        $sucursal['negocio']['nombre_slug'],
        'tq'=>$tq, 'en'=>$en, 'te'=>$te
      ], true);
      ?>
      <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 text-right bpage-share">
        <a class="bpage-link-share" tabindex="0" role="button" data-toggle="popover" 
          data-trigger="focus" data-placement="left" data-html="true"
          data-content="
          <a class='social-share' data-url='https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($negocioUrl) ?>' title='Facebook'><span class='fa fa-2x fa-facebook-square share-item'></span></a>
          <a class='social-share' data-url='https://twitter.com/intent/tweet?text=<?= urlencode($sucursal['negocio']['nombre']) ?>&url=<?= urlencode($negocioUrl) ?>&hashtags=<?= $config['sitio_nombre'] ?>ElSalvador' title='Twitter'><span class='fa fa-2x fa-twitter-square share-item'></span><a>
          <a class='social-share' data-url='https://plus.google.com/share?url=<?= urlencode($negocioUrl) ?>' title='Google +'><span class='fa fa-2x fa-google-plus-square share-item'></span><a>
          <?php if($this->request->is('mobile')): ?><a href='whatsapp://send?text=<?= urlencode($negocioUrl) ?>' data-action='share/whatsapp/share'><span class='fa fa-2x fa-whatsapp share-item'></span></a><?php endif; ?>">
         <span title="<?= __('Compartir') ?>" class="fa fa-share-alt-square fa-1-5x"></span>
       </a> 
      </div>
    </div>
    <div class="row">
      <?php
      /*configurar columnas para no mostrar el div con los contactos si no hay telefonos registrados en la base*/
      $configColumnas = "col-xs-12 col-sm-9 col-md-5 col-md-offset-2 col-lg-4 col-lg-offset-3";
      if(empty($sucursal['telefonos'])){
        $configColumnas = "col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3";
      }
      ?>
      <div class="<?php echo $configColumnas; ?> text-justify margin-bottom-10x">
        <?= nl2br($sucursal['negocio']['descripcion']) ?>
      </div>
      
      <?php if(!empty($sucursal['telefonos'])): /*oculta div si no hay telefonos registrados en la base*/ ?>
      <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 margin-bottom-10x" >        
        <b><?= __('Contacto: ') ?></b> <br/>
        <?php
        foreach($sucursal['telefonos'] as $tel){
          switch ($tel['tipo']){
            case 1:
              $tipo = 'Tel:';
              break;
            case 2:
              $tipo = 'Fax:';
              break;
            default:
              $tipo = 'Cel:';
              break;
          }

          echo $tipo.'('.$sucursal['pais']['codigo_telefono'].') '.$tel['numero']."<br/>";
        }
        ?>
      </div>
      <?php endif; ?>
    </div>
    
    <div class="row">
      <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 bpage-adress margin-bottom-10x text-justify">
        <span class="fa fa-map-marker"></span> 
        <?php 
        echo $sucursal['departamento']['nombre'].', '.$sucursal['municipio']['nombre'].'. '.$sucursal['direccion']; 
        ?>
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 text-center">
        <small>Medios de contacto con <?= $sucursal['negocio']['nombre'] ?></small>
      </div>
    </div>
    
    <div class="row"> 
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 bg-info text-center" style="padding: 10px; border-radius: 5px;">
        <span class="bpage-social">
          <?php if($sucursal['negocio']['url']!=''): ?>
          <a href="<?= $sucursal['negocio']['url'] ?>" target="_blank"><span class="fa fa-globe fa-1-5x bpage-social-item" title="Sitio web"></span></a>          
          <?php endif; ?>
          
          <?php 
            /*facebook*/
            if($sucursal['facebook']!='' || $sucursal['negocio']['facebook']!=''): 
              $rutaFacebook = $sucursal['facebook'];
              if($rutaFacebook==''){
                $rutaFacebook = $sucursal['negocio']['facebook'];
              }
          ?>
          <a href="<?= $config['negocios_url_facebook'].$rutaFacebook ?>" target="_blank"><span class="fa fa-facebook fa-1-5x bpage-social-item" title="Facebook"></span></a>
          <?php endif; ?>
          
          <?php 
            /*twitter*/
            if($sucursal['twitter']!='' || $sucursal['negocio']['twitter']!=''): 
              $rutaTwitter = $sucursal['twitter'];
              if($rutaTwitter==''){
                $rutaTwitter = $sucursal['negocio']['twitter'];
              }
          ?> 
          <a href="<?= $config['negocios_url_twitter'].$rutaTwitter ?>" target="_blank"><span class="fa fa-twitter fa-1-5x bpage-social-item" title="Twitter"></span></a>   
          <?php endif; ?>
          
          <?php 
            /*google plus*/
            if($sucursal['google_plus']!='' || $sucursal['negocio']['google_plus']!=''): 
              $rutaGooglePlus = $sucursal['google_plus'];
              if($rutaGooglePlus==''){
                $rutaGooglePlus = $sucursal['negocio']['google_plus'];
              }
          ?>  
          <a href="<?= $config['negocios_url_google_plus'].$rutaGooglePlus ?>" target="_blank"><span class="fa fa-google-plus fa-1-5x bpage-social-item" title="Google +"></span></a>
          <?php endif; ?>
          
          <?php 
            /*instagram*/
            if($sucursal['instagram']!='' || $sucursal['negocio']['instagram']!=''): 
              $rutaInstagram = $sucursal['instagram'];
              if($rutaInstagram==''){
                $rutaInstagram = $sucursal['negocio']['instagram'];
              }
          ?> 
          <a href="<?= $config['negocios_url_instagram'].$rutaInstagram ?>" target="_blank"><span class="fa fa-instagram fa-1-5x bpage-social-item" title="Instagram"></span></a>
          <?php endif; ?>
          
          <?php 
            /*correo*/
            if($sucursal['correo']!=''):               
              $rutaCorreo = $sucursal['correo'];              
          ?>
          <a id="modalCorreoLabel" href="#" data-toggle="modal" data-sucursal='<?= $sucursal['id'] ?>' data-target="#modalCorreo" data-correo="<?= $rutaCorreo ?>" data-para='<?= h($sucursal['negocio']['nombre']) ?>'><span class="fa fa-envelope fa-1-5x bpage-social-item" title="<?= __('Enviar correo a '). h($sucursal['negocio']['nombre']); ?>"></span></a>
          <?php endif; ?>
        </span>
      </div>
    </div>
  </div>
</div>

<?php if(!empty($imagenes)): ?>
<div class="container-fluid background-slate-blue text-white" style="padding-top: 10px; padding-bottom: 10px;">
  <div class="container">
    <div class="row" style="overflow-x: auto;">
      <div class="col-xs-12 col-lg-10 col-lg-offset-1 text-center neg-gallery" itemscope itemtype="http://schema.org/ImageGallery">
        <?php foreach($imagenes as $img): ?>
        <?php 
        $url = Cake\Routing\Router::url('/', true).'/img/neg/'.$img['negocio_id'].'/'.$img['sucursal_id'].'/'.$img['nombre'].'.jpg'; 
        
        if($img['ancho']==null){ /*obtener alcho algo de la imagen*/
          $image_size = getimagesize($url);
          $size = $image_size[0]."x".$image_size[1];
        }else{
          $size = $img['ancho']."x".$img['alto'];
        }       
        ?>
        
        <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" style="background: url(<?= $url ?>); ">
          <a href="<?= $url ?>" itemprop="contentUrl" data-size="<?= $size ?>">
            <img src="<?= $url ?>" itemprop="thumbnail" alt="<?= h($sucursal['negocio']['nombre']); ?>"/>
          </a>
        </figure>
        
      <?php endforeach; ?>  
        
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="container-fluid" style="padding-top: 20px; padding-bottom: 10px;">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-3 margin-bottom-10x" style="padding-left: 0px; padding-right: 0px;">
        <h4 id="title-sucursales"><?= __('Sucursales') ?></h4>
        <?php
        $this->Form->templates([
          'option' => '<option value="{{value}}" title="{{text}}"{{attrs}}>{{text}}</option>',
          'optgroup' => '<optgroup label="{{label}}" title="{{label}}"{{attrs}}>{{content}}</optgroup>',
        ]);
        
        echo $this->Form->select('sucursal', $arraySucursales, [
          'id'=>'bpage-sucursales',
          'class'=>'form-control bpage-sucursales padding-sucursales',
          'default'=>$defaultSucursal,
          'size'=>2       
        ]);
        
        
        ?>
      </div>
      <div class="col-xs-12 col-md-9">
        <div style="position: relative;">          
          <div id="texto_disabled" class="alert alert-warning <?php echo ($sucursal['lat']=='') ? 'disabled':''; ?>" >
            <strong>Ubicaci&oacute;n no disponible</strong>
          </div>                    
        </div>        
        
        <div id="map_canvas"></div>        
      </div>
    </div>
  </div>
</div>

<div class="container-fluid background-orange" style="padding-top: 20px; padding-bottom: 10px;">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 text-center">
        <h5 style="font-weight: bold;"><?= __('Categor&iacute;as relacionadas') ?></h5>
        <?php
        foreach ($negocio['categorias'] as $cat){
          echo "<div class='cat-relacionadas'>";
          echo $this->Html->link($cat['nombre'],[
            'controller'=>'Principal',
            'action'=>'buscar',
            'que'=>  urlencode($cat['nombre']),
            'tq' => 1, 
            'en'=> urlencode($en), 
            'te' => $te
          ]);
          echo "</div>";
        }
        ?>
      </div>
    </div>
  </div>
</div>


<!-- plantilla photogallery. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

  <!-- Background of PhotoSwipe. 
       It's a separate element, as animating opacity is faster than rgba(). -->
  <div class="pswp__bg"></div>

  <!-- Slides wrapper with overflow:hidden. -->
  <div class="pswp__scroll-wrap">

    <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
    <!-- don't modify these 3 pswp__item elements, data is added later on. -->
    <div class="pswp__container">
      <div class="pswp__item"></div>
      <div class="pswp__item"></div>
      <div class="pswp__item"></div>
    </div>

    <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
    <div class="pswp__ui pswp__ui--hidden">

      <div class="pswp__top-bar">
        <!--  Controls are self-explanatory. Order can be changed. -->

        <div class="pswp__counter"></div>

        <button class="pswp__button pswp__button--close" title="Cerrar (Esc)"></button>

        <!--<button class="pswp__button pswp__button-[quitar]-share" title="Compartir"></button>-->

        <button class="pswp__button pswp__button--fs" title="Pantalla completa"></button>

        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

        <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
        <!-- element will get class pswp__preloader-[quitar]-active when preloader is running -->
        <div class="pswp__preloader">
          <div class="pswp__preloader__icn">
            <div class="pswp__preloader__cut">
              <div class="pswp__preloader__donut"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
        <div class="pswp__share-tooltip"></div> 
      </div>

      <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>

      <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>

      <div class="pswp__caption">
        <div class="pswp__caption__center"></div>
      </div>
    </div>
  </div>
</div>
<!-- fin plantilla photogallery. -->


<script> 
  $(function () {
    $('[data-toggle="popover"]').popover();
  });
  
    
  /*photogallery*/
  var initPhotoSwipeFromDOM = function(gallerySelector) {

    /*parse slide data (url, title, size ...) from DOM elements */
    /*(children of gallerySelector)*/
    var parseThumbnailElements = function(el) {
      var thumbElements = el.childNodes,
          numNodes = thumbElements.length,
          items = [],
          figureEl,
          linkEl,
          size,
          item;

      for(var i = 0; i < numNodes; i++) {

        figureEl = thumbElements[i]; /*<figure> element*/

        /*include only element nodes */
        if(figureEl.nodeType !== 1) {
            continue;
        }

        linkEl = figureEl.children[0]; /*<a> element*/

        size = linkEl.getAttribute('data-size').split('x');

        /*create slide object*/
        item = {
            src: linkEl.getAttribute('href'),
            w: parseInt(size[0], 10),
            h: parseInt(size[1], 10)
        };

        if(figureEl.children.length > 1) {
            /*<figcaption> content*/
            item.title = figureEl.children[1].innerHTML; 
        }

        if(linkEl.children.length > 0) {
            /*<img> thumbnail element, retrieving thumbnail url*/
            item.msrc = linkEl.children[0].getAttribute('src');
        } 

        item.el = figureEl; /*save link to element for getThumbBoundsFn*/
        items.push(item);
      }

      return items;
    };

    /*find nearest parent element*/
    var closest = function closest(el, fn) {
      return el && ( fn(el) ? el : closest(el.parentNode, fn) );
    };

    /*triggers when user clicks on thumbnail*/
    var onThumbnailsClick = function(e) {
      e = e || window.event;
      e.preventDefault ? e.preventDefault() : e.returnValue = false;

      var eTarget = e.target || e.srcElement;

      /*find root element of slide*/
      var clickedListItem = closest(eTarget, function(el) {
        return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
      });

      if(!clickedListItem) {
        return;
      }

      /*find index of clicked item by looping through all child nodes*/
      /*alternatively, you may define index via data- attribute*/
      var clickedGallery = clickedListItem.parentNode,
        childNodes = clickedListItem.parentNode.childNodes,
        numChildNodes = childNodes.length,
        nodeIndex = 0,
        index;

      for (var i = 0; i < numChildNodes; i++) {
        if(childNodes[i].nodeType !== 1) { 
          continue; 
        }

        if(childNodes[i] === clickedListItem) {
          index = nodeIndex;
          break;
        }
        nodeIndex++;
      }

      if(index >= 0) {
        /*open PhotoSwipe if valid index found*/
        openPhotoSwipe( index, clickedGallery );
      }
      return false;
    };

    /*parse picture index and gallery index from URL (#&pid=1&gid=2)*/
    var photoswipeParseHash = function() {
      var hash = window.location.hash.substring(1),
      params = {};

      if(hash.length < 5) {
        return params;
      }

      var vars = hash.split('&');
      for (var i = 0; i < vars.length; i++) {
        if(!vars[i]) {
            continue;
        }
        var pair = vars[i].split('=');  
        if(pair.length < 2) {
            continue;
        }           
        params[pair[0]] = pair[1];
      }

      if(params.gid) {
        params.gid = parseInt(params.gid, 10);
      }

      return params;
    };

    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
      var pswpElement = document.querySelectorAll('.pswp')[0],
        gallery,
        options,
        items;

      items = parseThumbnailElements(galleryElement);

      /*define options (if needed)*/
      options = {
        /*define gallery index (for URL)*/
        galleryUID: galleryElement.getAttribute('data-pswp-uid'),

        getThumbBoundsFn: function(index) {
          /*See Options -> getThumbBoundsFn section of documentation for more info*/
          var thumbnail = items[index].el.getElementsByTagName('img')[0], /*find thumbnail*/
            pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
            rect = thumbnail.getBoundingClientRect(); 

          return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
        }

      };

      /*PhotoSwipe opened from URL*/
      if(fromURL) {
        if(options.galleryPIDs) {
          /*parse real index when custom PIDs are used */
          /*http://photoswipe.com/documentation/faq.html#custom-pid-in-url*/
          for(var j = 0; j < items.length; j++) {
            if(items[j].pid == index) {
              options.index = j;
              break;
            }
          }
        } else {
          /*in URL indexes start from 1*/
          options.index = parseInt(index, 10) - 1;
        }
      } else {
        options.index = parseInt(index, 10);
      }

      /*exit if index not found*/
      if( isNaN(options.index) ) {
        return;
      }

      if(disableAnimation) {
        options.showAnimationDuration = 0;
      }

      /*Pass data to PhotoSwipe and initialize it*/
      gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
      gallery.init();
    };

    /*loop through all gallery elements and bind events*/
    var galleryElements = document.querySelectorAll( gallerySelector );

    for(var i = 0, l = galleryElements.length; i < l; i++) {
      galleryElements[i].setAttribute('data-pswp-uid', i+1);
      galleryElements[i].onclick = onThumbnailsClick;
    }

    /*Parse URL and open gallery if it contains #&pid=3&gid=1*/
    var hashData = photoswipeParseHash();
    if(hashData.pid && hashData.gid) {
      openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
    }
  };

  /*execute above function*/
  initPhotoSwipeFromDOM('.neg-gallery');
  /*fin photogallery  */
  
  
  function sucursales_lista(){
    /*modificar lista de sucursales segun tamanio de pantalla*/
    var ancho_ventana = $(window).width();
    
    if(ancho_ventana<1024){
      $('#bpage-sucursales').attr('size','');
      $('#bpage-sucursales').removeClass('bpage-sucursales');
    }else{
      $('#bpage-sucursales').attr('size',2);
      $('#bpage-sucursales').addClass('bpage-sucursales');
    }
  }
  
  $(window).resize(function(){
    sucursales_lista();
  });
  
  <?php if(!isset($cookieUsuario)): ?>
  var widgetId1;
  function generarCaptcha(){
    widgetId1 = grecaptcha.render('gCaptcha', {
      'sitekey' : '6LeI6Q8TAAAAAL_yOH82ttIyccRrIMwK7Ki784YV'
    });
  }
  <?php endif; ?>
  
  $(document).ready(function(){
    sucursales_lista();
    
    /*inicio modal para envio de correo */     
    $('#modalCorreo').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); /*Button that triggered the modal*/
      var para = button.data('para'); /*Extract info from data-* attributes*/
      var correo = button.data('correo'); /*Extract info from data-* attributes*/
      var sucursal_id = button.data('sucursal'); /*Extract info from data-* attributes*/
      
      var modal = $(this);
      modal.find('#modalPara').text('<?= __('Mensaje para') ?> ' + para);
      modal.find('.modal-body #modalSucursalId').val(sucursal_id);
      modal.find('.modal-body #modalCorreoDireccion').val(correo);
      modal.find('.modal-body #modalMensaje').val('');   
    });
    
    $('#modalCorreo').on('shown.bs.modal', function(){
       $('#modalDeNombre').focus();
    });
    
    
    $('#modalEnviarMensaje').click(function(){
      /*mostrar spinner*/
      $('#modalSpinner').show();
      
      var cap;
      <?php if(!isset($cookieUsuario)): ?>
      cap = $('#g-recaptcha-response').val();      
      <?php else: ?>
      cap=-1; 
      <?php endif; ?>
        
      var sucursal_id = $('#modalSucursalId').val();
      var correo = $('#modalCorreoDireccion').val();;
      var deNombre = $('#modalDeNombre').val();
      var deCorreo = $('#modalDeCorreo').val();
      var mensaje = $('#modalMensaje').val();
      
      $.ajax({
        type: 'POST',
        data: {captcha: cap, sucursal_id:sucursal_id, correo:correo, deNombre:deNombre, deCorreo:deCorreo, mensaje:mensaje},
        dataType: 'json',
        url: "<?= Cake\Routing\Router::url(['prefix'=>false, 'controller'=>'Principal', 'action'=>'enviarMensaje']) ?>"
      }).done(function(data){
        var obj = $.parseJSON(JSON.stringify(data));
        var cod = parseInt(obj.cod);
        var mensaje = obj.mensaje;
        var modalError = $('#modalError');
        
        switch(cod){
          case 0:
            $('#modalSpinner').hide();
            modalError.html(mensaje);
            <?php if(!isset($cookieUsuario)): ?>
            grecaptcha.reset(widgetId1); 
            <?php endif; ?>
            break;
            
          case 1:
            $('#modalCorreo').modal('hide');
            $('#modalCorreoEnviado').modal('show');
            break;
        }        
      });
    });
    
    
    $('#modalCorreo').on('hidden.bs.modal', function (event) {
      $('#modalSpinner').hide();
      $('#modalError').html('');
      <?php if(!isset($cookieUsuario)): ?>
      grecaptcha.reset(widgetId1); 
      <?php endif; ?>
      
    });    
    
    /*fin modal para envio de correo*/
    
    /*abrir popup para compartir un elemento*/
    $(document).on('click', '.social-share', function(e){ 
      e.preventDefault();
      
      var url = $(this).data('url');
      window.open(url, "", "width=500, height=400");
    });
    
    /*Doble clic en select box*/
    $('#bpage-sucursales option').on('dblclick', function(){
      var idCompleto = $(this).val();
      var idArray = idCompleto.split("|");
      var id = idArray[0];
      
      var index = $('#bpage-sucursales')[0].selectedIndex;     
      
      var url = sucursales[index][6];
      
      /*dirige a la url de la opcion seleccionada*/
      $(location).attr('href', url);
    });
    
    $('.neg-gallery').animate({'opacity':'1'}, 4000);
    
  });
</script>

<script>
  
  var infowindow = null;
  var gmarkers = [];
  var gmap;
  var prev_infowindow =false; 

  function initialize() {
    var image = 'icono.png';    
    var lat = <?php echo ($sucursal['lat']!='') ? $sucursal['lat']:$sucursal['pais']['lat'] ?>;
    var lng = <?php echo ($sucursal['lng']!='') ? $sucursal['lng']:$sucursal['pais']['lng'] ?>;
    
    var coordenadas= new google.maps.LatLng(lat, lng);
    
    var map_canvas = document.getElementById('map_canvas');
    var map_options = {
      center: coordenadas,
      zoom: <?php echo ($sucursal['lng']!='') ? $config['negocios_zoom_marcador']:$sucursal['pais']['zoom'] ?>
    };
    
    var map = new google.maps.Map(map_canvas, map_options);
    gmap = map;
    setMarkers(map, sucursales, '../img/icono2.png');
  }

  
  var sucursales = [       
    <?php
    foreach($negocio['sucursales'] as $suc){     
      
      $texto = "<b>".h($suc['nombre'])."</b><br/>";
      $texto.= "Direcci&oacute;n: ".$suc['departamento']['nombre'].", ".$suc['municipio']['nombre'].". ".$suc['direccion']."<br/>";
      
      $v=0;
      $count_telefonos = count($suc['telefonos']);
      
      if($count_telefonos>0){
        $texto.="Tel&eacute;fonos: <br/>";
      }
      
      foreach($suc['telefonos'] as $tel){
        $v = $v + 1;
        switch ($tel['tipo']){
          case 1:
            $tipo = 'Tel:';
            break;
          case 2:
            $tipo = 'Cel:';
            break;
          default:
            $tipo = 'Fax:';
            break;
        }

        $texto.= $tipo.'('.$suc['pais']['codigo_telefono'].') '.$tel['numero']." | ";

        if($v>2){
          break;
        }
      }
      
      $url = \Cake\Routing\Router::url([
        'action'=>'index', $suc['id'], $negocio['nombre_slug'],
        'tq'=>$tq, 'en'=>$en, 'te'=>$te
      ]);
      $texto.="<br/><a href=\"".$url."\">Ver detalles</a>";
      
      echo "['".$texto."', ".$suc['lat'].", ".$suc['lng'].", ".$suc['id'].", ".$config['negocios_zoom_marcador'].", '".$suc['id']."|".$suc['lat']."|".$suc['lng']."', '".$url."'], ";
    }
    ?>
  ];

  
  function setMarkers(map, locations, icono) {    
    var image = {
      url: icono,
      /*tamanio*/
      size: new google.maps.Size(40, 63),
      /*origen*/
      origin: new google.maps.Point(0,0),
      /*parte media*/
      anchor: new google.maps.Point(20, 63)
    };

    var infowindow = new google.maps.InfoWindow({
        content: ''
    });

    for (var i = 0; i < locations.length; i++) {
      var place = locations[i];
      var myLatLng = new google.maps.LatLng(place[1], place[2]);
      var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          animation: google.maps.Animation.DROP,  
          texto: place[0],
          id: place[3],
          zoom: place[4],
          idCompleto: place[5]
      });

      google.maps.event.addListener(marker, 'click', function() {                  
        
        /*seleccionar la opcion en el select segun el id del marcador*/
        $('#bpage-sucursales option[value="'+ this.idCompleto +'"]').prop('selected', 'selected');        
        
        var contenido='<div id="content" style="width: auto;">' + this.texto + '</div>';
        infowindow.setContent(contenido);
        infowindow.open(map, this);
        map.setZoom(this.zoom);
        prev_infowindow = infowindow;
      });
            
      gmarkers.push(marker);
    }
  }
  
  var lastIndex = 0;
  $(document).on('change','#bpage-sucursales',function(){
    var idCompleto = $(this).val();
    var idArray = idCompleto.split("|");
    var lat = idArray[1];
    var lng = idArray[2];
    
    var index = $('#bpage-sucursales')[0].selectedIndex;        
    
    if(lat!==''){
      lastIndex = index;
      $('#texto_disabled').removeClass('disabled');
      
      var position = new google.maps.LatLng(lat,lng);
      gmap.setCenter(position);
      google.maps.event.trigger(gmarkers[index],"click");      
    }else{
      $('#texto_disabled').addClass('disabled');       
      prev_infowindow.close();
    }
  });

</script>
<?php if(!isset($cookieUsuario)): ?>
<script src='https://www.google.com/recaptcha/api.js?onload=generarCaptcha&render=explicit' defer async></script>
<?php endif; ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFrVItEITGc-IjDjSazYhDuOIvMX0xfI4&signed_in=true&callback=initialize" async defer></script>