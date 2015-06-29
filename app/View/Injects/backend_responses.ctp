<h2>Backend Panel - Inject Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('backend_injects_navbar', array('at_responses' => true)); ?>

<p>&nbsp;</p>

<table class="table table-vertalign">
	<thead>
		<tr>
			<td>Inject ID</td>
			<td>Team</td>
			<td>Submitted</td>
			<td>Status</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( array() AS $inject ): ?>
	<tr>
		<td><?php echo $inject['Inject']['title']; ?></td>
		<td><?php echo $inject['Inject']['type_name']; ?></td>
		<td>
			<?php echo ($inject['Inject']['time_start'] > 0) ? date('m/d/Y \a\t g:iA', $inject['Inject']['time_start']) : 'Immediately'; ?>
		</td>
		<td>
			<?php echo ($inject['Inject']['time_end'] > 0) ? date('m/d/Y \a\t g:iA', $inject['Inject']['time_end']) : 'Immediately'; ?>
		</td>
		<td class="text-center">
			<p><?php echo $this->Html->link('View', '/backend/injects/response_view/'.$inject['Inject']['id'], array('class' => array('btn btn-xs btn-primary'))); ?></p>
		</td>
	</tr>
	<?php endforeach; ?>
	
	<?php if ( empty(array()) ): ?>
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