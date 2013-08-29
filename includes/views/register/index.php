<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>


<?php
// Check existing POST data and show them
$username = '';
if ( ! empty( $_POST[ 'username' ] ) ) {
	$username = htmlspecialchars( $_POST[ 'username' ], ENT_QUOTES );
}

$email = '';
if ( ! empty( $_POST[ 'email' ] ) ) {
	$email = htmlspecialchars( $_POST[ 'email' ], ENT_QUOTES );
}

$password1 = '';
if ( ! empty( $_POST[ 'password1' ] ) ) {
	$password1 = htmlspecialchars( $_POST[ 'password1' ], ENT_QUOTES );
}

$password2 = '';
if ( ! empty( $_POST[ 'password2' ] ) ) {
	$password2 = htmlspecialchars( $_POST[ 'password2' ], ENT_QUOTES );
}

// Check for errors
$username_extra = $email_extra = $password_extra = '';

// Check for errors
if ( ! empty( $_[ 'error' ] ) ) {
	?>
	<div class="alert alert-danger">Sorry, there was an error. Please check the highlighted fields.</div>
	<?php

	if ( in_array( 'emptyusername', $_[ 'error' ] ) || in_array( 'invalidusername', $_[ 'error' ] ) || in_array( 'usernameexists', $_[ 'error' ] ) ) {
		$username_extra = ' has-error';
	}

	if ( in_array( 'emptyemail', $_[ 'error' ] ) || in_array( 'invalidemail', $_[ 'error' ] ) || in_array( 'mailexists', $_[ 'error' ] ) ) {
		$email_extra = ' has-error';
	}

	if ( in_array( 'emptypassword', $_[ 'error' ] ) || in_array( 'passwordmismatch', $_[ 'error' ] ) ) {
		$password_extra = ' has-error';
	}
}
?>

<form class="form-horizontal" method="post" action="">

	<legend>Register</legend>

	<div class="form-group">
		<label for="username" class="col-lg-2 control-label">Username</label>
		<div class="col-lg-4">
			<input type="text" class="form-control" id="username" name="username" autofocus placeholder="Enter Username">
			<small class="help-block">Allowed characters: <code>a-z0-9_-</code></small>
		</div>
	</div>

	<div class="form-group">
		<label for="email" class="col-lg-2 control-label">Email</label>
		<div class="col-lg-4">
			<input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">
		</div>
	</div>

	<div class="form-group">
		<label for="password1" class="col-lg-2 control-label">Password</label>
		<div class="col-lg-4">
			<input type="password" class="form-control" id="password1" name="password1" placeholder="Enter Password">
		</div>
	</div>

	<div class="form-group">
		<label for="password2" class="col-lg-2 control-label">Password again <span id="password-mismatch" class="label label-danger">Mismatch!</span> </label>
		<div class="col-lg-4">
			<input type="password" class="form-control" id="password2" name="password2" placeholder="Enter Password again">
		</div>
	</div>

	<div id="password-strength-result" class="form-group">
		<div class="col-lg-offset-2 col-lg-4">
			<small class="help-block">Password Strength</small>
			<div class="progress">
				<div class="progress-bar"></div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-4">
			<button type="submit" class="btn btn-primary pull-right">Register</button>
		</div>
	</div>

</form>


<?php include APP_VIEWS_PATH . 'footer.php'; ?>



