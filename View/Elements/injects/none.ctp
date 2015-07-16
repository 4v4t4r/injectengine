<?php
$this->set(compact('completed_inject', 'expired_inject', 'check_requested', 'inject'));

$this->extend('injects/common');
$this->assign('inject_submit', '');
$this->start('inject_submit');
?>

<div class="row">
	<div class="col-sm-12">
		<p class="text-muted">There is no actions available for this inject.</p>
	</div>
</div>

<?php $this->end(); ?>
