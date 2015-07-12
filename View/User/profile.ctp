<h2>My Profile</h2>

<?php if ( $saved ): ?>
<div class="alert alert-success">Profile updated!</div>
<?php endif; ?>

<?php if ( $error ): ?>
<div class="alert alert-danger">You entered the wrong current password.</div>
<?php endif; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-3 control-label">Username</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" value="<?php echo $userinfo['username']; ?>" readonly="readonly" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">Team Membership</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" value="<?php echo $teaminfo['name']; ?>" readonly="readonly" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">Group Membership</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" value="<?php echo $groupinfo['name']; ?>" readonly="readonly" />
		</div>
	</div>

	<div class="form-group">
		<label for="old_password" class="col-sm-3 control-label">Current Password</label>
		<div class="col-sm-9">
			<input type="password" class="form-control" id="old_password" name="old_password" value="" />
		</div>
	</div>

	<div class="form-group">
		<label for="new_password" class="col-sm-3 control-label">New Password</label>
		<div class="col-sm-9">
			<input type="password" class="form-control" id="new_password" name="new_password" value="" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-default">Update Profile</button>
		</div>
	</div>
</form>