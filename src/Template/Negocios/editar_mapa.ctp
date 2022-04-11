<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <?php
        /*flash para mostrar que el negocio deshabilitado ha sido reportado para revision*/
        echo $this->Flash->render('notificado'); 
        ?>
        <h2 class="page-header"><?= __('Editar ubicaci&oacute;n de ') ?> <?= $negocio['nombre'] ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-3">
        <?= $this->element('menu-perfil'); ?>
      </div>
      <div class="col-sm-9">
        <?= $this->Form->create($negocio, ['class' => 'form-horizontal frm-sm']); ?>  
        <div class="form-group">
          <div class="col-xs-12">
            <?= $this->element('menu-n-editar'); ?>
          </div>
        </div>
        <div class="form-group margin-bottom-10x" id="neg-mapa">
          <div class="col-md-12 col-lg-10">
            <div class="row">
              <div class="col-xs-12 col-md-4 margin-bottom-5x">
                <span class="required" style="font-size: 12px; position: absolute; right: 5px;"></span>
                <?=
                $this->Form->input('sucursales.0.pais_id', [
                  'div'=>false, 'label'=>false,
                  'type'=>'select',
                  'options'=>$paises,
                  'class' => 'form-control input-sm',
                  'id' => 'pais_id',
                  'escape'=>false,
                ]);
                ?>
              </div>

              <div class="col-xs-12 col-md-4 margin-bottom-5x">
                <span class="required" style="font-size: 12px; position: absolute; right: 5px;"></span>
                <?=
                $this->Form->input('sucursales.0.departamento_id', [     
                  'div'=>false, 'label'=>false,
                  'type'=>'select',
                  'empty' => __('- Departamentos -'),
                  'class' => 'form-control input-sm',
                  'id' => 'departamento_id',
                  'escape'=>false,
                ]);
                ?>
                
              </div>

              <div class="col-xs-12 col-md-4 margin-bottom-5x">
                <span class="required" style="font-size: 12px; position: absolute; right: 5px;"></span>
                <?=
                $this->Form->input('sucursales.0.municipio_id', [      
                  'div'=>false, 'label'=>false,
                  'type'=>'select',
                  'empty' => __('- Municipios -'),
                  'class' => 'form-control input-sm',
                  'id' => 'municipio_id',
                  'escape'=>false,
                ]);
                ?>
              </div>
            </div>
            
            <div class="row margin-bottom-10x">
              <div class="col-xs-12">
                <?php
                echo $this->Form->input('sucursales.0.direccion', array(
                  'class' => 'form-control input-sm',
                  'div' => false,
                  'label' => false,
                  'placeholder' => __('Direcci&oacute;n. Ej: Residencial, Calle, etc.'),
                  'escape' => false
                ));
                ?>
              </div>
            </div>
            
            <div class="row">
              <div class="col-xs-12">
                <label class="control-label" style="text-align: left;">
                <?= __('Seleccione en el mapa la ubicaci&oacute;n de su negocio. En la caja de texto del mapa puede 
                  realizar b&uacute;squedas de direcci&oacute;n para acercarse f&aacute;cilmente a la locaci&oacute;n de 
                  su negocio. Al hacer click en el mapa creará un marcador, esto indicar&aacute; la ubicaci&oacute;n 
                  exacta que ayudar&aacute; a que sus potenciales clientes puedan encontrarlo.') ?>
                </label>
              </div>
            </div>
            
            <div class="row">
              <div class="col-xs-12">
                <input id="pac-input" class="controls" type="text" placeholder="Buscar direcci&oacute;n">
                <input id="marcador_eliminar" type="button" value="Eliminar marcador" class="btn btn-default">
                <div id="map"></div>
                <div>
                  <?php
                  echo $this->Form->input('sucursales.0.lat',[
                    'id'=>'lat',
                    'type'=>'hidden',
                    'div'=>false,
                  ]);
                  
                  echo $this->Form->input('sucursales.0.lng',[
                    'id'=>'lng',
                    'type'=>'hidden',
                    'div'=>false,
                  ]);
                  ?>                  
                </div>
              </div>
            </div>
          </div>          
        </div>        
        
        <div class="form-group text-center">
          <div class="col-md-9">
            <?php
            echo $this->Form->submit(__('Guardar'), array(
              'class' => 'btn btn-success btn-margin-right',
              'div' => false,          
            ));

            echo $this->Html->link(__('Cancelar'), array(
              'prefix'=>false,
              'controller' => 'Negocios',
              'action' => 'editarMapa',
              $negocio_id, $tokenFalso
              ), array(
              'div' => false,
              'class' => 'btn btn-danger',
              'escape' => false
            ));
            ?>
          </div>
        </div>
        
        <?= $this->Form->end(); ?>
        
      </div>
    </div>
  </div>
</div>

 
<script>  
$(document).ready(function(){ 
  
  /*get departamentos*/
  function getDepartamentos(pais_id, cambioSeleccion){
    $.ajax({
      url: "<?= Cake\Routing\Router::url(['controller' => 'Negocios', 'action' => 'getDepartamentos']) ?>/" + pais_id,
      dataType: 'html',
      type: 'POST'
    }).done(function(data){
      $('#departamento_id').html(data);
      
      <?php if(isset($data['sucursales'][0]['departamento_id']) || isset($negocio['sucursales'][0]['departamento_id'])): //verificar si se selecciono un departamento ?>      
      if(cambioSeleccion === undefined){
        $('#departamento_id').val(<?= (isset($data['sucursales'][0]['departamento_id'])) ? $data['sucursales'][0]['departamento_id'] : $negocio['sucursales'][0]['departamento_id'] ?>);
        getMunicipios(<?= (isset($data['sucursales'][0]['departamento_id'])) ? $data['sucursales'][0]['departamento_id'] : $negocio['sucursales'][0]['departamento_id'] ?>);
      }else{
        $('#municipio_id').html('<option value="" selected>- Municipios -</option>');
      }
      <?php endif; ?>
      
      
    });
  }
  
  <?php if(isset($data['sucursales'][0]['pais_id']) || isset($negocio['sucursales'][0]['pais_id'])): ?>
  /*llenar de nuevo al hacer post*/
  getDepartamentos(<?= (isset($data['sucursales'][0]['pais_id'])) ? $data['sucursales'][0]['pais_id'] : $negocio['sucursales'][0]['pais_id'] ?>);
  <?php endif; ?>
  
  
  $('#pais_id').change(function(){
    var pais_id = $(this).val();
    
    if(pais_id === ''){
      $('#departamento_id').html('<option value="" selected>- Departamentos -</option>');
      $('#municipio_id').html('<option value="" selected>- Municipios -</option>');
    }else{
      $('#departamento_id').html('<option value="" selected>- Cargando -</option>');
      getDepartamentos(pais_id, 1);
      $('#departamento_id').val('');
    }
  }); 
  /*fin get departamentos*/
  
  /*get municipios*/
  function getMunicipios(departamento_id, cambioSeleccion){
    $.ajax({
      url: "<?= Cake\Routing\Router::url(['controller' => 'Negocios', 'action' => 'getMunicipios']) ?>/" + departamento_id,
      dataType: 'html',
      type: 'POST'
    }).done(function(data){
      $('#municipio_id').html(data);
      
      <?php if(isset($data['sucursales'][0]['municipio_id']) || isset($negocio['sucursales'][0]['municipio_id'])): ?>
      if(cambioSeleccion === undefined){
        $('#municipio_id').val(<?= (isset($data['sucursales'][0]['municipio_id'])) ? $data['sucursales'][0]['municipio_id'] : $negocio['sucursales'][0]['municipio_id'] ?>);
      }
      <?php endif; ?>
    });
  }
  
  $('#departamento_id').change(function(){
    var departamento_id = $(this).val();
    
    if(departamento_id === ''){
      $('#municipio_id').html('<option value="" selected>- Municipios -</option>');
    }else{
      $('#municipio_id').html('<option value="" selected>- Cargando -</option>');
      getMunicipios(departamento_id, 1);   
      $('#municipio_id').val('');
    }
  });
  /*fin get municipios*/
});


/*******************************INICIA MAPA *****************************/
/*evitar que el formulario se envie al presionar enter*/
$('#pac-input').keypress(function(e){
  if (e.keyCode === 13){
    return false;
  }
});         

/*inicializamos marcadores*/
var markers = [];

/*funcion para iniciar buscador de lugares*/
function initAutocomplete() {
  var locacionInicial;
  
  var txtLat = $('#lat').val();
  var txtLng = $('#lng').val();
  
  /*si hay coordenadas llenas establecerlas como coordenadas iniciales*/
  if(txtLat!=='' && txtLng !==''){    
    locacionInicial = new google.maps.LatLng(parseFloat(txtLat), parseFloat(txtLng));
  }else{
    locacionInicial = new google.maps.LatLng(13.802994, -88.9053364);
  }
  
  var map = new google.maps.Map(document.getElementById('map'), {
    center: locacionInicial,
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true,
    zoomControl: true
  });
  
  /*crea el buscador*/
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  /*enlaza la busqueda con el mapa*/
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });


  /*listener de eventos en el buscador*/
  /*se dispara al escribir y seleccionar texto predictivo*/
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    /*limpia los marcadores*/
    cleanAllMarkers();

    /*para cada lugar recupera la locacion*/
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      /*latitud y longitud a cajas de texto*/
      $('#lat').val(place.geometry.location.lat());
      $('#lng').val(place.geometry.location.lng());  

      /*crea el marcador para cada lugar*/
      markers.push(new google.maps.Marker({
        map: map,
        position: place.geometry.location,
        animation: google.maps.Animation.DROP
      }));


      if (place.geometry.viewport) {            
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });

    /*mueve el foco hasta el lugar buscado*/
    map.fitBounds(bounds);        
    map.setZoom(<?php echo $config['negocios_zoom_marcador'] ?>);        
  });

  /*escucha el evento click en el mapa*/
  map.addListener('click', function(event) {
    /*recupera latitud y longitud*/
    var lat = event.latLng.lat();
    var lng = event.latLng.lng();

    /*agrega el marcador al mapa*/
    addMarker(map,lat, lng);
  });  
  
  /*dibujar marcador si latitud y longitud tienen datos*/  
  if(txtLat!=='' && txtLng !==''){    
    addMarker(map,parseFloat(txtLat), parseFloat(txtLng));
    map.setZoom(<?php echo $config['negocios_zoom_marcador'] ?>);
  }
}

/*funcion para agregar un marcador*/
function addMarker(map,lat, lng) {  
  /*quitar info window del negocio*/
  $('.gm-style-iw').parent().remove();
  
  /*limpiar todos los marcadores*/
  cleanAllMarkers();

  /*latitud y longitud a cajas de texto*/
  $('#lat').val(lat);
  $('#lng').val(lng);  

  /*dibuja el marcador en el mapa*/
  var marker = new google.maps.Marker({
    position: {lat: lat, lng: lng},
    map: map,
    animation: google.maps.Animation.DROP
  });
  markers.push(marker);  
}

/*limpia los marcadores existentes*/
function cleanAllMarkers(){
  $('#lat').val(null);
  $('#lng').val(null);

  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(null);
  }
  markers = [];
}

/*boton eliminar marcador*/
$('#marcador_eliminar').click(function(){
  cleanAllMarkers();
});
/**************************FIN MAPA ****************************/  
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $google_map_api_key ?>&libraries=places&signed_in=true&callback=initAutocomplete" async defer></script>   
