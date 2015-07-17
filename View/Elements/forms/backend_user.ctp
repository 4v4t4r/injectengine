<?php
	$teamsArr = array();
	foreach ( $teams AS $team ) { $teamsArr[$team['Team']['id']] = $team['Team']['name']; }

	echo $this->Html->css('bootstrap-datetimepicker.min', array('inline' => false));
	echo $this->Html->script('moment.min', array('inline' => false));
	echo $this->Html->script('bootstrap-datetimepicker.min', array('inline' => false));

	echo $this->Form->create('User');
	echo $this->Form->input('Username');
	echo $this->Form->input('Password');
	echo $this->Form->input('team_id', array('options' => $teamsArr));
	
	echo $this->Form->input('expires', array(
		'type'    => 'text',
		'between' => '<div class="col-lg-10"><div class="input-group date datetimepicker" id="expires_datepicker">',
		'after'   => '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div>',
	));
?>

<div class="row">
	<div class="col-sm-10 col-sm-offset-2">
		<p class="help-block">Please enter "0" if this account will never expire</p>
	</div>
</div>

<?php
	echo $this->Form->input('enabled', array('type' => 'checkbox', 'text' => 'Account Enabled'));
	echo $this->Form->end( (!empty($user) ? 'Edit' : 'Create').' User' )
?>

<script>
$(document).ready(function() {
	$('.datetimepicker').datetimepicker({
		sideBySide: true,
		keepInvalid: true,
	});

	<?php if ( !empty($user) && $user['User']['expires'] > 0 ): ?>
	$('#expires_datepicker').data('DateTimePicker').date(moment.unix('<?php echo $user['User']['expires']; ?>'));
	<?php endif; ?>

	$('form').submit(function() {
		$('.datetimepicker').each(function() {
			dtp = $(this).data('DateTimePicker');
			input = $(this).children('input');

			if ( !$.isNumeric(input.val()) ) {
				// Not a number. Let's get the date from DTP
				input.val(dtp.date().utc().unix());
			}
		});
	});
});
</script>