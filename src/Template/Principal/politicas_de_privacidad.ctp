<div class="container-fluid">
  <div class="container container-min-height condiciones">
    <div class="row">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
        <h3><?= __('Pol&iacute;ticas de privacidad') ?></h3>
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
          <?= __('A continuaci&oacute;n detallamos las pol&iacute;ticas de privacidad que se emplean en este sitio web, 
            con la navegaci&oacute;n y uso de nuestra plataforma afirma estar de acuerdo con estos t&eacute;rminos.') ?>        
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('INFORMACION PERSONAL'); ?></h4>
        <?= $config['sitio_nombre'].__(' no solicita informaci&oacute;n vital en su registro salvo correo 
          electr&oacute;nico, otros datos como nombres y apellidos son opcionales. Estos datos no ser&aacute;n 
          revelados por nuestra parte salvo en la excepci&oacute;n que una entidad judicial los solicite a 
          trav&eacute;s de un procedimiento legal.'); ?>       
      </div>
    </div>
    
   <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('GOOGLE ANALYTICS'); ?></h4>
        <?= __('En '.$config['sitio_nombre'].' utilizamos Google Analytics como herramienta 
          estad&iacute;stica y estudio de tr&aacute;fico web dentro del sitio. Sus pol&iacute;ticas pueden ser 
          revisadas en el siguiente enlace:'); ?>    
        <div><?= $this->Html->link('https://www.google.com/intl/es/policies/privacy',
          'https://www.google.com/intl/es/policies/privacy', array(
            'target'=>'_blank'
          )); ?></div>
      </div>
    </div> 
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('COOKIES'); ?></h4>
        <?= $config['sitio_nombre'].__(' utiliza cookies exclusivamente para uso de sesiones de los usuarios logueados, 
          esta guarda informaci&oacute;n como nombre de usuario y permisos.'); ?>         
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('RESPONSABILIDAD'); ?></h4>
        <?= $config['sitio_nombre'].__(' se responsabiliza por ofrecer una herramienta a los negocios para publicitar 
          su servicios, pero no somos responsables por el contenido introducido por los usuarios que pueda ser 
          malicioso, difamativo o dañino a pesar de intentar tomar las medidas necesarias para evitarlo.'); ?>         
      </div>
    </div>
    
    
    <div class="row margin-bottom-20x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= $config['sitio_nombre'].__(' se reserva el derecho de modificar estas pol&iacute;ticas sin previo aviso 
          a los usuarios.'); ?>         
      </div>
    </div>  
  </div>
</div>