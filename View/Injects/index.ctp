<?php
echo $this->Html->script('injectengine', array('inline' => false));

$this->Inject->setup($injects);
?>

<h2>Injects</h2>

<?php if ( !empty($injects) ): ?>
<div class="panel-group" id="accordion">

	<?php 
		foreach ( $injects AS $inject ) {
			if ( !$this->Inject->canShow($inject) ) continue;

			$completed_inject = $this->Inject->completed($inject);;
			$expired_inject = (!$completed_inject && $this->Inject->expired($inject));
			$check_requested = $this->Inject->checkRequested($inject);

			echo $this->element(
				'injects/'.$this->Inject->getElementNameFromType($inject['Inject']['type']),
				compact('inject')
			);
		}
	?>

</div>

<?php else: ?>

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