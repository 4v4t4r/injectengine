<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<title><?php echo $competition_name; ?>: Inject Engine</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('style');

		echo $this->Html->script('jquery.min');
		echo $this->Html->script('bootstrap.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
<?php if ( $emulating ): ?>
<div class="alert alert-danger" style="margin-bottom: 0px;">
	You are currently emulating a user's account! <?php echo $this->Html->link('EXIT', '/user/emulate_clear', array('class' => 'btn btn-sm btn-info pull-right')); ?>
</div>
<?php endif; ?>

<nav class="navbar navbar-default<?php echo $competition_logo != false ? ' navbar-with-logo' : ''; ?>">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $this->Html->url('/'); ?>">
				<?php if ( $competition_logo != false ): ?>
				
				<img src="<?php echo $this->Html->url($competition_logo); ?>"/>
				
				<?php else: ?>

				<?php echo $competition_name; ?>
				
				<?php endif; ?>
			</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="<?php echo isset($at_home) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/'); ?>">Home</a></li>

				<?php if ( !empty($userinfo) ): ?>
				<li class="<?php echo isset($at_injects) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/injects'); ?>">Injects</a></li>
				<?php endif; ?>

				<li class="<?php echo isset($at_scoreboard) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/scoreboard'); ?>">Scoreboard</a></li>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<?php if ( !empty($userinfo) ): ?>

				<?php if ( $teampanel_access ): ?>
				<li class="<?php echo isset($at_teampanel) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/team'); ?>">Team Panel</a></li>
				<?php endif; ?>

				<?php if ( $dashboard_access ): ?>
				<li class="dropdown<?php echo isset($at_dashboard) ? ' active' : ''; ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
						Dashboards <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li class=""><a href="<?php echo $this->Html->url('/dashboard/overview'); ?>">Overview</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/dashboard/timeline'); ?>">Inject Completion Timeline</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/dashboard/personal'); ?>">Personalized</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if ( $backend_access ): ?>
				<li class="dropdown<?php echo isset($at_backendpanel) ? ' active' : ''; ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
						Backend Panel <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li class=""><a href="<?php echo $this->Html->url('/backend/user'); ?>">User Manager</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/backend/injects'); ?>">Inject Manager</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/backend/service'); ?>">Service Manager</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/backend/logs'); ?>">Log Manager</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
						<?php echo $userinfo['username']; ?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li class=""><a href="<?php echo $this->Html->url('/user/profile'); ?>">My Profile</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/user/logout/'.$userinfo['logout_token']); ?>">Logout</a></li>
					</ul>
				</li>

				<?php else: ?>
				<li class="<?php echo isset($at_login) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/user/login'); ?>">Login</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>

<div class="container">
	<?php echo $this->Session->flash(); ?>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>

	<?php if ( $backend_access ): ?>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<?php echo $this->element('sql_dump'); ?>
		</div>
	</div>
	<?php endif; ?>
</div>

<footer class="footer">
	<div class="container">
		<p class="text-muted pull-right">
			InjectEngine <abbr title="<?php echo $version_long; ?>"><?php echo $version; ?></abbr> // Created by <a href="//james.droste.im">James Droste</a>
		</p>
	</div>
</footer>

</body>
</html>
