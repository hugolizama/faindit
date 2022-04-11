<?php
/*radio buttons personalizados para abregar class radio-label*/
$this->Form->templates([
  'nestingLabel' => '<label{{attrs}} class=\'radio-modo\'>{{input}}{{text}}</label>',
]);
?>

<?php if ($modo == 'meses'):?>
<style>
  .ui-datepicker-calendar{
    display: none;
  }
</style>
<?php endif; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h3><?= __('Mas estad&iacute;sticas') ?></h3>  
    </div>
  </div>
  
  <?php echo $this->Form->create(null, ['class'=>'form-inline']); ?>
  <div class="row">
    <div class="col-xs-12">
      Vista en       
      
      <?php
      echo $this->Form->input('modo',array(
        'type'=>'radio',
        'options'=>array(
          'dias' => 'D&iacute;as',
          'meses' => 'Meses'
        ),
        'label'=>false,
        'div'=>false,
        'class'=>'form-control hugo',
        'default'=> (isset($modo)) ? $modo : 'dias',
        'escape'=>false
      ));
      ?>      
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12">
      Fecha inicio
      <?php
      echo $this->Form->input('fecha1', array(
        'label'=>false,
        'div'=>false,
        'class'=>'form-control'
      ));
      ?>
      
      <div class="hidden-xs" style="display: inline-block; margin-right: 10px;"></div>
      Fecha final
      <?php
      echo $this->Form->input('fecha2', array(
        'label'=>false,
        'div'=>false,
        'class'=>'form-control'
      ));
      ?>
      
      <?php
      echo $this->Form->input('Mostrar', array(
        'type'=>'button',
        'id'=>'btnMostrar',
        'label'=>false,
        'div'=>false,
        'class'=>'btn btn-default'
      ));
      ?>
    </div>
  </div>
  
  <div class="row">
    <div class="col-xs-12">
      <div id="promedioBusquedas"></div>
      <div id="graficaBusquedas"></div>
      
      <div id="promedioRegistros" style="margin-top: 20px;"></div>
      <div id="graficaRegistros"></div>
    </div>
  </div>
    
  <?php echo $this->Form->end(); ?>
</div>


