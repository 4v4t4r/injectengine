<h2>Backend Panel - Inject Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('navbars/backend_injects', array('at_list' => true)); ?>

<p>&nbsp;</p>

<table class="table table-vertalign">
	<thead>
		<tr>
			<td>Title</td>
			<td>Status</td>
			<td>Group</td>
			<td>Type</td>
			<td>Starts</td>
			<td>Expires</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $injects AS $inject ): ?>
	<tr>
		<td><?php echo $inject['Inject']['title']; ?></td>
		<td><?php echo ($inject['Inject']['active'] == 1) ? 'Active' : 'Inactive'; ?></td>
		<td><?php echo $inject['Group']['name']; ?></td>
		<td><?php echo $inject['Inject']['type_name']; ?></td>
		<td>
			<?php echo ($inject['Inject']['time_start'] > 0) ? date('m/d/Y \a\t g:iA', $inject['Inject']['time_start']) : 'Immediately'; ?>
		</td>
		<td>
			<?php echo ($inject['Inject']['time_end'] > 0) ? date('m/d/Y \a\t g:iA', $inject['Inject']['time_end']) : 'Immediately'; ?>
		</td>
		<td class="text-center">
			<p><?php echo $this->Html->link('Edit', '/backend/injects/edit/'.$inject['Inject']['id'], array('class' => array('btn btn-xs btn-primary'))); ?></p>
			<p><?php echo $this->Html->link('Toggle Status', '/backend/injects/toggleStatus/'.$inject['Inject']['id'], array('class' => array('btn btn-xs btn-primary'))); ?></p>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="8">
			<a href="<?php echo $this->Html->url('/backend/injects/create'); ?>" class="btn btn-primary pull-right">New Inject</a>
		</td>
	</tr>
	</tbody>
</table>