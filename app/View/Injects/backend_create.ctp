<h2>Backend Panel - Inject Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('backend_injects_navbar', array('at_create' => true)); ?>

<p>&nbsp;</p>

<?php echo $this->element('backend_inject_form', array('inject' => array(), 'groups' => array(), 'injects' => array())); ?>