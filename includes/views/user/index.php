<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<legend>Your Profile</legend>

<p>Username: <?php echo $_[ 'user' ]->user_login; ?></p><br>
<p>Email-Adresse: <?php echo $_[ 'user' ]->user_email; ?></p><br>
<p>Gravatar:<p>

<?php
echo get_gravatar(   $_[ 'user' ]->user_email, 200, 'mm', 'g', true );


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

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



