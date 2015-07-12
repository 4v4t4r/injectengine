<div class="panel panel-default">
	<?php foreach ( $teams AS $team ): ?>
	<div class="panel-heading" role="tab">
		<h4 class="panel-title">
			<a class="" role="button" data-toggle="collapse" href="#team<?php echo $team['Team']['id']; ?>">
				<?php echo $team['Team']['name']; ?>
			</a>
		</h4>
	</div>
	<div id="team<?php echo $team['Team']['id']; ?>" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-4 text-center" style="border-right: thin solid #000;">
					<p>Team Status</p>

					<?php if ( $team['Help']['status'] == null ): ?>
					
					<p style="font-size:100px; color: green;">&#9786;</p>
					
					<?php elseif ( $team['Help']['status'] == 1 ): ?>

					<p style="font-size:100px; color: red;">&#9786;</p>
					<p>Requested <?php echo $this->Time->timeAgoInWords($team['Help']['requested_time']); ?></p>

					<?php elseif ( $team['Help']['status'] == 2): ?>

					<p style="font-size:100px; color: orange;">&#9786;</p>
					<p>Acknowledged <?php echo $this->Time->timeAgoInWords($team['Help']['time_started']); ?> by <?php echo $team['HelpUser']['username']; ?></p>

					<?php else: ?>

					<p style="font-size:100px; color: red;">ERROR</p>

					<?php endif; ?>
				</div>
				<!--
				<div class="col-sm-4 text-center">
					<p>Team Members</p>

					<ul class="list-group">
						<?php foreach ( $team['User'] AS $user ): ?>
						<li class="list-group-item"><?php echo $user['username']; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				-->
				<div class="col-sm-8 text-center">
					<p>Team Health</p>

					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>Current Inject</td>
								<td><?php echo ($team['CurrentInject']['title'] !== null ? $team['CurrentInject']['title'] : 'N/A'); ?></td>
								<td></td>
							</tr>
							<tr>
								<td>Needs Help</td>

								<?php if ( $team['Help']['status'] === null ): ?>

								<td>N/A</td>
								<td>N/A</td>

								<?php else: ?>
								
								<td><?php echo $team['HelpInject']['title']; ?></td>

								<?php if ( $team['Help']['status'] == 1 || $team['Help']['status'] == 2 ): ?>

								<td><a href="<?php echo $this->Html->url('/dashboard/help/'.$team['Help']['id']); ?>" class="btn btn-xs btn-primary">View</a></td>

								<?php endif; ?>

								<?php endif; ?>
							</tr>
							<?php if ( empty($team['RequestedChecks']) ): ?>

							<tr>
								<td>Needs Checking</td>
								<td>N/A</td>
								<td>N/A</td>
							</tr>

							<?php else: ?>

							<?php foreach ( $team['RequestedChecks'] AS $i => $requestedCheck ): ?>
							<tr>
								<td>Needs Checking (#<?php echo $i+1; ?>)</td>
								<td><?php echo $requestedCheck['Inject']['title']; ?></td>
								<td><a href="<?php echo $this->Html->url('/dashboard/check/'.$requestedCheck['RequestedCheck']['id']); ?>" class="btn btn-xs btn-primary">Start</a>
							</tr>
							<?php endforeach; ?>

							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>

