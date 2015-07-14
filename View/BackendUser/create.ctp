<h2>Backend Panel - User Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('backend_user_navbar', array('at_create' => true)); ?>

<p>&nbsp;</p>

<?php echo $this->element('backend_user_form', array('user' => array(), 'teams' => $teams)); ?>