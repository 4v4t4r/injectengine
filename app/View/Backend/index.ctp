<script type="text/javascript" src="//www.google.com/jsapi"></script>

<h2>Backend Panel - Dashboard</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Team Inject Standings</div>

			<div class="panel-body">
				<div id="team-inject-placements"></div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Inject Completion Rates</div>

			<div class="panel-body">
				<div id="inject-completion-rates"></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Hint Usage Per Team</div>

			<div class="panel-body">
				<em>Coming soon...</em>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Hint Usage Per Inject</div>

			<div class="panel-body">
				<em>Coming soon...</em>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Service Downtime (Overall)</div>

			<div class="panel-body">
				<em>Coming soon...</em>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Server Downtime (Overall)</div>

			<div class="panel-body">
				<em>Coming soon...</em>
			</div>
		</div>
	</div>
</div>

<script>
google.load('visualization', '1.0', {'packages':['corechart']});
google.setOnLoadCallback(drawCharts);

function drawCharts() {
	drawInjectPlacements();
	drawCompletionRates();
}

function drawInjectPlacements() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Team');
	data.addColumn('number', 'Inject');
	data.addRows([
		['Team 1', 1],
		['Team 2', 3],
		['Team 3', 1],
		['Team 4', 5],
		['Team 5', 2]
	]);

	var options = {'width':300, 'height': 200};

	var chart = new google.visualization.PieChart(document.getElementById('team-inject-placements'));
	chart.draw(data, options);
}

function drawCompletionRates() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Inject');
	data.addColumn('number', 'Completions');
	data.addRows([
		['Inject 1', 3],
		['Inject 2', 2],
		['Inject 3', 1],
		['Inject 4', 1],
		['Inject 5', 0]
	]);

	var options = {'width':300, 'height': 200};

	var chart = new google.visualization.BarChart(document.getElementById('inject-completion-rates'));
	chart.draw(data, options);
}
</script>
