<div class="container-fluid">
  <div class="container container-min-height">
    <div class="row">
      <div class="col-xs-12 text-center">
        <h2><?= __('Ayuda - Redes sociales') ?></h2>
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-12 text-justify">
        <?= __('En esta p&aacute;gina de ayuda encontrar&aacute; orientaci&oacute;n sobre como agregar las redes 
          sociales de su negocio u organizaci&oacute;n a un perfil en '.$config['sitio_nombre'].'.') ?>
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-12">
        <h3 id="Facebook"><?= __('Facebook') ?></h3>
      </div>
    </div>
    
    <div class="row margin-bottom-20x">
      <div class="col-xs-12 text-justify">
        <?= __('En su perfil de negocio puede especificar la p&aacute;gina de Facebook mediante una casilla como esta:') ?>
        <div class="margin-bottom-5x">
          <?php 
          if(!$this->request->is('mobile')){
            echo $this->Html->image('ayuda/redes_sociales/perfil_facebook1.png', ['class'=>'img-responsive']);
          } else{
            echo $this->Html->image('ayuda/redes_sociales/perfil_facebook2.png', ['class'=>'img-responsive']);
          }
          ?>
        </div>
        <div class="margin-bottom-5x">
          <?= __('Si se dirige a su fanpage de Facebook en este momento podr&aacute; notar que la direcci&oacute;n web en su navegador
          es como la siguiente:<br/> <i>https://www.facebook.com/faindit/?fref=ts</i>') ?>
        </div>
       
        <div>
          <?= __('En este caso particular solo nos interesa tomar lo resaltado en negrita de la direcci&oacute;n web y 
            pegarlo en la casilla del formulario del negocio:
            <br/> <i>https://www.facebook.com/<b style="font-size: 110%;">faindit</b>/?fref=ts</i>') ?>
        </div>
      </div>
    </div>
    
    
    
    <div class="row">
      <div class="col-xs-12">
        <h3 id="Twitter"><?= __('Twitter') ?></h3>
      </div>
    </div>
    
    <div class="row margin-bottom-20x">
      <div class="col-xs-12 text-justify">
        <?= __('En su perfil de negocio puede especificar la p&aacute;gina de Twitter mediante una casilla como esta:') ?>
        <div class="margin-bottom-5x">
          <?php 
          if(!$this->request->is('mobile')){
            echo $this->Html->image('ayuda/redes_sociales/perfil_twitter1.png', ['class'=>'img-responsive']);
          } else{
            echo $this->Html->image('ayuda/redes_sociales/perfil_twitter2.png', ['class'=>'img-responsive']);
          }
          ?>
        </div>
        <div>
          <?= __('En la cual si tomamos de ejemplo el usuario para '.$config['sitio_nombre'].' el cual es @FainditSV 
            deber&aacute; digitar eso mismo pero sin la arroba, es decir solo lo resaltado en negrita:<br/>
            @<b style="font-size: 110%;">FainditSV</b>') ?>
        </div>        
      </div>
    </div>
    
    
    
    <div class="row">
      <div class="col-xs-12">
        <h3 id="GooglePlus"><?= __('Google+') ?></h3>
      </div>
    </div>
    
    <div class="row margin-bottom-20x">
      <div class="col-xs-12 text-justify">
        <?= __('En su perfil de negocio puede especificar la p&aacute;gina de Google+ mediante una casilla como esta:') ?>
        <div class="margin-bottom-5x">
          <?php 
          if(!$this->request->is('mobile')){
            echo $this->Html->image('ayuda/redes_sociales/perfil_googleplus1.png', ['class'=>'img-responsive']);
          } else{
            echo $this->Html->image('ayuda/redes_sociales/perfil_googleplus2.png', ['class'=>'img-responsive']);
          }
          ?>
        </div>
        <div class="margin-bottom-5x">
          <?= __('Si se dirige a su p&aacute;gina de Google+ en este momento podr&aacute; notar que la direcci&oacute;n web en su navegador
          es como la siguiente:<br/> <i>https://plus.google.com/u/0/b/102745894110736266558/102745894110736266558/+faindit/about</i>') ?>
        </div>
        
        <div class="margin-bottom-5x">
          <?= __('Lo que nos interesa es el ID que se encuentra en esa direcci&oacute;n web el cual podr&aacute; reconocer
            como un n&uacute;mero largo:<br/>
            <i>https://plus.google.com/u/0/b/<b style="font-size: 110%;">102745894110736266558</b>/102745894110736266558/+faindit/about</i>') ?>
        </div>
        
        <div class="margin-bottom-5x">
          <?= __('Ese n&uacute;mero es el que debemos copiar y pegar en la casilla del perfil del negocio en '.$config['sitio_nombre'].'. 
            Si usted ha asignado el nombre de su negocio a la p&aacute;gina de Google+, como alternativa al id que acabamos de describir
            podr&aacute; utilizar ese nombre el nuestro formulario, incluyendo el signo mas(+) al inicio:<br/>
            https://plus.google.com/u/0/b/102745894110736266558/102745894110736266558/<b style="font-size: 110%">+faindit</b>/about</i>') ?>
        </div>  
      </div>
    </div>
    
    
    <div class="row">
      <div class="col-xs-12">
        <h3 id="Instagram"><?= __('Instagram') ?></h3>
      </div>
    </div>
    
    <div class="row margin-bottom-20x">
      <div class="col-xs-12 text-justify">
        <?= __('En su perfil de negocio puede especificar la p&aacute;gina de Instagram mediante una casilla como esta:') ?>
        <div class="margin-bottom-5x">
          <?php 
          if(!$this->request->is('mobile')){
            echo $this->Html->image('ayuda/redes_sociales/perfil_instagram1.png', ['class'=>'img-responsive']);
          } else{
            echo $this->Html->image('ayuda/redes_sociales/perfil_instagram2.png', ['class'=>'img-responsive']);
          }
          ?>
        </div>
        <div>
          <?= __('En la cual &uacute;nicamente tiene que digitar el usuario conque inicia sesi&oacute;n en dicha red social.') ?>
        </div>        
      </div>
    </div>
   
  </div>
</div>