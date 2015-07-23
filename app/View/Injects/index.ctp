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

<?php
	echo $this->element('modals/general', array(
		'id'     => 'hintModal',
		'title'  => 'Request A Hint',

		'body'   => '<em>Loading...</em>',
		
		'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>',
	));
?>

<?php
	echo $this->element('modals/general', array(
		'id'     => 'helpModal',
		'title'  => 'Request Help',

		'body'   => '<p>You are requesting help for <span id="helpModal-injectname"></span>. Please note the following information:</p>'.
				'<p>What we <strong>can</strong> do:</p>'.
				'<ul>'.
				'	<li>Clarify the inject, or taken hint(s)</li>'.
				'	<li>Tell you if something is supposed to happen</li>'.
				'	<li>Fix something <strong>we</strong> manage, that is broken</li>'.
				'</ul>'.
				'<p>What we <strong>can not</strong> do:</p>'.
				'<ul>'.
				'	<li>Give hints, other than ones provided</li>'.
				'	<li>Give you answers</li>'.
				'</ul>',
		
		'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'.
				'<button type="button" class="btn btn-primary" id="helpModal-yesRequest">Yes, Request Help</button>',
	));
?>

<?php
	echo $this->element('modals/general', array(
		'id'     => 'manualCheckModal',
		'title'  => 'Before we continue...',

		'body'   => '<p>You are requesting a check for <span id="manualCheckModal-injectname"></span>. Please note the following information:</p>'.
				'<p>When to use this:</p>'.
				'<ul>'.
				'	<li>You believe that you have <strong>completely</strong> fulfilled the inject</li>'.
				'	<li>You are ready to show proof when requested</li>'.
				'</ul>'.
				'<p>When <strong>not</strong> to use this:</p>'.
				'<ul>'.
				'	<li>You are unsure what this inject means - use the "<strong>Request Help</strong>" button.</li>'.
				'</ul>',
		
		'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'.
				'<button type="button" class="btn btn-primary" id="manualCheckModal-yesRequest">Yes, Request Help</button>',
	));
?>

<script>
$(document).ready(function() {
	InjectEngine.init('<?php echo $this->Html->url('/injects'); ?>');
});
</script>