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

<?php if ( !empty($injects) ): ?>
<div class="panel-group" id="accordion">
	<?php foreach ( $injects AS $inject ): ?>
	
	<?php
		// Did the inject start?
		if ( $inject['Inject']['time_start'] > 0 && $inject['Inject']['time_start'] > time() ) continue;

		// Do we have a dependency/was it started?
		if ( $inject['Inject']['dependency'] != 0 && !$injectCompleted($inject['Inject']['dependency']) ) continue;

		$completed_inject = ($inject['CompletedInject']['id'] !== null);
		$expired_inject = (!$completed_inject && $inject['Inject']['time_end'] > 0 && $inject['Inject']['time_end'] < time());
	?>
	
	<div class="panel <?php echo ($completed_inject) ? 'panel-success' : ($expired_inject ? 'panel-warning' : 'panel-primary'); ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a 
					data-toggle="collapse" 
					href="#inject<?php echo $inject['Inject']['id']; ?>" 
					class="<?php echo ($completed_inject OR $expired_inject) ? 'collapsed' : ''; ?>"
				>
					<?php echo $inject['Inject']['title']; ?>
				</a>
			</h4>
		</div>

		<div id="inject<?php echo $inject['Inject']['id']; ?>" class="panel-collapse collapse<?php echo ($completed_inject OR $expired_inject) ? '' : ' in'; ?>">
			<div class="panel-body">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<?php echo $inject['Inject']['description']; ?>
							</td>
							<td class="text-right text-nowrap">
								<?php if ( $completed_inject ): ?>
								
								<p><button class="btn btn-xs btn-success">COMPLETED</button></p>

								<?php elseif ( $expired_inject ): ?>

								<p><button class="btn btn-xs btn-danger">EXPIRED</button></p>

								<?php else: ?>

								<?php if ( $inject['Inject']['hints_enabled'] ): ?>
								<p>
									<button 
										class="btn btn-xs btn-info" 
										data-toggle="modal" 
										data-target="#hintModal" 
										data-inject-id="<?php echo $inject['Inject']['id']; ?>"
									>
										HINTS AVAILABLE
									</button>
								</p>
								<?php endif; ?>

								<p>
									<button 
										class="btn btn-xs btn-warning" 
										data-toggle="modal" 
										data-target="#helpModal" 
										data-inject-id="<?php echo $inject['Inject']['id']; ?>"
										data-inject-name="<?php echo $inject['Inject']['title']; ?>"
									>
										REQUEST HELP
									</button>
								</p>

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
												type="<?php echo ($completed_inject OR $expired_inject) ? 'password' : 'text'; ?>" 
												class="form-control inject-flag" 
												id="inject<?php echo $inject['Inject']['id']; ?>-flag" 
												placeholder="Enter Key Here"
												data-inject-id="<?php echo $inject['Inject']['id']; ?>" 
												<?php echo ($completed_inject OR $expired_inject) ? 'disabled="disabled" value="good_try_but_no_password_here"' : ''; ?>
											>
										</div>
										<div class="col-sm-2">
											<button type="submit" class="btn btn-primary"<?php echo ($completed_inject OR $expired_inject) ? ' disabled="disabled"' : ''; ?>>
												Submit
											</button>
										</div>
									</div>
								</form>
								<div class="alert alert-danger text-center hidden" id="inject<?php echo $inject['Inject']['id']; ?>-invalid">
									<strong>Invalid Password!</strong> No guessing!
								</div>
								
								<?php elseif ( $inject['Inject']['type'] == 2 ): ?>
								
								<p><em>Inject Type: Submit</em></p>

								<?php elseif ( $inject['Inject']['type'] == 3 ): ?>
								
								<div class="row">
									<div class="col-sm-9">
										<p class="form-control-static">This inject must be manually checked by a White Team member.</p>
									</div>
									<div class="col-sm-2">
										<button 
											class="btn btn-primary" 
											data-toggle="modal" 
											data-target="#manualCheckModal" 
											data-inject-id="<?php echo $inject['Inject']['id']; ?>"
											data-inject-name="<?php echo $inject['Inject']['title']; ?>" 
											<?php echo ($completed_inject OR $expired_inject) ? ' disabled="disabled"' : ''; ?>
										>
											Request Check
										</button>
									</div>
								</div>

								<?php else: ?>
								
								<p><em>Unknown Inject Type</em> - Please contact White Team</p>
								
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="panel-footer">
				<?php if ( $inject['Inject']['time_start'] > 0 ): ?>
				<p><strong>Inject Start</strong>: <?php echo date('n/j \a\t g:iA', $inject['Inject']['time_start']); ?></p>
				<?php endif; ?>

				<?php if ( $inject['Inject']['time_end'] > 0 ): ?>
				<p><strong>Inject End</strong>: <?php echo date('n/j \a\t g:iA', $inject['Inject']['time_end']); ?></p>
				<?php endif; ?>
				
				<?php if ( $completed_inject ): ?>
				<p><strong>Completed By</strong>: <?php echo $inject['User']['username']; ?> at <?php echo date('g:iA', $inject['CompletedInject']['time']); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ( empty($injects) ): ?>
<div class="panel panel-default">
	<div class="panel-body">
		<p>No injects found.</p>
	</div>
</div>
<?php endif; ?>

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

<div class="modal fade" id="helpModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Request Help</h4>
			</div>

			<div class="modal-body">
				<p>You are requesting help for <span id="helpModal-injectname"></span>. Please note the following information:</p>

				<p>What we <strong>can</strong> do:</p>
				<ul>
					<li>Clarify the inject, or taken hint(s)</li>
					<li>Tell you if something is supposed to happen</li>
					<li>Fix something <strong>we</strong> manage, that is broken</li>
				</ul>

				<p>What we <strong>can not</strong> do:</p>
				<ul>
					<li>Give hints, other than ones provided</li>
					<li>Give you answers</li>
				</ul>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="helpModal-yesRequest">Yes, Request Help</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="manualCheckModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Before we continue...</h4>
			</div>

			<div class="modal-body">
				<p>You are requesting a check for <span id="manualCheckModal-injectname"></span>. Please note the following information:</p>

				<p>When to use this:</p>
				<ul>
					<li>You believe that you have <strong>completely</strong> fulfilled the inject</li>
					<li>You are ready to show proof when requested</li>
				</ul>

				<p>When <strong>not</strong> to use this:</p>
				<ul>
					<li>You are unsure what this inject means - use the "<strong>Request Help</strong>" button.</li>
				</ul>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="manualCheckModal-yesRequest">Yes, Request A Check</button>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	InjectEngine.init('<?php echo $this->Html->url('/injects'); ?>');
});
</script>