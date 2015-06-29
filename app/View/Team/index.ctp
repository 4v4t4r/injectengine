<h2>Team Panel</h2>
<h4><?php echo $teaminfo['name']; ?></h4>

<ul class="nav nav-pills">
	<li class="active"><a href="#">Team Dashboard</a></li>
	<li class=""><a href="#">Account Info Change</a></li>
	<li class=""><a href="#">Service Configuration</a></li>
</ul>

<p>&nbsp;</p>

<div class="panel panel-default">
	<div class="panel-heading">Team Membership</div>

	<ul class="list-group">
		<?php foreach ( $members AS $member ): ?>
		<li class="list-group-item"><?php echo $member['User']['username']; ?></li>
		<?php endforeach; ?>
	</ul>
</div>
