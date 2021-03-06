<?php echo $this->Html->css('timeline', array('inline' => false)); ?>

<h2>Team Panel</h2>
<h4><?php echo $teaminfo['name']; ?></h4>

<?php echo $this->element('navbars/team', array('at_events' => true)); ?>

<p>&nbsp;</p>

<div class="timeline">
	<div class="line text-muted"></div>

	<div class="separator"></div>

	<?php foreach ( $timeline AS $item ): ?>
	<div class="panel panel-primary panel-outline">
		<div class="panel-heading icon">
			<i class="glyphicon glyphicon-ok"></i>
		</div>

		<div class="panel-body">
			<p><strong><?php echo $item['Inject']['title']; ?></strong> was completed</p>
			<p class="text-muted"><?php echo date('n/j \a\t g:iA', $item['CompletedInject']['time']); ?></p>
		</div>
	</div>
	<?php endforeach; ?>

	<div class="panel panel-danger panel-outline">
		<div class="panel-heading icon">
			<i class="glyphicon glyphicon-plus"></i>
		</div>

		<div class="panel-body">
			<p>Start of the UB CSC Fall 2015</p>
			<p class="text-muted">11/21/2015</p>
		</div>
	</div>
</div>