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
    <div class="row">
      <div id="buscar-sidebar" class="col-md-4 col-lg-3 border-blue">
        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title visible-md visible-lg">Menu</h3>
            <span id="menu-boton" class="fa fa-bars visible-xs visible-sm"></span>
          </div>
          <div id="menu-cuerpo" class="panel-body">
            <ul>
              <li>Opcion 1</li>
              <li>Opcion 2</li>
              <li>Opcion 3</li>
              <li>Opcion 4</li>
              <li>Opcion 5</li>
              <li>Opcion 6</li>
              <li>Opcion 7</li>
              <li>Opcion 8</li>
              <li>Opcion 9</li>
              <li>Opcion 10</li>
            </ul>
          </div>
        </div>         
      </div>
      <div id="buscar-contenido" class="col-md-8 col-lg-9 border-green" style="height: 300px;">
        
      </div>
    </div>
    
    
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#menu-boton').click(function(){
      $('#menu-cuerpo').slideToggle();
    });
  });
  
  $(window).resize(function(){
    $('#menu-cuerpo').removeAttr('style');
  });
</script>