<?php echo $this->Html->css('timeline', array('inline' => false)); ?>
<?php echo $this->Html->script('dashboard.timeline', array('inline' => false)); ?>

<h2>Dashboard - Inject Completion Timeline</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">

			<div class="panel-body" id="teams-inject-timeline">
				<em>Loading...</em>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	InjectEngine_Dashboard_Timeline.init('<?php echo $this->Html->url('/api/dashboard'); ?>');
});
</script>