<h2>Dashboard - Overview</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="panel-group" id="teamStatus-group">
	<div class="panel panel-default">
		<?php foreach ( $teams AS $team ): ?>
		<div class="panel-heading" role="tab">
			<h4 class="panel-title">
				<a class="<?php echo $team['Help']['status'] == null ? 'collapsed' : ''; ?>" role="button" data-toggle="collapse" href="#team<?php echo $team['Team']['id']; ?>">
					<?php echo $team['Team']['name']; ?>
				</a>
			</h4>
		</div>
		<div id="team<?php echo $team['Team']['id']; ?>" class="panel-collapse collapse<?php echo $team['Help']['status'] == null ? '' : ' in'; ?>" role="tabpanel">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-6 text-center" style="border-right: thin solid #000;">
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

						<a href="<?php echo $this->Html->url('/dashboard/personal/'.$team['Team']['id']); ?>" class="btn btn-primary">VIEW</a>
					</div>
					<div class="col-sm-6 text-center">
						<p>Team Members</p>

						<ul class="list-group">
							<?php foreach ( $team['User'] AS $user ): ?>
							<li class="list-group-item"><?php echo $user['username']; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Team Inject Standings</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200" />
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Inject Completion Rates</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200" />
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Hint Usage Per Team</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200" />
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Hint Usage Per Inject</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200" />
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Service Downtime (Overall)</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200" />
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Server Downtime (Overall)</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200" />
			</div>
		</div>
	</div>
</div>

