<h2>Backend Panel - Inject Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('navbars/backend_injects', array('at_create' => true)); ?>

<p>&nbsp;</p>

<?php echo $this->element('forms/backend_inject', array('inject' => array(), 'groups' => $groups, 'injects' => $injects)); ?>