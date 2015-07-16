<?php echo $this->Html->script('dashboard.overview', array('inline' => false)); ?>
<?php echo $this->Html->script('//www.google.com/jsapi', array('inline' => false)); ?>

<h2>Dashboard - Overview</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="panel-group" id="teamStatus-group">
	<em>Loading...</em>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Inject Completion Rates</div>

			<div class="panel-body" id="inject-completion-rates">
				<em>Loading...</em>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Team Inject Standings</div>

			<div class="panel-body" id="team-inject-standings">
				<em>Loading...</em>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Hint Usage Per Team</div>

			<div class="panel-body" id="hint-usage-per-team">
				<em>Loading...</em>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Service Downtime (Overall)</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200?text=Coming+Soon!" class="center-block" />
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Server Downtime (Overall)</div>

			<div class="panel-body">
				<img src="//placehold.it/300x200?text=Coming+Soon!" class="center-block" />
			</div>
		</div>
	</div>
</div>

<script>
google.load('visualization', '1', {
	callback: function() {
		InjectEngine_Dashboard_Overview.init('<?php echo $this->Html->url('/api/dashboard'); ?>');
	},
	packages: ['corechart']
});
</script>