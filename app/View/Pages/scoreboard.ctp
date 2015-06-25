<h2>Scoreboard</h2>

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
	data.addColumn('number', 'Points');
	data.addColumn('number', 'Uptime');
	data.addRows([
		['Team 1', 2352, 0.40],
		['Team 2', 1920, 0.75],
		['Team 3', 2500, 0.69],
		['Team 4', 1503, 0.12],
		['Team 5', 2940, 0.83]
	]);

	var options = {
		width:800,
		height: 500,
		bars: 'horizontal',
		series: {
			0: {axis: 'Points'},
			1: {axis: 'Uptime'},
		},
		axes: {
			x: {
				Points: {label: 'Points'},
				Uptime: {side: 'top', label: 'Uptime'},
			}
		}
	};

	var chart = new google.charts.Bar(document.getElementById('scoreboard'));
	chart.draw(data, options);
}
</script>