<h2>Backend Panel - Inject Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('navbars/backend_injects', array('at_list' => true)); ?>

<p>&nbsp;</p>

<?php echo $this->element('forms/backend_inject', array('inject' => $inject, 'groups' => $groups, 'injects' => $injects)); ?>