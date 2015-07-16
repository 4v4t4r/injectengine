<?php
$this->set(compact('completed_inject', 'expired_inject', 'check_requested', 'inject'));

$this->extend('injects/common');
$this->assign('inject_submit', '');
$this->start('inject_submit');
?>

<div class="row">
	<div class="col-sm-9">
		<p class="form-control-static">This inject must be manually checked by a White Team member.</p>
	</div>
	<div class="col-sm-2">
		<button 
			id="inject<?php echo $inject['Inject']['id']; ?>-requestCheckBtn"
			class="btn btn-primary<?php echo ($completed_inject OR $expired_inject OR $check_requested) ? ' disabled' : ''; ?>" 
			data-toggle="modal" 
			data-target="#manualCheckModal" 
			data-inject-id="<?php echo $inject['Inject']['id']; ?>"
			data-inject-name="<?php echo $inject['Inject']['title']; ?>" 
		>
			<?php echo $check_requested ? 'Check Requested' : 'Request Check'; ?>
		</button>
	</div>
</div>

<?php $this->end(); ?>
