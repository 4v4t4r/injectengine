<div class="panel panel-default">
	<div class="panel-heading">Team Status Overview</div>
	<div class="panel-body">
		<table class="table">
			<thead>
				<tr>
					<td>Name</td>
					<td>Status</td>
					<td>Current Inject</td>
					<td>Actions</td>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $teams AS $team ): ?>
				<tr>
					<td><?php echo $team['Team']['name']; ?></td>

					<td>
						<?php if ( $team['Help']['status'] == null ): ?>
						
						<span class="label label-success">OKAY</span>
						
						<?php elseif ( $team['Help']['status'] == 1 ): ?>

						<span class="label label-danger">NEEDS HELP</span>

						<?php elseif ( $team['Help']['status'] == 2): ?>

						<span class="label label-warning">BEING HELPED</span>

						<?php else: ?>

						ERROR

						<?php endif; ?>

						<?php if ( !empty($team['RequestedChecks']) ): ?>
						<span class="label label-info">WAITING FOR CHECK (<?php echo count($team['RequestedChecks']); ?>)</span>
						<?php endif; ?>
					</td>

					<td><?php echo ($team['CurrentInject']['title'] !== null ? $team['CurrentInject']['title'] : 'N/A'); ?></td>

					<td>
						<a href="<?php echo $this->Html->url('/dashboard/personal/'.$team['Team']['id']); ?>" class="btn btn-sm btn-primary">VIEW</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>