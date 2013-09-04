<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<?php
// Check existing POST data and show them
$login = '';
if ( ! empty( $_POST[ 'login' ] ) ) {
	$login = htmlspecialchars( $_POST[ 'login' ], ENT_QUOTES );
}

$password = '';
if ( ! empty( $_POST[ 'password' ] ) ) {
	$password = htmlspecialchars( $_POST[ 'password' ], ENT_QUOTES );
}

if ( isset( $_GET[ 'loggedout' ] ) ) {
	?>
	<div class="alert alert-success">Successfully logged out!</div>
	<?php
}

// Check for errors
$password_extra = $user_extra = '';
if ( ! empty( $_[ 'error' ] ) ) {
	?>
	<div class="alert alert-danger">Sorry, there was an error. Please check the highlighted fields.</div>
	<?php

	if ( in_array( 'emptypassword', $_[ 'error' ] ) || in_array( 'wrongpassword', $_[ 'error' ] ) ) {
		$password_extra = ' has-error';
	}

	if ( in_array( 'emptylogin', $_[ 'error' ] ) || in_array( 'nouser', $_[ 'error' ] ) ) {
		$user_extra = ' has-error';
	}
}
?>

<div class="page-header" style="text-align:center">
  <h2>Login</h2>
</div>

<form class="form-horizontal" action="<?php site_url( '/login/' ); ?>" method="post">
	<div class="form-group<?php echo $user_extra; ?>">
		<label for="login" class="col-lg-2 control-label">Email</label>
		<div class="col-lg-4">
			<input type="email" value="<?php echo $login; ?>" class="form-control" id="login" name="login" autofocus placeholder="Enter Email">
		</div>
	</div>

	<div class="form-group<?php echo $password_extra; ?>">
		<label for="password" class="col-lg-2 control-label">Password</label>
		<div class="col-lg-4">
			<input type="password" value="<?php echo $password; ?>" class="form-control" id="password" name="password" placeholder="Enter Password">
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="remember" value="1"> Remember me
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-primary">Sign in</button>
		</div>
	</div>
</form>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
