<h2>Backend Panel - Inject Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('navbars/backend_injects', array('at_responses' => true)); ?>

<meta http-equiv="refresh" content="30">

<p>&nbsp;</p>

<table class="table table-bordered">
	<thead>
		<tr>
			<td>Inject ID</td>
			<td>Team</td>
			<td>Submitted</td>
			<td>Filename</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $data AS $inject ): ?>
	<tr>
		<td><?php echo $inject['Inject']['title']; ?></td>
		<td><?php echo $inject['Team']['name']; ?></td>
		<td>
			<?php echo date('m/d/Y \a\t g:iA', $inject['Attachment']['time']); ?>
		</td>
		<td>
			<?php echo $inject['Attachment']['filename']; ?>
		</td>
		<td>
			<p><?php echo $this->Html->link('View', '/backend/injects/responses/'.$inject['Attachment']['id'], array('class' => array('btn btn-xs btn-primary'))); ?></p>
		</td>
	</tr>
	<?php endforeach; ?>
	
	<?php if ( empty($data) ): ?>
	<tr>
		<td colspan="5">
			<p>There are no responses yet.</p>
		</td>
	</tr>
	<?php endif; ?>

	<tr>
		<td colspan="8">
			<a href="<?php echo $this->Html->url('/backend/injects/responses_create'); ?>" class="btn btn-primary pull-right">Manual Submit</a>
		</td>
	</tr>
	</tbody>
</table>