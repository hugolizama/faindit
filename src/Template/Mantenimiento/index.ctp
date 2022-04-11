<style>
  #texto{
    margin-top: 30px; 
    padding: 0px 30px 20px 30px; 
    font-size: 38px; 
    font-weight: bold; 
    border: 1px solid #DDD; 
    line-height: 44px;
  }
  @media screen and (max-width: 767px){
    #texto{
      font-size: 23px;
      line-height: normal;
    }
  }
</style>
<div class="container-fluid">  
  <div  class="row">    
    <div id="texto" class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center">
      <div class="row">
        <div class="col-xs-12">
          <div style="">
            Ahorita no joven, estamos en mantenimiento.
          </div>
        </div>
        <div class="col-xs-12 ">
          <?= $this->Html->image('man1.jpg', [
            'class'=>'img-responsive', 
            'style'=>'margin-left: auto; margin-right: auto; max-height: 360px;'
          ]) ?>
        </div>        
      </div>
    </div>
  </div>  
</div>