<?php
$teams_arr  = array();
$teams_id   = array();
foreach ( $teams AS $team ) { $teams_arr[] = $team['Team']['name']; $teams_id[] = $team['Team']['id']; }

echo $this->Html->script('dashboard.personal');
?>

<h2>Dashboard - <?php echo implode(', ', $teams_arr); ?></h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="panel-group" id="teamStatus-group">
	<em>Loading...</em>
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

<script>
$(document).ready(function() {
	InjectEngine_Dashboard_Personal.init('<?php echo $this->Html->url('/api/dashboard'); ?>', '<?php echo implode(',', $teams_id); ?>');
});
</script>