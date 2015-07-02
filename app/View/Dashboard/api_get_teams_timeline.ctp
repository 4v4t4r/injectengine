<div class="timeline">
	<div class="line text-muted"></div>

	<div class="separator"></div>

	<?php foreach ( $timeline AS $item ): ?>
	<div class="panel panel-primary panel-outline">
		<div class="panel-heading icon">
			<i class="glyphicon glyphicon-ok"></i>
		</div>

		<div class="panel-body">
			<p><?php echo $item['Team']['name']; ?> completed <?php echo $item['Inject']['title']; ?></p>
			<p class="text-muted"><?php echo date('n/j \a\t g:iA', $item['CompletedInject']['time']); ?></p>
		</div>
	</div>
	<?php endforeach; ?>

	<div class="panel panel-danger panel-outline">
		<div class="panel-heading icon">
			<i class="glyphicon glyphicon-plus"></i>
		</div>

		<div class="panel-body">
			<p>Start of the Cyber Simulation</p>
			<p class="text-muted">MANUALLY ENTER DATE IN TEMPLATE</p>
		</div>
	</div>
</div>