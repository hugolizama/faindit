<div class="container-fluid">
  <div class="container">
    <?= $this->Form->create(null,['class'=>'form-inline']); ?>
    <div style="margin-top: 10px;"></div>
    <div class="form-group">Buscador</div>
    <div class="form-group">
      <?php
      echo $this->Form->input('que', [
        'id' => 'txtQue',
        'label' => false,
        'div' => false,
        'class' => 'form-control',
        'placeholder' => __('Busca. Ej: Abogados')
      ]);
      ?>
    </div>
    
    <div class="form-group">
      <?php
      echo $this->Form->input('en', [
        'id' => 'txtEn',
        'label' => false,
        'div' => false,
        'class' => 'form-control',
        'placeholder' => __('Donde. Ej: San Salvador')
      ]);
      ?> 
    </div>
    
    <div class="form-group">
        <?php
        echo $this->Form->submit(__('Encuentra'), [
          'class' => 'btn btn-info'
        ]);
        ?>
      </div>
    <?= $this->Form->end(); ?>
  </div>
</div>

<div class="container-fluid">
  <div class="container">
    <button type="button" id="button-toggle" class="btn btn-default" style="z-index: 1000;">        
      <span class="fa fa-bars"></span>
    </button>
    
    <div class="use-sidebar" id="main">
      <div id="content">
        <?php
        for($i=0; $i<100; $i++){
          echo $i."<br/>";
        }
        ?>
      </div>
      
      <div id="sidebar" style="position: absolute; top: 0;">
        <ul>
          <li>opcion 1</li>
          <li>opcion 2</li>
          <li>opcion 3</li>
          <li>opcion 4</li>
          <li>opcion 5</li>
          <li>opcion 6</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
          <li>opcion 7</li>
        </ul>
      </div>
    </div>

    <div id="div" style="height: 800px; border: 1px solid green;">
      asdas
    </div>
  </div>
</div>

<script>
  /*Toggle sidebar*/
$('#button-toggle').click(function(e){
  e.preventDefault();  
  $(this).toggleClass('menu-show');
  $('#main').toggleClass('use-sidebar');  
});


var buttonPosition = $('#button-toggle').offset();
var sidebarPosition = $('#sidebar').offset();

$(window).scroll(function(){
  
  /*button scrolling*/
  if($(window).scrollTop() > buttonPosition.top){
    $('#button-toggle').css('position','fixed').css('top','0');    
    
    if(($('#button-toggle').offset().top + $('#button-toggle').height()) >= ($('#main').offset().top + $('#main').height())){
      $('#button-toggle').css('position','absolute').css('top','0');
    }
    
  } else {
    $('#button-toggle').css('position','relative').css('top','0');
  }  
      
  /*sidebar scrolling*/
  var topStart;
  var topFixed = '40px';
  
  if($('#button-toggle').css('display')==='none'){
    topStart = sidebarPosition;
    topFixed= '0px';
  }else{
    topStart = buttonPosition;
  }
  
  if($(window).scrollTop() > topStart.top){
    $('#sidebar').css('position','fixed').css('top',topFixed).css('bottom','');
    
    if(($("#sidebar").offset().top + $("#sidebar").height()) >= ($("#main").offset().top + $("#main").height())){
      $('#sidebar').css('position','absolute').css('top','auto').css('bottom','0px');
      
    }
    
  } else {
    
    $('#sidebar').css('position','absolute').css('top','0px');
  } 
  
});

</script>
