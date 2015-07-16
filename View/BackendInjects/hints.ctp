<?php
$items_in_row = 0;

echo $this->Html->css('bootstrap3-wysihtml5.min', array('inline' => false));
echo $this->Html->css('bootstrap-datetimepicker.min', array('inline' => false));

echo $this->Html->script('bootstrap3-wysihtml5.all.min', array('inline' => false));
echo $this->Html->script('moment.min', array('inline' => false));
echo $this->Html->script('bootstrap-datetimepicker.min', array('inline' => false));
echo $this->Html->script('backend.hint', array('inline' => false));
?>

<h2>Backend Panel - Inject Manager</h2>
<h4><?php echo $teaminfo['name']; ?> (<?php echo $groupinfo['name']; ?>)</h4>

<?php echo $this->element('navbars/backend_injects', array('at_hints' => true)); ?>

<p>&nbsp;</p>

<?php foreach ( $injects AS $inject ): ?>

<?php if ( $items_in_row == 0 ): ?>
<div class="row">
<?php endif; ?>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo $inject['Inject']['title']; ?>
			</div>

			<ul class="list-group">
				<?php foreach ( $inject['Hint'] AS $hint ): ?>
				<li class="list-group-item">
					<h4 class="list-group-item-heading">
						Hint #<?php echo $hint['order']; ?>
						<a href="#hintEdit" class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-id="<?php echo $hint['id']; ?>">
							EDIT
						</a>
					</h4>
					<p class="list-group-item-text"><?php echo $hint['description']; ?></p>
				</li>
				<?php endforeach; ?>

				<?php if ( count($inject['Hint']) == 0): ?>
				<li class="list-group-item">There are no hints assigned to this inject.</li>
				<?php endif; ?>

				<a href="#hintAdd" class="list-group-item" data-toggle="modal" data-id="<?php echo $inject['Inject']['id']; ?>" data-name="<?php echo $inject['Inject']['title']; ?>">
					<span class="glyphicon glyphicon-plus"></span> Add a hint to this inject
				</a>
			</ul>
		</div>
	</div>

<?php if ( $items_in_row == 1 ): $items_in_row = -1;?>
</div>
<?php endif; ?>

<?php $items_in_row++; ?>
<?php endforeach; ?>

<?php if ( $items_in_row != 0 ): ?>
</div>
<?php endif; ?>

<?php
	echo $this->element('modals/form', array(
		'id'     => 'hintAdd',
		'title'  => 'Add Hint To <span id="hintAdd-injectname">...</span>',

		'body'   => '<span class="hidden" id="hintAdd-id"></span>'.
					$this->element('forms/backend_hint', array('prefix' => 'add')),

		'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'.
					'<button type="submit" class="btn btn-primary" id="hintAdd-addBtn">Add Hint!</button>',
	));
?>

<?php
	echo $this->element('modals/form', array(
		'id'     => 'hintEdit',
		'title'  => 'Editing Hint #<span id="hintEdit-number">...</span> For <span id="hintEdit-injectname">...</span>',

		'body'   => '<span class="hidden" id="hintEdit-id"></span>'.
					$this->element('forms/backend_hint', array('prefix' => 'edit')),

		'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'.
					'<button type="submit" class="btn btn-primary" id="hintEdit-addBtn">Edit Hint!</button>',
	));
?>

<script>
$(document).ready(function() {
	InjectEngine_Backend_Hint.init('<?php echo $this->Html->url('/backend/injects'); ?>');
});
</script>