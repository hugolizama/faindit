<?php            
echo $this->Form->select('fecha_termina_suspension', $opcionesFecha, [
  'class' => 'form-control',
  'escape'=>false
]);