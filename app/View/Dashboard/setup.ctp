<h2>Dashboard - Personalization Setup</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<div class="panel panel-default">
	<div class="panel-heading">Available Teams</div>
	<div class="panel-body">
		<p>Please select which team(s) you would like to see.</p>

		<form class="form-horizontal" method="post">
			<div class="form-group">
				<div class="col-md-10">
					<select multiple class="form-control" name="teams[]">
						<?php foreach ( $teams AS $team ): ?>
						<option value="<?php echo $team['Team']['id']; ?>"><?php echo $team['Team']['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-10">
					<button type="submit" class="btn btn-default">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>