<?php echo $this->Html->css('timeline', array('inline' => false)); ?>

<h2>Team Panel</h2>
<h4><?php echo $teaminfo['name']; ?></h4>

<?php echo $this->element('navbars/team', array('at_dashboard' => true)); ?>

<p>&nbsp;</p>

<?php foreach ( $data AS $d ): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			Check - <?php echo date('h:i A', $d['ScoreEngineChecks']['time']); ?>
		</h4>
	</div>
	<div class="panel-body">
		<pre>
		<?php echo $d['ScoreEngineChecks']['output']; ?>
		</pre>
	</div>
	<div class="panel-footer">
		Result: <?php echo $d['ScoreEngineChecks']['status'] == false ? 'PASSED' : 'FAILED'; ?>
	</div>
</div>
<?php endforeach; ?>
