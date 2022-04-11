<?php if(isset($debug) && $debug===1): ?>
<pre>
  <?php print_r($data); ?>
</pre>
<?php endif; ?>
<div class="container-fluid">  
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Editar ubicaci&oacute;n de ') ?> <?= $sucursal['nombre'] ?> de <?= $this->Html->link($sucursal['negocio']['nombre'], [
        'prefix'=>'admin', 'controller'=>'NegociosSucursales', 'action'=>'editarNegocio', $negocio_id
      ]); ?></h3>       
    </div>
  </div>

  <div class="row">      
    <div class="col-xs-12">
      <?= $this->Form->create($sucursal, ['class' => 'form-horizontal']); ?>  
      <div class="form-group">
        <div class="col-xs-12">
          <?= $this->element('Admin/menu-sucursales'); ?>
        </div>
      </div>
      <div class="form-group margin-bottom-10x" id="neg-mapa">
        <div class="col-md-12 col-lg-10">
          <div class="row">
            <div class="col-xs-12 col-md-4 margin-bottom-5x">
              <?=
              $this->Form->input('pais_id', [
                'div'=>false, 'label'=>false,
                'type'=>'select',
                'options'=>$paises,
                'empty' => __('- País -'),                 
                'class' => 'form-control ',
                'id' => 'pais_id',
                'escape'=>false,
              ]);
              ?>
            </div>

            <div class="col-xs-12 col-md-4 margin-bottom-5x">
              <span class="required" style="font-size: 12px; position: absolute; right: 5px;"></span>
              <?=
              $this->Form->input('departamento_id', [
                'div'=>false, 'label'=>false,
                'type'=>'select',
                'empty' => __('- Departamentos -'),
                'class' => 'form-control ',
                'id' => 'departamento_id',
                'escape'=>false,
              ]);
              ?>

            </div>

            <div class="col-xs-12 col-md-4 margin-bottom-5x">
              <span class="required" style="font-size: 12px; position: absolute; right: 5px;"></span>
              <?=
              $this->Form->input('municipio_id', [
                'div'=>false, 'label'=>false,
                'type'=>'select',
                'empty' => __('- Municipios -'),
                'class' => 'form-control ',
                'id' => 'municipio_id',
                'escape'=>false,
              ]);
              ?>
            </div>
          </div>

          <div class="row margin-bottom-10x">
            <div class="col-xs-12">
              <?php
              echo $this->Form->input('direccion', array(
                'class' => 'form-control ',
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
              <?= __('Seleccione en el mapa la ubicaci&oacute;n de la sucursal de su negocio. En la caja de texto del mapa puede 
                realizar b&uacute;squedas de direcci&oacute;n para acercarse f&aacute;cilmente a la locaci&oacute;n de 
                la sucursal. Al hacer click en el mapa creará un marcador, esto indicar&aacute; la ubicaci&oacute;n 
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
                echo $this->Form->input('lat',[
                  'id'=>'lat',
                  'type'=>'hidden',
                  'div'=>false,
                ]);

                echo $this->Form->input('lng',[
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
            'controller' => 'NegociosSucursales',
            'action' => 'editarSucursalMapa',
            $sucursal_id
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

 
<script>  
$(document).ready(function(){    
  /*activar popover*/
  $('[data-toggle="popover"]').popover(); 
  
  /*get departamentos*/
  function getDepartamentos(pais_id, cambioSeleccion){
    $.ajax({
      url: "<?= Cake\Routing\Router::url(['prefix'=>false, 'controller' => 'Sucursales', 'action' => 'getDepartamentos']) ?>/" + pais_id,
      dataType: 'html',
      type: 'POST'
    }).done(function(data){
      $('#departamento_id').html(data);
      
      <?php if(isset($data['departamento_id']) || isset($sucursal['departamento_id'])): ?>
      /*seleccionar departamento cuando se carga la pagina por primera vez y cargar municipios*/
      if(cambioSeleccion === undefined){
        $('#departamento_id').val(<?= (isset($data['departamento_id'])) ? $data['departamento_id'] : $sucursal['departamento_id'] ?>);
        getMunicipios(<?= (isset($data['departamento_id'])) ? $data['departamento_id'] : $sucursal['departamento_id'] ?>);
      }else{
        $('#municipio_id').html('<option value="" selected>- Municipios -</option>');
      }
      <?php endif; ?>      
    });
  }
  
  <?php if(isset($data['pais_id']) || isset($sucursal['pais_id'])): ?>
  /*llenar de nuevo al hacer post*/
  getDepartamentos(<?= (isset($data['pais_id'])) ? $data['pais_id'] : $sucursal['pais_id'] ?>);
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
      url: "<?= Cake\Routing\Router::url(['prefix'=>false, 'controller' => 'Sucursales', 'action' => 'getMunicipios']) ?>/" + departamento_id,
      dataType: 'html',
      type: 'POST'
    }).done(function(data){
      $('#municipio_id').html(data);
      
      <?php if(isset($data['municipio_id']) || isset($sucursal['municipio_id'])): ?>
      /*seleccionar municipio cuando se carga la pagina por primera vez o co post*/
      if(cambioSeleccion === undefined){
        $('#municipio_id').val(<?= (isset($data['municipio_id'])) ? $data['municipio_id'] : $sucursal['municipio_id'] ?>);
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


  /*listener de eventos en el buscador
    se dispara al escribir y seleccionar texto predictivo*/
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFrVItEITGc-IjDjSazYhDuOIvMX0xfI4&libraries=places&signed_in=true&callback=initAutocomplete" async defer></script>  
