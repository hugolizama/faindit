<div class="row">
  <div class="col-xs-12 col-lg-10">
    <label class="control-label control-label-left">
      <?= __('Arrastre y suelte las im&aacute;genes para ordenarlas seg&uacute;n su preferencia. Para seleccionar  o 
        deseleccionar una im&aacute;gen ') ?>

      <?php
      if($isMobile==true){
        echo __('mantengala presionada por un segundo.');
      }else{
        echo __('haga clic sobre ella.');
      }
      ?>
    </label>
  </div>
</div>

<div   class="row" style="text-align: center;">
  <div id="div-eliminar-img" class="col-xs-12"></div>
  <div id="menu-eliminar-img" class="col-xs-12 text-center">
    <input id="selectall" type="checkbox" style="display: none;">
    <label id="label-selectall" for="selectall" class="btn btn-primary btn-sm btn-margin-right"><?= __('Seleccionar todo') ?></label>
    <button id="btn-eliminar-seleccionadas" type="submit" name="btnEliminarImagenes" class="btn btn-danger btn-sm" disabled="disabled"><?= __('Eliminar seleccionadas') ?></button>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <ul id="sortable">
      <?php foreach ($imagenes as $img): ?>
      <li id="li-<?= $img['id'] ?>" class="ui-state-default li-check">
        <input type="checkbox" class="chk-img selectall" value="<?= $img['id'] ?>" name="chk[]" id="chk-<?= $img['id'] ?>"/>
        <input type="text" class="img-order" value="<?= $img['orden'] ?>" name="img[<?= $img['id'] ?>]" id="img-<?= $img['id'] ?>">
        <?= $this->Html->image('neg/'.$img['negocio_id'].'/'.$img['sucursal_id'].'/'.$img['nombre'].'.jpg', [
          'class'=>'sortable-img',                  
        ]) ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>