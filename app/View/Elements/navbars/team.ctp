<ul class="nav nav-pills">
	
	<li class="<?php echo isset($at_dashboard) ? 'active' : ''; ?>"><?php echo $this->Html->link('Team Dashboard', '/team'); ?></li>

	<!--
	<li class="<?php echo isset($at_events) ? 'active' : ''; ?>"><?php echo $this->Html->link('Team Events', '/team/events'); ?></li>

	<li class="<?php echo isset($at_membership) ? 'active' : ''; ?>"><?php echo $this->Html->link('Team Membership', '/team/membership'); ?></li>
	-->

	<li class="<?php echo isset($at_config) ? 'active' : ''; ?>"><?php echo $this->Html->link('Scoring Engine Config', '/team/config'); ?></li>
</ul>