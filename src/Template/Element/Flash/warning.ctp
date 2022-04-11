<?php
$class = '';
$tiempo = 0;

if (!empty($params['class'])) {
  $class = $params['class'];
}

if (!empty($params['tiempo'])) {
  $tiempo = $params['tiempo'];
}

?>
<div class="alert alert-warning alert-dismissible <?= h($class) ?>" role="alert" data-timeout="<?= h($tiempo) ?>">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><?= ($message) ?></strong>
</div>

<?php if(strpos($class, 'timed')!==FALSE): ?>
<script>
  var timeout = $('.timed').attr('data-timeout');	
  setTimeout(function(){
    $('.timed').fadeOut();
  }, timeout * 1000);
</script>
<?php endif; ?>