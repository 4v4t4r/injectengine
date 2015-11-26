<h2>Dashboard - Respond to Check Request</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="panel panel-default">
	<div class="panel-heading"><?php echo $check['Inject']['title']; ?></div>
	<div class="panel-body">
		<table class="table">
			<tbody>
				<tr>
					<td>
						<h4>Inject Description</h4>
						<?php echo $check['Inject']['description']; ?>
					</td>
					<td class="text-left text-nowrap">
						<?php if ( $check['Inject']['time_start'] > 0 ): ?>
						<p>Start: <?php echo date('n/j \a\t g:iA', $check['Inject']['time_start']); ?></p>
						<?php else: ?>
						<p>Start: Immediately</p>
						<?php endif; ?>

						<?php if ( $check['Inject']['time_end'] > 0 ): ?>
						<p>End: <?php echo date('n/j \a\t g:iA', $check['Inject']['time_end']); ?></p>
						<?php else: ?>
						<p>End: Never</p>
						<?php endif; ?>

						<p>Requested: <?php echo $this->Time->timeAgoInWords($check['RequestedCheck']['time_requested']); ?></p>
						<p>Requested By: <?php echo $check['User']['username']; ?></p>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4>White Team Explanation - Keep Secret!</h4>
						<?php echo $check['Inject']['explanation']; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer clearfix">
		<div class="pull-right">
			<form class="form-horizontal" method="post">
				<button type="submit" class="btn btn-danger" name="action" value="0">Deny</button>
				<button type="submit" class="btn btn-success" name="action" value="1">Approve</button>
			</form>
		</div>
	</div>
</div>
