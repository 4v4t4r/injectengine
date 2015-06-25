<?php
/*
 * I am so sorry, this page will be *extremely* messy.
 * I promised myself that I would make it nicer in the future, but
 * you know how that works.....
 *
 * .....sorry
 */

// Helper functions for the page
$injectCompleted = function($dependency_id) use($injects) {
	foreach ( $injects AS $inject ) {
		if ( $inject['Inject']['id'] != $dependency_id ) continue;

		return $inject['CompletedInject']['id'] !== null;
	}

	return false;
};

$getInjectName = function($inject_id) use($injects) {
	foreach ( $injects AS $inject ) {
		if ( $inject['Inject']['id'] != $inject_id ) continue;

		return $inject['Inject']['title'];
	}

	return 'Unknown';
};

// Scripts for the page
echo $this->Html->script('injectengine');
?>

<h2>Injects</h2>

<div class="panel-group" id="accordion">
	<?php foreach ( $injects AS $inject ): ?>
	
	<?php
		if ( $inject['Inject']['dependency'] != 0 && !$injectCompleted($inject['Inject']['dependency']) ) continue;

		$completed_inject = ($inject['CompletedInject']['id'] !== null);
	?>
	
	<div class="panel <?php echo ($completed_inject) ? 'panel-success' : 'panel-primary'; ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a 
					data-toggle="collapse" 
					href="#inject<?php echo $inject['Inject']['id']; ?>" 
					class="<?php echo ($completed_inject) ? 'collapsed' : ''; ?>"
				>
					<?php echo $inject['Inject']['title']; ?>
				</a>
			</h4>
		</div>

		<div id="inject<?php echo $inject['Inject']['id']; ?>" class="panel-collapse collapse<?php echo ($completed_inject) ? '' : ' in'; ?>">
			<div class="panel-body">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<?php echo $inject['Inject']['description']; ?>
							</td>
							<td class="text-right text-nowrap">
								<?php if ( $completed_inject ): ?>
								
								<button class="btn btn-xs btn-success">COMPLETED</button>

								<?php else: ?>

								<?php if ( $inject['Inject']['hints_enabled'] ): ?>
								<button 
									class="btn btn-xs btn-info" 
									data-toggle="modal" 
									data-target="#hintModal" 
									data-inject-id="<?php echo $inject['Inject']['id']; ?>"
								>
									HINTS AVAILABLE
								</button>
								<?php endif; ?>

								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<?php if ( $inject['Inject']['type'] == 1 ): ?>
								
								<form action="#" class="form-horizontal">
									<div class="form-group">
										<label for="inject<?php echo $inject['Inject']['id']; ?>-flag" class="col-sm-1 control-label">Flag</label>
										<div class="col-sm-9">
											<input 
												type="<?php echo ($completed_inject) ? 'password' : 'text'; ?>" 
												class="form-control inject-flag" 
												id="inject<?php echo $inject['Inject']['id']; ?>-flag" 
												placeholder="Enter Key Here"
												data-inject-id="<?php echo $inject['Inject']['id']; ?>" 
												<?php echo ($completed_inject) ? 'disabled="disabled" value="good_try_but_no_password_here"' : ''; ?>
											>
										</div>
										<div class="col-sm-2">
											<button type="submit" class="btn btn-primary"<?php echo $completed_inject ? ' disabled="disabled"' : ''; ?>>Submit</button>
										</div>
									</div>
								</form>
								<div class="alert alert-danger text-center hidden" id="inject<?php echo $inject['Inject']['id']; ?>-invalid">
									<strong>Invalid Password!</strong> No guessing!
								</div>
								
								<?php elseif ( $inject['Inject']['type'] == 2 ): ?>
								
								<p><em>Inject Type: Submit</em></p>
								
								<?php else: ?>
								
								<p><em>Unknown Inject Type</em> - Please contact White Team</p>
								
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="panel-footer">
				<p><strong>Inject Start</strong>: <?php echo date('n/j \a\t g:iA', $inject['Inject']['time_end']); ?></p>
				<p><strong>Inject End</strong>: <?php echo date('n/j \a\t g:iA', $inject['Inject']['time_end']); ?></p>
				
				<?php if ( $completed_inject ): ?>
				<p><strong>Completed By</strong>: <?php echo $inject['User']['username']; ?> at <?php echo date('g:iA', $inject['CompletedInject']['time']); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>

<div class="modal fade" id="hintModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Request A Hint</h4>
			</div>

			<div class="modal-body">
				<em>Loading...</em>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	InjectEngine.init('<?php echo $this->Html->url('/injects'); ?>');
});
</script>