<div id="div-tel-<?= $index ?>" class="input-group margin-bottom-5x">
  <div class="input-group-btn">
    <?= $this->Form->select('sucursales.0.telefonos.'.$index.'.tipo',[
              1 => _('Tel.'),
              2 => __('Fax'),
              3 => __('Cel.')
            ],[
              'id'=>'telefonos.'.$index.'.tipo',
              'class'=>'form-control input-sm telefono-tipo',
              'style'=>'width: 70px;'
            ]); ?>
  </div>
  <?= $this->Form->input('sucursales.0.telefonos.'.$index.'.numero',[
          'id'=>'telefonos.'.$index.'.numero',
          'class'=>'form-control input-sm telefono-numero',
          'placeholder'=>'0000-0000',
          'label'=>false,
          'div'=>false
        ]); ?>
  <span class="input-group-addon input-sm" id="">
    <span id="tel_quitar.<?= $index ?>" title="<?= __('Eliminar este registro'); ?>" class="fa fa-close cursor-pointer tel_quitar"></span>
  </span>
</div>