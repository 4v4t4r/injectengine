<?php
$this->set(compact('inject'));

$this->extend('injects/common');
$this->assign('inject_submit', '');
$this->start('inject_submit');
?>

<p><em>Please note, you can only submit once.</em></p>

<form action="<?php echo $this->Html->url('/injects/submit'); ?>" class="form-horizontal" enctype="multipart/form-data" method="post">
	<input type="hidden" name="id" value="<?php echo $inject['Inject']['id']; ?>" />

	<div class="form-group">
		<label for="inject<?php echo $inject['Inject']['id']; ?>-flag" class="col-sm-1 control-label">File</label>
		<div class="col-sm-9">
			<input 
				type="file" 
				name="response"
				class="form-control inject-submit" 
				id="inject<?php echo $inject['Inject']['id']; ?>-file" 
			>
		</div>
		<div class="col-sm-2">
			<button type="submit" class="btn btn-primary"<?php echo ($this->Inject->completedOrExpired($inject)) ? ' disabled="disabled"' : ''; ?>>
				Submit
			</button>
		</div>
	</div>
</form>

<?php $this->end(); ?>
