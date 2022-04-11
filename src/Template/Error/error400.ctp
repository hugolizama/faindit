<?php
use Cake\Core\Configure;

if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
    
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?= Debugger::dump($error->params) ?>
<?php endif; ?>
<?= $this->element('auto_table_warning') ?>
<?php
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
    else:
      $this->layout = 'error';
      $this->assign('title', $message);
      $this->assign('templateName', 'error400.ctp');
endif;
?>
  
<div class="panel panel-default">
  <div class="panel-heading"><?= __('Ohmaiga! ¿Qu&eacute; pas&oacute;? ¿Qu&eacute; tratas de hacer?') ?></div>
  <div class="panel-body" style="font-size: 14px;">
    <div class="text-center">
      <b><?= h($error->getCode().' - '.$message) ?></b><br/>
      <?= $this->Html->image('error/'.$error->getCode().'.jpg',['class'=>'img-responsive']); ?><br/><br/>
      <?= __('Aqui no hay nada que ver. <a href="javascript:history.back(1)">Sigamos, sigamos!</a>') ?> 
    </div>
  </div>
</div>
