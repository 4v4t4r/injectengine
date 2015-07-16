<?php
$this->set(compact('completed_inject', 'expired_inject', 'check_requested', 'inject'));

$this->extend('injects/common');
$this->assign('inject_submit', '');
$this->start('inject_submit');
?>

<p><em>Inject Type: Submit</em></p>

<?php $this->end(); ?>
