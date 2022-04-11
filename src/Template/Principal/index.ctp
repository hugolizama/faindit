<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($negocios); ?>
</pre>
<?php endif; ?>

<div class="container-fluid">
  <div class="container">
    <?= $this->Form->create('search', ['class'=>'form-horizontal', 'action'=>'buscarEncode','type'=>'get', 'autocomplete'=>'off']); ?>
    <div class="form-search">  
      <div class="row text-center"><h3><?= __('Buscador'); ?></h3></div>
      <div class="form-group buscador-group">
        <div class="col-md-5 col-lg-4 col-lg-offset-1">
          <?php
          echo $this->Form->input('que', [
            'id' => 'txtQue',
            'label' => false,
            'div' => false,
            'class' => 'form-control input-lg',
            'placeholder' => __('Busca. Ej: Abogados'),
            'autofocus'=>'autofocus',
            'required'=>true /*necesario*/
          ]);

          echo $this->Form->input('tq', [
            'type'=>'hidden',
            'id' => 'tq',
            'value'=>0
          ]);
          ?>
        </div>
        <div class="col-md-5 col-lg-4">
          <?php
          echo $this->Form->input('en', [
            'id' => 'txtEn',
            'label' => false,
            'div' => false,
            'class' => 'form-control input-lg',
            'placeholder' => __('Donde. Ej: San Salvador')
          ]);

          echo $this->Form->input('te', [
            'type'=>'hidden',
            'id' => 'te',
            'value'=>0
          ]);
          ?> 
        </div> 
        <div class="col-md-2 col-lg-2">
          <?php
          echo $this->Form->submit($config['sitio_nombre'], [
            'id'=>'btnBuscarIndex',
            'class' => 'btn btn-lg btn-info'
          ]);
          ?>
        </div>
      </div>
      
      <div class="form-group visible-md visible-lg">
        <div class="col-lg-offset-1 cat-sugerencias">
          Sugerencias:<br/><?= $texto_cat5; ?>
        </div>
      </div>
      
      <div class="form-group hidden-md hidden-lg cat-sugerencias">
        Sugerencias:<br/><?= $texto_cat3; ?>
      </div>
      
    </div>
    <?= $this->Form->end(); ?>
  </div>
</div>

<div class="container-fluid background-slate-blue">
  <div id="main-register" class="container text-center">
    <div class="row text-white">
      <div id="reasons" class="col-xs-12">
        <div class="razones-registrarte"><?= __('¿Tienes un negocio o quieres ofrecer tus servicios? <div class="hidden-lg"></div>Razones para registrarte'); ?></div>
      </div>
    </div>
    <div class="row text-white">
      <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-0 reasons-item">
        <span class="fa fa-group fa-5x "></span>
        <div>
          <?= __('Aumenta tu cartera de clientes') ?>
        </div>
      </div>

      <div class="col-xs-12 col-sm-5 col-md-4 reasons-item ">
        <span class="fa fa-building fa-5x"></span>
        <div>
          <?= __('Incrementa tu lista de contactos') ?>
        </div>
      </div>

      <div class="col-xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-0 reasons-item">
        <span class="fa fa-bar-chart fa-5x"></span>
        <div>
          <?= __('Mejora tus ventas') ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <?php
        echo $this->Html->link(__('Registrate, es gratis!'), [
          'prefix'=>false,
          'controller'=>'Usuarios',
          'action'=>'registro'
        ] , [
          'class'=>'btn btn-success btn-lg'
        ]);
        ?>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid background-slate-brown business-ultimos">
  <div class="container text-center">
    <div class="row"><h2><?= _('&Uacute;ltimos registros') ?></h2></div>
    <div class="row">      
      <?php foreach($ultimos_registros as $ur): ?>
      
        <div class="col-xs-12 col-sm-6 col-md-3 business-item hideme">
          <?php
          
          /*evaluar si tiene logo personalizado*/
          $imagen_ruta = 'neg/'.$ur['id'].'/logo.jpg';
          
          if(!file_exists('img/'.$imagen_ruta)){
            $imagen_ruta = 'no_logo.jpg';
          }
          
          $imagen = $this->Html->image($imagen_ruta , ['class'=>'img-rounded zoom', "alt"=>$ur['nombre']]); /*crear imagen*/
          
          /*texto completo del negocio*/
          $bi_text = '<div class="business-img-div">'.$imagen.'</div>
            <div class="business-title">
              '.$ur['nombre'].'
            </div>
            <div class="business-excerpt">
              '.$this->Text->truncate($ur['descripcion'], 100).'
            </div>';
          
          /*imprimir enlace con la informacion del negocio*/
          echo $this->Html->link($bi_text, [
            'prefix' => false,
            'controller' => 'N',
            'action' => 'index',
            $ur['sucursales'][0]['id'],
            $ur['nombre_slug']
            ], [
            'escape' => false,
          ]);
          ?>       
        </div>      
      
      <?php endforeach; ?>
      
    </div>
  </div>
</div>

<div id="business-random" class="container-fluid background-orange">
  <div class="container text-center">
    <div class="row"><h2><?= _('Algunos al azar') ?></h2></div>
    <div class="row">      
      <?php foreach($negocios as $neg): ?>
      
        <div class="col-xs-12 col-sm-6 col-md-3 business-item hideme">
          <?php
          
          /*evaluar si tiene logo personalizado*/
          $imagen_ruta = 'neg/'.$neg['id'].'/logo.jpg';
          
          if(!file_exists('img/'.$imagen_ruta)){
            $imagen_ruta = 'no_logo.jpg';
          }
          
          $imagen = $this->Html->image($imagen_ruta , ['class'=>'img-rounded zoom', "alt"=>$ur['nombre']]); /*crear imagen*/
          
          /*texto completo del negocio*/
          $bi_text = '<div class="business-img-div">'.$imagen.'</div>
            <div class="business-title">
              '.$neg['nombre'].'
            </div>
            <div class="business-excerpt">
              '.$this->Text->truncate($neg['descripcion'], 100).'
            </div>';
          
          /*imprimir enlace con la informacion del negocio*/
          echo $this->Html->link($bi_text, [
            'prefix' => false,
            'controller' => 'N',
            'action' => 'index',
            $neg['sucursales'][0]['id'],
            $neg['nombre_slug']
            ], [
            'escape' => false,
          ]);
          ?>       
        </div>      
      
      <?php endforeach; ?>
      
    </div>
  </div>
</div>

<script>   
  $(document).ready(function(){
    /*efecto de animacion para mostrar los negocios*/
    $('.hideme').each( function(i){
      var bottom_of_object = $(this).offset().top + ($(this).outerHeight()/2);
      var bottom_of_window = $(window).scrollTop() + $(window).height();

      /* If the object is completely visible in the window, fade it it */
      if( bottom_of_window > bottom_of_object ){
        $(this).animate({'opacity':'1'}, 1000);
        $(this).find('img').animate({'height':'100%'}, 1000);
      }
    });     
   
    $(window).scroll( function(){
      $('.hideme').each( function(i){
        var bottom_of_object = $(this).offset().top + ($(this).outerHeight()/2);
        var bottom_of_window = $(window).scrollTop() + $(window).height();

        if( bottom_of_window > bottom_of_object ){
          $(this).animate({'opacity':'1'}, 1000);
          $(this).find('img').animate({'height':'100%'}, 1000);
        }
      }); 
    });
    /*fin de efecto de animacion*/
  });
</script>




