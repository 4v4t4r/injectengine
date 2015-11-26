<h2>Backend Panel - Logs</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php if ( $filtering ): ?>
<div class="alert alert-info">
	Filtering for logs of type "<?php echo $filtering_type; ?>". Click <?php echo $this->Html->link('here', '/backend/logs'); ?> to remove.
</div>
<?php endif; ?>

<table class="table">
	<thead>
		<tr>
			<td>ID</td>
			<?php if ( !$filtering ): ?>
				<td>Type</td>
			<?php endif; ?>
			<td>Text</td>
			<td>Time</td>
			<td>IP Address</td>
			<td>Related User</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $logs AS $log ): ?>
		<tr>
			<td><?php echo $log['Log']['id']; ?></td>

			<?php if ( !$filtering ): ?>
				<td><?php echo $this->Html->link($log['Log']['type'], '/backend/logs/filter/'.$log['Log']['type']); ?></td>
			<?php endif; ?>

			<td><?php echo $log['Log']['text']; ?></td>
			<td><?php echo date('m/d/Y \a\t g:iA', $log['Log']['time']); ?></td>
			<td><?php echo $log['Log']['ip_address']; ?></td>
			<td><?php echo ($log['User']['username'] != null) ? $log['User']['username'] : 'N/A'; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<ul class="pagination">
	<?php
		echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
		echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
		echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
	?>
</ul>