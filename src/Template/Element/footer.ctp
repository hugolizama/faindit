<div id="footer" class="container-fluid footer">
  <div class="container ">
    <div class="row">
      <div class="col-xs-12 text-center">
        <?php if (isset($config['sitio_nombre_secundario'])){ echo $config['sitio_nombre_secundario']; }else{ echo '';} ?>&COPY; <?= date('Y') ?>
      </div>      
    </div>    
    
    <div class="row">
      <div class="col-xs-12 col-sm-4 col-md-2 col-md-offset-2">
        <?= $this->Html->link('<span class="fa fa-fw fa-envelope" style="margin-right: 5px;"></span>Contacto con '.$config['sitio_nombre'], [
          'controller'=>'Principal',
          'action'=>'contacto'
        ], ['escape'=>false]) ?>
        <br/>
        <?= $this->Html->link('<span class="fa fa-fw fa-exclamation-triangle" style="margin-right: 5px;"></span>Comentarios', [
          'controller'=>'Principal',
          'action'=>'comentarios'
        ], ['escape'=>false]) ?>
      </div> 
      
      <div class="col-xs-12 col-sm-4 col-md-3 col-md-offset-1">
        <div>
          <?= $this->Html->link('Condiciones de uso', [
            'controller'=>'Principal',
            'action'=>'condicionesDeUso'
          ], ['escape'=>false]) ?>
        </div>
        <div>
          <?= $this->Html->link('Pol&iacute;ticas de privacidad', [
            'controller'=>'Principal',
            'action'=>'politicasDePrivacidad'
          ], ['escape'=>false]) ?>
        </div>
        
      </div> 
      
      <div class="col-xs-12 col-sm-4 col-md-2 text-right">        
        <?php
        if(isset($config['sitio_facebook']) && $config['sitio_facebook']!=''){
          echo $this->Html->link('<span class="fa fa-facebook-square fa-2x"></span>', 
            $config['sitio_facebook'], [
              'escape'=>false,
              'target'=>'_blank',
              'title'=>'Facebook',
              'class'=>'social-icon',
          ]);
        }
        
        if(isset($config['sitio_twitter']) && $config['sitio_twitter']!=''){
          echo $this->Html->link('<span class="fa fa-twitter-square fa-2x"></span>', 
            $config['sitio_twitter'], [
              'escape'=>false,
              'target'=>'_blank',
              'title'=>'Twitter',
              'class'=>'social-icon',
          ]);
        }
        ?>
      </div>
    </div> 
    
    <?php $user_agent = $this->request->header('user-agent');?>
    <?php 
    /*Verificar si el navegador es Internet Explorer para recomendar Firefox o Chrome*/
    if(!preg_match('/chrome/i', $user_agent) && !preg_match('/firefox/i', $user_agent) /*&& !preg_match('/mobile/i', $user_agent)*/): 
    ?>
    <div class="row text-center" style="margin-top: 20px;">  
      <div>Visualiza mejor <?php if (isset($config['sitio_nombre_secundario'])){ echo $config['sitio_nombre_secundario']; }else{ echo '';} ?> con</div>      
      <table style="margin: 0px auto 0px auto;">
        <tr>
          <td class="text-center">
            <?php
            $urlFirefox = 'https://www.mozilla.org/es-ES/firefox/new/';
            $urlChrome = 'https://www.google.com/chrome/browser/desktop/index.html';
            
            if($this->request->is('mobile')){ /*verificar si el disponitivo es movil para cambiar la url a la google play store*/
              $urlFirefox = 'https://play.google.com/store/apps/details?id=org.mozilla.firefox&hl=es-419';
              $urlChrome = 'https://play.google.com/store/apps/details?id=com.android.chrome&hl=es-419';
            }  
            echo $this->Html->link(
              $this->Html->image('firefox_icon.png').'<div style="font-size: 14px;">Firefox</div>', 
                $urlFirefox,['escape'=>false,'target'=>'_blank']);
            ?>            
          </td>
          <td style="font-size: 20px; padding: 0px 10px 0px 10px; color: white;"><b><?= __('O') ?></b></td>
          <td class="text-center">
            <?php 
            echo $this->Html->link(
              $this->Html->image('chrome_icon.png').'<div style="font-size: 14px;">Chrome</div>', 
                $urlChrome ,['escape'=>false,'target'=>'_blank']);      
            ?>
          </td>
        </tr>
      </table>      
    </div>
    <?php endif; ?>
  </div>
</div>
<?php
/*imprimir codigo de seguimiento para usuarios que no son administrador*/
$urlFooter = \Cake\Routing\Router::url(null, true);
if((!isset($perRol) || $perRol['acceso_admin']==0) && !preg_match('/localhost/i', $urlFooter)){
  echo $config['sitio_script_rastreo'];
}
?>