<h2>Welcome!</h2>

<p class="text-center"><img src="http://img.memecdn.com/true-camping-story_c_897237.jpg" /></p>

<?php if ( $backend_access ): ?>
<?php echo $this->Html->script('index'); ?>

<p>&nbsp;</p>
<ul class="list-group checked-list-box">
	<li class="list-group-item disabled">ToDo List Future</li>
	<li class="list-group-item">Team/Group edit name/permissions</li>
	<li class="list-group-item">Inject Page: Attachments</li>
	<li class="list-group-item">Injects: Responses - Uploaded/Manually Entered</li>
	<li class="list-group-item">Add flash messages</li>
	<li class="list-group-item">Add inject categories</li>
	<li class="list-group-item">Add inject "schedule"</li>
	<li class="list-group-item">Add incident reports</li>
	<li class="list-group-item">Move modals to elements</li>
	<li class="list-group-item">Normalize column names (ex: time_started and requested_time)</li>
	<li class="list-group-item">Normalize "statuses"
		<ul class="list-group checked-list-box">
			<li class="list-group-item">ex: some start at 0, some start at 1</li>
			<li class="list-group-item">Provide constants for various status states</li>
		</ul>
	</li>
	<li class="list-group-item">Move complex find queries to respective models, instead of dynamically through the controller</li>
	<li class="list-group-item">Move all ajax only methods to the ajax request prefix</li>
	<li class="list-group-item">Better javascript modulization/organization</li>
	<li class="list-group-item">Constants for Completition Start Date, Competition Name, Competition Logo, etc</li>
	<li class="list-group-item">Revamp logging</li>
	<li class="list-group-item">Better code organization (aka: oh gosh DashboardController, I'm so sorry) - split backend/api stuff into other controllers?</li>
	<li class="list-group-item">Make InjectController::index.ctp readable again</li>
</ul>
<?php endif; ?>