<?php echo $this->Html->css('timeline', array('inline' => false)); ?>

<h2>Team Panel</h2>
<h4><?php echo $teaminfo['name']; ?></h4>

<?php echo $this->element('navbars/team', array('at_dashboard' => true)); ?>

<p>&nbsp;</p>

<div class="row">
<?php foreach ( $data AS $i => $d ): ?>
	<?php if ( $i % 3 == 0 ): ?>
</div>
<div class="row">
	<?php endif; ?>

	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $d['services']['name']; ?></div>
			<div class="panel-body text-center">
				<h1><?php echo round($d[0]['passed'] / $d[0]['total'], 3) * 100; ?>%</h1>
				<h3>(<?php echo $d[0]['passed']; ?>/<?php echo $d[0]['total']; ?>)</h3>
				<!--<h4>Latest: <?php echo isset($latest[$d['services']['name']]) ? $latest[$d['services']['name']]['status'] == false ? 'UP' : 'DOWN' : 'N/A'; ?></h4>-->
			</div>
			<div class="panel-footer text-right">
				<?php echo $this->Html->link('More Information', '/team/service/'.$d['services']['id']); ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>
</div>
