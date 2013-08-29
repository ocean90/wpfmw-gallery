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

<form method="post" class="form-horizontal install-register-form clearfix">
		<div class="form-group<?php echo $username_extra; ?>">
			<label for="username" class="col-lg-3 control-label">Username</label>
			<div class="col-lg-5">
				<input type="text" value="<?php echo $username; ?>" class="form-control input-lg" id="username" name="username" autofocus placeholder="Enter username" maxlength="60">
			</div>
		</div>

		<div class="form-group<?php echo $email_extra; ?>">
			<label for="email" class="col-lg-3 control-label">Email</label>
			<div class="col-lg-5">
				<input type="email" value="<?php echo $email; ?>" class="form-control input-lg" id="email" name="email" placeholder="Enter email" maxlength="100">
			</div>
		</div>

		<div class="form-group<?php echo $password_extra; ?>">
			<label for="password1" class="col-lg-3 control-label">Password</label>
			<div class="col-lg-5">
				<input type="password" value="<?php echo $password1; ?>" class="form-control input-lg" id="password1" name="password1" placeholder="Enter password" maxlength="100">
			</div>
		</div>

		<div class="form-group<?php echo $password_extra; ?>">
			<label for="password2" class="col-lg-3 control-label">Repeat Password</label>
			<div class="col-lg-5">
				<input type="password" value="<?php echo $password2; ?>" class="form-control input-lg" id="password2" name="password2" placeholder="Enter password again" maxlength="100">
			</div>
		</div>

		<div class="col-lg-8">
			<button type="submit" class="btn btn-primary btn-lg pull-right">Register</button>
		</div>
	</form>


<?php include APP_VIEWS_PATH . 'footer.php'; ?>



