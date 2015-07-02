<?php echo $this->Html->script('dashboard.help'); ?>

<h2>Dashboard - Respond to Help Request</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="panel panel-default">
	<div class="panel-heading">Help Request</div>
	<div class="panel-body">
		<table class="table">
			<tbody>
				<tr>
					<td>
						<h4>Inject Description</h4>
						<?php echo $help['Inject']['description']; ?>
					</td>
					<td class="text-left text-nowrap">
						<h4>Information</h4>

						<?php if ( $help['Inject']['time_start'] > 0 ): ?>
						<p>Start: <?php echo date('n/j \a\t g:iA', $help['Inject']['time_start']); ?></p>
						<?php else: ?>
						<p>Start: Immediately</p>
						<?php endif; ?>

						<?php if ( $help['Inject']['time_end'] > 0 ): ?>
						<p>End: <?php echo date('n/j \a\t g:iA', $help['Inject']['time_end']); ?></p>
						<?php else: ?>
						<p>End: Never</p>
						<?php endif; ?>

						<p>Requested: <?php echo $this->Time->timeAgoInWords($help['Help']['requested_time']); ?></p>
						<p>Requested By: <?php echo $help['User']['username']; ?></p>

						<?php if ( $help['Help']['assigned_user_id'] > 0 ): ?>
						<p>Assigned: <?php echo $assigned_user['User']['username']; ?></p>
						<?php endif; ?>

						<?php if ( $help['Help']['time_started'] > 0 ): ?>
						<p>Started: <?php echo date('n/j \a\t g:iA', $help['Help']['time_started']); ?></p>
						<?php endif; ?>

						<?php if ( $help['Help']['time_finished'] > 0 ): ?>
						<p>Finished: <?php echo date('n/j \a\t g:iA', $help['Help']['time_finished']); ?></p>
						<p>Time Taken: <?php echo $help['Help']['time_finished'] - $help['Help']['time_started']; ?> seconds</p>
						<?php endif; ?>

						<?php if ( $help['Inject']['type'] == 1 ): ?>
						<p>Flag: <strong><?php echo $help['Inject']['flag']; ?></strong></p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4>White Team Explanation - Keep Secret!</h4>
						<?php echo $help['Inject']['explanation']; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer clearfix">
		<div class="pull-right">
			<button class="btn btn-warning<?php echo $help['Help']['status'] == 1 ? '' : ' hidden'; ?>" id="helpButton-ack">Acknowledge</button>
			<button class="btn btn-success<?php echo $help['Help']['status'] == 2 ? '' : ' hidden'; ?>" id="helpButton-fin">Finish</button>
			<a href="<?php echo $this->Html->url('/dashboard/personal/'.$help['Help']['requested_team_id']); ?>" class="btn btn-primary<?php echo $help['Help']['status'] == 3 ? '' : ' hidden'; ?>" id="helpButton-done">Return To Team Dashboard</a>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	InjectEngine_Dashboard_Help.init('<?php echo $this->Html->url('/dashboard/help/'.$help['Help']['id']); ?>');
});
</script>