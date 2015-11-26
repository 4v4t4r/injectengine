<h2>Scoreboard</h2>

<meta http-equiv="refresh" content="30">

<div id="scoreboard"></div>

<script type="text/javascript" src="//www.google.com/jsapi"></script>
<script>
google.load('visualization', '1.0', {'packages':['corechart', 'bar']});
google.setOnLoadCallback(drawCharts);

function drawCharts() {
	drawScoreboard();
}

function drawScoreboard() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Team');
	data.addColumn('number', 'Successful Checks');
	data.addRows([
		<?php foreach ( $data AS $d ): ?>
		['<?php echo $d['teams']['name']; ?>', <?php echo $d[0]['passed']; ?>],
		<?php endforeach; ?>
	]);

	var options = {
		width:800,
		height: 500,
		bars: 'horizontal',
		series: {
			0: {axis: 'Uptime'},
		},
		axes: {
			x: {
				Uptime: {side: 'top', label: 'Successful Checks'},
			}
		}
	};

	var chart = new google.charts.Bar(document.getElementById('scoreboard'));
	chart.draw(data, options);
}
</script>