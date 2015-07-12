<h2>Backend Panel - User Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('backend_user_navbar', array('at_list' => true)); ?>

<p>&nbsp;</p>

<table class="table">
	<thead>
		<tr>
			<td>User ID</td>
			<td>Username</td>
			<td>Team</td>
			<td>Status</td>
			<td>Expires</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $users AS $user ): ?>
		<tr>
			<td><?php echo $user['User']['id']; ?></td>
			<td><?php echo $user['User']['username']; ?></td>
			<td><?php echo $user['Team']['name']; ?></td>
			<td><?php echo $user['User']['active'] == 1 ? 'Enabled' : 'Disabled'; ?></td>
			<td><?php echo ($user['User']['expires'] == 0) ? 'Never' : date('m/d/Y \a\t g:iA', $user['User']['expires']); ?></td>
			<td>
				<?php echo $this->Html->link('Edit', '/backend/user/edit/'.$user['User']['id']); ?>
				
				<?php if ( $userinfo['id'] != $user['User']['id'] ): ?>

				| <?php echo $this->Html->link('Toggle Status', '/backend/user/toggleActive/'.$user['User']['id']); ?> 
				| <?php echo $this->Html->link('Emulate User', '/backend/user/emulate/'.$user['User']['id']); ?>

				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr>
		<td colspan="8">
			<a href="<?php echo $this->Html->url('/backend/user/create'); ?>" class="btn btn-primary pull-right">New User</a>
		</td>
	</tr>
	</tbody>
</table>
	
