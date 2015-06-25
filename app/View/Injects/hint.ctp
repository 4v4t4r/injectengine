<?php $enabledBtn = false; ?>

<div class="row">
	<div class="alert alert-warning">
		<strong>Warning</strong>! Each hint removes [INSERT POINTS HERE]! Blah blah super scary words
	</div>
</div>

<div class="row">
	<table class="table table-vertalign">
		<tbody>
			<tr>
				<td colspan="2">
					You are requesting a hint for "<strong><?php echo $inject['Inject']['title']; ?></strong>". 
					This inject has <strong><?php echo count($hints); ?> hint<?php echo count($hints) > 1 ? 's' : ''; ?> available</strong>.
				</td>
			</tr>
			<?php foreach ( $hints AS $hint ): ?>
			<tr>
				<td width="75%">
					<?php if ( $hint['Hint']['unlocked'] ): ?>

					<p><?php echo $hint['Hint']['description']; ?></p>

					<?php else: ?>

					<p><em>Hint not unlocked</em> - To unlock it, please click the "Reveal" button!</p>

					<?php endif; ?>
				</td>

				<?php if ( $hint['Hint']['unlocked'] ): ?>
				
				<td><button class="btn btn-primary" disabled="disabled">Reveal</button></td>

				<?php else: ?>

				<td><button class="btn btn-primary<?php echo !$enabledBtn ? ' hint-btn"' : '" disabled="disabled"'; ?>>Reveal</button></td>

				<?php $enabledBtn = true; endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>