<script>
  $(document).ready(function(){
    /*establecer fecha minima de estadisticas*/
    var fechaMinima = new Date(<?php echo $fechaInicio[2]; ?>, <?php echo $fechaInicio[1]; ?> - 1, <?php echo $fechaInicio[0]; ?>);
    var fechaDefault1 = new Date(<?php echo $fechaHoy[2]; ?>, <?php echo $fechaHoy[1]; ?> - 1, 1 );
    var fechaDefault2 = new Date(<?php echo $fechaHoy[2]; ?>, <?php echo $fechaHoy[1]; ?> - 1, <?php echo $fechaHoy[0]; ?> );
    var meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    function pad(n, width, z) {
      z = z || '0';
      n = n + '';
      return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }
    
    <?php if($modo == 'dias'): ?>    
    
    $("#fecha1").val(fechaDefault1.getFullYear() + '-' + pad((fechaDefault1.getMonth() + 1), 2) + '-' + pad(fechaDefault1.getDate(), 2));  
    $( "#fecha1" ).datepicker({
      monthNamesShort: meses,
      changeMonth: true,
      changeYear: true,
      showButtonPanel: false,
      dateFormat: 'yy-mm-dd',
      minDate: fechaMinima,
      maxDate: "+0d",
      defaultDate: fechaDefault1
    });
    
    $("#fecha2").val(fechaDefault2.getFullYear() + '-' + pad((fechaDefault2.getMonth() + 1), 2) + '-' + pad(fechaDefault2.getDate(), 2));  
    $( "#fecha2" ).datepicker({
      monthNamesShort: meses,
      changeMonth: true,
      changeYear: true,
      showButtonPanel: false,
      dateFormat: 'yy-mm-dd',
      minDate: fechaMinima,
      maxDate: "+0d",
      defaultDate: fechaDefault2
    });
    
    <?php elseif($modo == 'meses'): ?>
      
    $("#fecha1").val(fechaDefault1.getFullYear() + '-01');      
    $( "#fecha1" ).datepicker({
      monthNamesShort: meses,
      changeMonth: true,
      changeYear: true,
      showButtonPanel: true,
      dateFormat: 'yy-mm',
      minDate: fechaMinima,
      maxDate: "+0d",
      defaultDate: fechaDefault1,
      onClose: function(dateText, inst) { 
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
      } 
    });
    
    $("#fecha2").val(fechaDefault2.getFullYear() + '-' + pad((fechaDefault2.getMonth() + 1), 2));      
    $( "#fecha2" ).datepicker({
      monthNamesShort: meses,
      changeMonth: true,
      changeYear: true,
      showButtonPanel: true,
      dateFormat: 'yy-mm',
      minDate: fechaMinima,
      maxDate: "+0d",
      defaultDate: fechaDefault2,
      onClose: function(dateText, inst) { 
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
      } 
    });
      
    <?php endif; ?>
    
    $('input[name=modo]').click(function(){
      var modo = $('input[name=modo]:checked').val();
      
      var url = '<?php echo \Cake\Routing\Router::url(array('action'=>'masEstadisticas')); ?>' + '/' + modo;
      
      $(location).attr('href', url);
      
    });
    
    
    $('#btnMostrar').click(function(){
      var modo = $('input[name=modo]:checked').val();
      var fecha1 = $("#fecha1").val();
      var fecha2 = $("#fecha2").val();
      
      var restFecha1;
      var restFecha2;
      var fechaResta;
      var restaDias;
      var promedioBusquedas = 0;
      var sumaContador = 0;
      var promedioRegistros = 0;
      var meses;
            
      $.ajax({
        data: {modo: modo, fecha1: fecha1, fecha2: fecha2},
        dataType: 'json',
        type: 'POST',
        url: '<?php echo \Cake\Routing\Router::url(array('action'=>'getDatosBusquedas')); ?>'        
      }).done(function(data){
        
        sumaContador = 0;
        promedioBusquedas = 0;
        
        if(modo==='dias'){
          /*dias*/
          restFecha1 = new Date(fecha1);
          restFecha2 = new Date(fecha2);          
          fechaResta = restFecha2 - restFecha1;
          
          restaDias = ((((fechaResta / 1000) / 60) / 60) / 24) + 1; /*dias*/ 
          
          data.forEach(function(a){
            sumaContador = sumaContador + parseInt(a['contador']);
          });
          
          promedioBusquedas = Math.round((sumaContador/restaDias) * 100) / 100;
        }else{
          /*meses*/
          meses = 0;
          restFecha1 = new Date(fecha1 + '-01');
          restFecha2 = new Date(fecha2 + '-01');           
          
          meses = (restFecha2.getFullYear() - restFecha1.getFullYear()) * 12;
          meses -= restFecha1.getMonth();
          meses += restFecha2.getMonth() + 1;
          
          data.forEach(function(a){
            sumaContador = sumaContador + parseInt(a['contador']);
          });
          
          promedioBusquedas = Math.round((sumaContador/meses) * 100) / 100;
        }
        
        $('#promedioBusquedas').html('Promedio de búsquedas: ' + Number(promedioBusquedas).toLocaleString());
        dibujarGraficaBusquedas(data);        
      });
      
      
      $.ajax({
        data: {modo: modo, fecha1: fecha1, fecha2: fecha2},
        dataType: 'json',
        type: 'POST',
        url: '<?php echo \Cake\Routing\Router::url(array('action'=>'getDatosRegistros')); ?>'        
      }).done(function(data){
                
        sumaContador = 0;
        promedioRegistros = 0;
        
        if(modo==='dias'){
          /*dias*/
          restFecha1 = new Date(fecha1);
          restFecha2 = new Date(fecha2);          
          fechaResta = restFecha2 - restFecha1;
          
          restaDias = ((((fechaResta / 1000) / 60) / 60) / 24) + 1; /*dias*/       
          
          data.forEach(function(a){
            sumaContador = sumaContador + parseInt(a['contador']);
          });
          
          promedioRegistros = Math.round((sumaContador/restaDias) * 100) / 100;          
          
        }else{
          /*meses*/
          meses = 0;
          restFecha1 = new Date(fecha1 + '-01');
          restFecha2 = new Date(fecha2 + '-01');           
          
          meses = (restFecha2.getFullYear() - restFecha1.getFullYear()) * 12;
          meses -= restFecha1.getMonth();
          meses += restFecha2.getMonth() + 1;
          
          data.forEach(function(a){
            sumaContador = sumaContador + parseInt(a['contador']);
          });
          
          promedioRegistros = Math.round((sumaContador/meses) * 100) / 100;          
        }
        
        $('#promedioRegistros').html('Promedio de registros: ' + Number(promedioRegistros).toLocaleString());
        dibujarGraficaRegistros(data);        
      });
    });
    
    
    function dibujarGraficaBusquedas(datos){
      var chart = AmCharts.makeChart("graficaBusquedas", {
        "type": "serial",
        "theme": "light",
        "titles":[{"text":"Búsquedas"}],
        "marginRight": 70,
        "dataProvider": datos,
        "valueAxes": [{
          "axisAlpha": 0,
          "position": "left",
          "title": "Búsquedas"
        }],
        "startDuration": 1,
        "graphs": [{
          "balloonText": "<b>[[category]]: [[value]]</b>",
          "fillAlphas": 0.9,
          "lineAlpha": 0.2,
          "type": "column",
          "valueField": "contador",
          "labelText": "[[value]]"
        }],
        "chartCursor": {
          "categoryBalloonEnabled": false,
          "cursorAlpha": 0,
          "zoomable": false
        },
        "categoryField": "fecha",
        "categoryAxis": {
          "gridPosition": "start",
          "labelRotation": 45
        }
        /*,
        "export": {
          "enabled": true
        }*/

      });
    }
    
    
    function dibujarGraficaRegistros(datos){
      var chart = AmCharts.makeChart("graficaRegistros", {
        "type": "serial",
        "theme": "light",
        "titles":[{"text":"Registros de negocios"}],
        "marginRight": 70,
        "dataProvider": datos,
        "valueAxes": [{
          "axisAlpha": 0,
          "position": "left",
          "title": "Registros"
        }],
        "startDuration": 1,
        "graphs": [{
          "balloonText": "<b>[[category]]: [[value]]</b>",
          "fillAlphas": 0.9,
          "lineAlpha": 0.2,
          "type": "column",
          "valueField": "contador",
          "labelText": "[[value]]"
        }],
        "chartCursor": {
          "categoryBalloonEnabled": false,
          "cursorAlpha": 0,
          "zoomable": false
        },
        "categoryField": "fecha",
        "categoryAxis": {
          "gridPosition": "start",
          "labelRotation": 45
        }
        /*,
        "export": {
          "enabled": true
        }*/

      });
    }    
  });
</script>