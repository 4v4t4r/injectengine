<?php echo $this->Html->css('bootstrap3-wysihtml5.min'); ?>
<?php echo $this->Html->css('bootstrap-datetimepicker.min'); ?>

<?php echo $this->Html->script('bootstrap3-wysihtml5.all.min'); ?>
<?php echo $this->Html->script('moment.min'); ?>
<?php echo $this->Html->script('bootstrap-datetimepicker.min'); ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label for="title" class="col-sm-3 control-label">Title</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="title" name="title" value="<?php echo !empty($inject) ? $inject['Inject']['title'] : ''; ?>" required="required" />
		</div>
	</div>

	<div class="form-group">
		<label for="description" class="col-sm-3 control-label">Description</label>
		<div class="col-sm-9">
			<textarea class="form-control" name="description" id="description" rows="10"></textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="group_id" class="col-sm-3 control-label">Assigned Group</label>
		<div class="col-sm-9">
			<select class="form-control" id="group_id" name="group_id" required="required">
				<?php foreach($groups AS $group): ?>
				<option value="<?php echo $group['Group']['id']; ?>"<?php echo (!empty($inject) && $inject['Inject']['group_id'] == $group['Group']['id']) ? ' selected="selected"' : ''; ?>><?php echo $group['Group']['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="dependency" class="col-sm-3 control-label">Inject Dependency</label>
		<div class="col-sm-9">
			<select class="form-control" id="dependency" name="dependency" required="required">
				<option value="0"<?php echo (!empty($inject) && $inject['Inject']['dependency'] == 0) ? ' selected="selected"' : ''; ?>>No Dependency</option>
				<?php foreach($injects AS $inj): ?>
				<option value="<?php echo $inj['Inject']['id']; ?>"<?php echo (!empty($inject) && $inject['Inject']['dependency'] == $inj['Inject']['id']) ? ' selected="selected"' : ''; ?>><?php echo $inj['Inject']['title']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="time_start" class="col-sm-3 control-label">Start Time</label>
		<div class="col-sm-9">
			<div class="input-group date datetimepicker" id="time_start_datepicker">
				<input type="text" class="form-control time-use-data" id="time_start" name="time_start" value="<?php echo !empty($inject) ? $inject['Inject']['time_start'] : ''; ?>" placeholder="0 means immediately" required="required" />
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="time_end" class="col-sm-3 control-label">End Time</label>
		<div class="col-sm-9">
			<div class="input-group date datetimepicker" id="time_end_datepicker">
				<input type="text" class="form-control time-use-data" id="time_end" name="time_end" value="<?php echo !empty($inject) ? $inject['Inject']['time_end'] : ''; ?>" placeholder="0 means immediately" required="required" />
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="active" class="col-sm-3 control-label">Enabled</label>
		<div class="col-sm-9">
			<div class="radio">
				<label>
					<input type="radio" name="active" id="activeYes" value="1"<?php echo (!empty($inject) && $inject['Inject']['active'] == 1) ? ' checked="checked"' : ''; ?> required="required">
					Yes
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="active" id="activeNo" value="0"<?php echo (!empty($inject) && $inject['Inject']['active'] == 0) ? ' checked="checked"' : ''; ?> required="required">
					No
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="type" class="col-sm-3 control-label">Type</label>
		<div class="col-sm-9">
			<div class="radio">
				<label>
					<input type="radio" name="type" id="type1" value="1"<?php echo (!empty($inject) && $inject['Inject']['type'] == 1) ? ' checked="checked"' : ''; ?> required="required">
					Flag
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="type" id="type2" value="2"<?php echo (!empty($inject) && $inject['Inject']['type'] == 2) ? ' checked="checked"' : ''; ?> required="required">
					Submission
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="type" id="type3" value="3"<?php echo (!empty($inject) && $inject['Inject']['type'] == 3) ? ' checked="checked"' : ''; ?> required="required">
					Manual Check
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="flag" class="col-sm-3 control-label">Flag (If Applicable)</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="flag" name="flag" value="<?php echo !empty($inject) ? $inject['Inject']['flag'] : ''; ?>" required="required" />
		</div>
	</div>

	<div class="form-group">
		<label for="hints_enabled" class="col-sm-3 control-label">Hints Enabled</label>
		<div class="col-sm-9">
			<div class="radio">
				<label>
					<input type="radio" name="hints_enabled" id="hints_enabledYes" value="1"<?php echo (!empty($inject) && $inject['Inject']['hints_enabled'] == 1) ? ' checked="checked"' : ''; ?> required="required">
					Yes
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="hints_enabled" id="hints_enabledNo" value="0"<?php echo (!empty($inject) && $inject['Inject']['hints_enabled'] == 0) ? ' checked="checked"' : ''; ?> required="required">
					No
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="order" class="col-sm-3 control-label">Order</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="order" name="order" value="<?php echo !empty($inject) ? $inject['Inject']['order'] : ''; ?>" required="required" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-default"><?php echo !empty($inject) ? 'Edit' : 'Create'; ?> Inject</button>
		</div>
	</div>
</form>

<script>
$(document).ready(function() {
	$('#description').wysihtml5({
		toolbar: {
			html: true,
			size: "xs",
		},
	});

	$('.datetimepicker').datetimepicker({
		sideBySide: true,
		keepInvalid: true,
	});

	<?php if ( !empty($inject) ): ?>
	$('#description').html('<?php echo addslashes($inject['Inject']['description']); ?>');
	
	$('#time_start_datepicker').data('DateTimePicker').date(moment.unix('<?php echo $inject['Inject']['time_start']; ?>'));
	$('#time_end_datepicker').data('DateTimePicker').date(moment.unix('<?php echo $inject['Inject']['time_end']; ?>'));
	<?php endif; ?>
	
	$('form').submit(function() {
		$('.datetimepicker').each(function() {
			dtp = $(this).data('DateTimePicker');
			input = $(this).children('input');

			if ( !$.isNumeric(input.val()) ) {
				// Not a number. Let's get the date from DTP
				input.val(dtp.date().unix());
			}
		});
	});
});
</script>