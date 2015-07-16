<?php
$items_in_row = 0;

echo $this->Html->script('backend.group');
?>

<h2>Backend Panel - User Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('navbars/backend_user', array('at_groups' => true)); ?>

<p>&nbsp;</p>

<?php foreach ( $groups AS $group ): ?>

<?php if ( $items_in_row == 0 ): ?>
<div class="row">
<?php endif; ?>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php if ( count($group['Team']) == 0 ): ?>
					<form class="form-inline" method="post">
						<input type="hidden" name="op" value="3" />
						<input type="hidden" name="id" value="<?php echo $group['Group']['id']; ?>" />

						<button type="submit" class="btn btn-xs btn-danger pull-right" onclick="return confirm('Are you sure you wish to delete this group?')">DELETE</button>
					</form>
				<?php endif; ?>
				<?php echo $group['Group']['name']; ?>
			</div>

			<ul class="list-group">
				<?php foreach ( $group['Team'] AS $team ): ?>
				<li class="list-group-item"><?php echo $team['name']; ?></li>
				<?php endforeach; ?>

				<?php if ( count($group['Team']) == 0): ?>
				<li class="list-group-item">There are no teams assigned to this group.</li>
				<?php endif; ?>

				<a href="#teamAdd" class="list-group-item" data-toggle="modal" data-gid="<?php echo $group['Group']['id']; ?>" data-name="<?php echo $group['Group']['name']; ?>">
					<span class="glyphicon glyphicon-plus"></span> Add a team to this group
				</a>
			</ul>
		</div>
	</div>

<?php if ( $items_in_row == 1 ): $items_in_row = -1;?>
</div>
<?php endif; ?>

<?php $items_in_row++; ?>
<?php endforeach; ?>

<?php if ( $items_in_row == 0 ): ?>
<div class="row">
<?php endif; ?>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				Create a new group
			</div>

			<div class="panel-body">
				<form class="form-horizontal" id="groupCreate-form" method="post">
					<input type="hidden" name="op" value="2" />

					<div class="form-group">
						<div class="col-sm-10">
							<input type="text" class="form-control" name="group_name" placeholder="Group Name">
						</div>
					</div>

					<div class="form-group">
						<label for="backendAccessYes" class="col-sm-5 control-label">Backend Access</label>
						<div class="col-sm-7">
							<div class="radio">
								<label>
									<input type="radio" name="backend_access" id="backendAccessYes" value="1">
									Yes
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="backend_access" id="backendAccessNo" value="0">
									No
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-10">
							<button type="submit" class="btn btn-default">Create!</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php if ( $items_in_row != 0 ): ?>
</div>
<?php endif; ?>

<div class="modal fade" id="teamAdd">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" id="teamAdd-form" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Add Team To <span id="teamAdd-groupname">...</span></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="op" value="1" />
					<input type="hidden" name="gid" id="teamAdd-gid" value="" />

					<div class="form-group">
						<label for="team" class="col-sm-2 control-label">Team</label>
						<div class="col-sm-10">
							<select class="form-control" name="tid" id="teamAdd-select">
								<option value="-1">Loading...</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="teamAdd-addBtn">Add Team!</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	InjectEngine_Backend_Group.init('<?php echo $this->Html->url('/backend/user/'); ?>');
});
</script>