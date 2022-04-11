<div class="container-fluid">
  <div class="container container-min-height condiciones">
    <div class="row">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
        <h3><?= __('Condiciones de uso') ?></h3>
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
          <?= $config['sitio_nombre']. __(' es un proyecto independiente y no financiado que busca potenciar los negocios
            y servicios mediante un directorio organizado, y con tal objetivo nos reservamos el derecho de deshabilitar 
            o eliminar la publicaci&oacute;n de negocios que incumplan las siguientes condiciones de uso sin previo aviso 
          al usuario:') ?>        
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('MARCAS'); ?></h4>
        <?= __('Todas las marcas, nombres, logos e im&aacute;genes de negocios publicados en '.$config['sitio_nombre'].' 
          son propiedad de sus respectivos titulares o propietarios. El usuario acuerda tener autorizaci&oacute;n 
          escrita para su debido uso.'); ?>       
      </div>
    </div>
    
   <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('SERVICIOS ILEGALES'); ?></h4>
        <?= __('El usuario acuerda no publicar negocios o promocionar productos relacionados pero no restringidos 
          a actividades tales como: '); ?>    
        <ul>
          <li><?= __('Software infeccioso, destructivo o esp&iacute;a'); ?></li>
          <li><?= __('Actividades relacionadas con delitos'); ?></li>
          <li><?= __('Material pornogr&aacute;fico'); ?></li>
          <li><?= __('Prostituci&oacute;n'); ?></li>
          <li><?= __('Drogas ilegales'); ?></li>
          <li><?= __('Armas ilegales'); ?></li>
          <li><?= __('Cr&iacute;menes, trasgresiones o fechor&iacute;as inform&aacute;ticas'); ?></li>
        </ul>
      </div>
    </div> 
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('ENLACES WEB'); ?></h4>
        <?= __('Cada perfil de negocio puede contener enlaces a sitios web de terceros, el usuario tiene la 
          responsabilidad de apuntarlos a p&aacute;ginas que ofrezcan informaci&oacute;n relacionada a su negocio
          y no a actividades mencionadas en la secci&oacute;n de <b>SERVICIOS ILEGALES</b>. '.$config['sitio_nombre'].' no se hace 
          responsable por sitios web con software malicioso al que pueda apuntar uno de estos enlaces.'); ?>         
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <h4><?= __('PERFILES DE NEGOCIOS'); ?></h4>
        <?= __('El usuario deber&aacute; introducir informaci&oacute;n real que mejor describa los servicios o productos 
          que su negocio ofrece, no debe utilizar 
          vocabulario soez, vulgar, racista, sexista o que incite a la violencia. No debe escribir textos o 
          descripciones difamatorias que afecten la imagen de otro negocio y no utilizar&aacute; im&aacute;genes 
          pornogr&aacute;ficas, que inciten a la violencia o sean ofensivas de alguna manera.'); ?>         
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= __('Los perfiles no deben ser utilizados como clasificados o anuncios para la venta de inmuebles, productos en 
          espec&iacute;fico u ofrecer plazas de trabajo, ser&aacute;n inmediatamente deshabilitados.'); ?>         
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= __('En '.$config['sitio_nombre'].' no se revisa la totalidad de los negocios registrados, ni se verifica 
          que tengan permiso de uso comercial o la veracidad de la informaci&oacute;n introducida de cada perfil 
          por lo que no somos responsables por incidentes relacionados con dichos negocios.'); ?>         
      </div>
    </div>
    
    <div class="row margin-bottom-10x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= __('La utilizaci&oacute;n de este sitio web confirma que usted acepta todas estas condiciones mecionadas.'); ?>         
      </div>
    </div>
    
    <div class="row margin-bottom-20x">
      <div class="col-xs-12 col-md-6 col-md-offset-3 text-justify">
        <?= $config['sitio_nombre'].__(' se reserva el derecho de modificar estas condiciones sin previo aviso a los usuarios.'); ?>         
      </div>
    </div>
    
   
  </div>
</div>