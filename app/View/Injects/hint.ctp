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

					<p><em>Hint not unlocked</em></p>

					<?php if ( $hint['Hint']['time_wait'] > 0 && $prevInjectCompleted > 0 && $hint['Hint']['time_wait']+$prevInjectCompleted > time() ): ?>
					<p>Hint will be available after <?php echo $hint['Hint']['time_wait']; ?> seconds</p>
					<?php endif; ?>

					<?php if ( $hint['Hint']['time_available'] > 0 && $hint['Hint']['time_available'] > time() ): ?>
					<p>Hint will be available on <?php echo $hint['Hint']['time_available']; ?></p>
					<?php endif; ?>

					<?php endif; ?>
				</td>

				<?php if ( $hint['Hint']['unlocked'] ): ?>
				
				<td><button class="btn btn-primary" disabled="disabled">Reveal</button></td>

				<?php else: ?>

				<?php if ( !$enabledBtn ): ?>

					<?php if ( $hint['Hint']['time_wait'] > 0 && $prevInjectCompleted > 0 && $hint['Hint']['time_wait']+$prevInjectCompleted > time() ): ?>

					<td>
						<button class="btn btn-primary hint-disabled-countdown" data-until="<?php echo $hint['Hint']['time_wait']+$prevInjectCompleted; ?>" disabled="disabled">
							Please wait <?php echo $hint['Hint']['time_wait']; ?> seconds
						</button>
					</td>

					<?php elseif ( $hint['Hint']['time_available'] > 0 && $hint['Hint']['time_available'] > time() ): ?>

					<td>
						<button class="btn btn-primary hint-disabled-countdown" data-until="<?php echo $hint['Hint']['time_available']; ?>" disabled="disabled">
							Please wait <?php echo $hint['Hint']['time_available']-time(); ?> seconds
						</button>
					</td>

					<?php else: ?>

					<td><button class="btn btn-primary hint-btn">Reveal</button></td>

					<?php endif; ?>

				<?php else: ?>

				<td><button class="btn btn-primary" disabled="disabled">Reveal</button></td>

				<?php endif; ?>

				<?php $enabledBtn = true; endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>