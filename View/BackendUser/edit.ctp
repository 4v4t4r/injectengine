<h2>Backend Panel - User Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('navbars/backend_user', array('at_list' => true)); ?>

<p>&nbsp;</p>

<?php echo $this->element('forms/backend_user', array('user' => $user, 'teams' => $teams)); ?>