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
			<?php
			if ( false === strpos( $_SERVER[ 'REQUEST_URI' ], '/upload' ) ) {
				?>
				<li><a href="<?php site_url( '/upload/' ); ?>">Upload</a></li>
				<?php
			} else {
				?>
				<li class="active"><a href="<?php site_url( '/upload/' ); ?>" >Upload</a></li>
				<?php
			}
			?>
		</ul>

		<?php if ( User_Manager::is_user_logged_in() ) { ?>
		<form class="navbar-form navbar-left" method="get" action="<?php site_url( 'search/' ); ?>">
			<div class="form-group">
				<?php
				$search_value = ! empty( $_GET[ 'q' ] ) ? escape_attribute( $_GET[ 'q' ] ) : '';
				?>
				<input type="text" class="form-control" name="q" value="<?php echo $search_value; ?>" placeholder="Enter Search Term">
			</div>
			<button type="submit" class="btn btn-default">Search</button>
		</form>
		<?php } ?>

		<ul class="nav navbar-nav navbar-right">
			<?php if ( ! User_Manager::is_user_logged_in() ) { ?>
				<li><a href="<?php site_url( '/register/'); ?>">Sign up</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <b class="caret"></b></a>
					<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
						<form method="post" action="<?php site_url( '/login/' ); ?>">
							<input style="margin-bottom: 15px;" type="text" placeholder="Enter Email" class="form-control" id="login" name="login">
							<input style="margin-bottom: 15px;" type="password" placeholder="Enter Password" class="form-control" id="password" name="password">
							<input style="float: left; margin-right: 10px;" type="checkbox" name="remember" id="remember" value="1">
							<label class="string optional" for="remember"> Remember me</label>
							<input class="btn btn-primary btn-block" type="submit" id="sign-in" value="Sign In">
							<label style="text-align:center;margin-top:5px"></label>
						</form>
					</div>
				</li>
			<?php } else { ?>
				<li class="dropdown">
					<?php $current_user = User_Manager::get_current_user(); ?>
					<a class="dropdown-toggle" data-toggle="dropdown" href="<?php site_url( '/user/' . $current_user->user_login ); ?>">
						Signed in as <?php echo $current_user->user_login; ?>
						<?php echo get_gravatar( $current_user->user_email, 18, 'mm', 'g', true ); ?>
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php site_url( '/user/' . $current_user->user_login ); ?>">Profile</a></li>
						<li><a href="<?php site_url( '/settings/' ); ?>">Settings</a></li>
						<li class="divider"></li>
						<li><a href="<?php site_url( '/logout/' ); ?>">Log out</a></li>
					</ul>
				</li>
			<?php } ?>
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>
