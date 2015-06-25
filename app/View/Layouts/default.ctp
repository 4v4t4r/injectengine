<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<title>UBNETDEF: InjectEngine</title>
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
<nav class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $this->Html->url('/'); ?>">Inject Engine</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="<?php echo isset($at_home) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/'); ?>">Home</a></li>
				<li class="<?php echo isset($at_injects) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/injects'); ?>">Injects</a></li>
				<li class="<?php echo isset($at_scoreboard) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/scoreboard'); ?>">Scoreboard</a></li>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<?php if ( !empty($userinfo) ): ?>

				<li class="<?php echo isset($at_teampanel) ? 'active' : ''; ?>"><a href="<?php echo $this->Html->url('/team'); ?>">Team Panel</a></li>

				<?php if ( $backend_access ): ?>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
						Backend Panel <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li class=""><a href="<?php echo $this->Html->url('/backend'); ?>">Dashboard</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/backend'); ?>">User Manager</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/backend'); ?>">Inject Manager</a></li>
						<li class=""><a href="<?php echo $this->Html->url('/backend'); ?>">Service Manager</a></li>
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
			<div class="alert alert-info">
				<strong>Please Note</strong>: The InjectEngine is currently in BETA. There will be bugs.
			</div>

			<?php echo $this->fetch('content'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<?php echo $this->element('sql_dump'); ?>
		</div>
	</div>
</div>

<footer class="footer">
	<div class="container">
		<p class="text-muted pull-right">
			InjectEngine <abbr title="<?php echo $version_long; ?>"><?php echo $version; ?></abbr> // Made with &hearts; by <a href="//james.droste.im">James Droste</a>
		</p>
	</div>
</footer>

</body>
</html>
