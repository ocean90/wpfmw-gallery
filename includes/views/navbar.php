<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php site_url( '/' ); ?>">Home</a>
	</div>

	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<li><a href="<?php site_url( '/settings/' ); ?>">Settings</a></li>
			<li><a href="<?php site_url( '/upload/' ); ?>">Upload</a></li>
		</ul>

		<form class="navbar-form navbar-left" role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>

		<ul class="nav navbar-nav navbar-right">
			<?php if ( ! is_user_logged_in() ) { ?>
			<li><a href="/register">Sign up</a></li>
			<li class="dropdown">
				<a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
				<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
					<form method="post" action="/login">
						<input style="margin-bottom: 15px;" type="text" placeholder="Enter Email" id="login" name="login">
						<input style="margin-bottom: 15px;" type="password" placeholder="Enter Password" id="password" name="password">
						<input style="float: left; margin-right: 10px;" type="checkbox" name="remember" id="remember" value="1">
						<label class="string optional" for="remember"> Remember me</label>
						<input class="btn btn-primary btn-block" type="submit" id="sign-in" value="Sign In">
						<label style="text-align:center;margin-top:5px"></label>
					</form>
				</div>
			</li>
			<?php } else { ?>
				<li>
					<p class="navbar-text">Signed in as <a class="navbar-link" href="<?php site_url( '/user/' . $GLOBALS['app']->current_user->user_login ); ?>"><?php echo $GLOBALS['app']->current_user->user_login; ?></a></p>
				</li>
				<li>
					<a href="<?php site_url( '/user/' . $GLOBALS['app']->current_user->user_login ); ?>" class="brand"><img alt="" src="<?php get_gravatar( $GLOBALS['app']->current_user->user_email, 40, 'mm', 'g', true)?>" /></a>
				</li>
				<li>
					<a href="<?php site_url( '/logout/' ); ?>">Log out</a>
				</li>
			<?php } ?>
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>
