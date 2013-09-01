<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<?php
// Check for errors
if ( ! empty( $_[ 'error' ] ) ) {
	?>
	<div class="alert alert-danger">Sorry, there was an error. Please check the highlighted fields.</div>
	<?php
	if ( in_array( 'emptyemail', $_[ 'error' ] ) || in_array( 'invalidemail', $_[ 'error' ] ) || in_array( 'mailexists', $_[ 'error' ] ) ) {
		$email_extra = ' has-error';
	}

	if ( in_array( 'passwordmismatch', $_[ 'error' ] ) ) {
		$password_extra = ' has-error';
	}
}

if ( isset( $_GET[ 'success' ] ) ) {
	?>
	<div class="alert alert-success">Settings updated!</div>
	<?php
}
?>

<div class="page-header">
	<h2>Settings <small>Update your profile</small></h2>
</div>

<form class="form-horizontal" method="post" action="">

	<div class="form-group">
		<label for="username" class="col-lg-2 control-label">Username</label>
		<div class="col-lg-4">
			<input type="text" class="form-control" id="username" name="username" value="<?php echo escape_attribute( $_[ 'user' ]->user_login ); ?>" placeholder="Enter Username" disabled>
		</div>
	</div>

	<div class="form-group">
		<label for="meta-firstname" class="col-lg-2 control-label">Firstname</label>
		<div class="col-lg-4">
			<?php
			if ( isset( $_POST[ 'meta[firstname]' ] ) ) {
				$firstname = $_POST[ 'meta[firstname]' ];
			} else {
				$firstname = $_[ 'user' ]->firstname;
			}
			$firstname = escape_attribute( $firstname );
			?>
			<input type="text" class="form-control" id="meta-firstname" name="meta[firstname]" value="<?php echo $firstname; ?>" placeholder="Enter Firstname">
		</div>
	</div>

	 <div class="form-group">
		<label for="meta-lastname" class="col-lg-2 control-label">Lastname</label>
		<div class="col-lg-4">
			<?php
			if ( isset( $_POST[ 'meta[lastname]' ] ) ) {
				$lastname = $_POST[ 'meta[lastname]' ];
			} else {
				$lastname = $_[ 'user' ]->lastname;
			}
			$lastname = escape_attribute( $lastname );
			?>
			<input type="text" class="form-control" id="meta-lastname" name="meta[lastname]" value="<?php echo $lastname; ?>" placeholder="Enter Lastname">
		</div>
	</div>

	<div class="form-group">
		<label for="email" class="col-lg-2 control-label">Email</label>
		<div class="col-lg-4">
			<?php
			if ( isset( $_POST[ 'email' ] ) ) {
				$email = $_POST[ 'email' ];
			} else {
				$email = $_[ 'user' ]->user_email;
			}
			$email = escape_attribute( $email );
			?>
			<input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>"  placeholder="Enter Email">
		</div>
	</div>

	<div class="form-group">
		<label for="password1" class="col-lg-2 control-label">Password</label>
		<div class="col-lg-4">
			<input type="password" class="form-control" id="password1" name="password1" placeholder="Enter Password">
			<small class="help-block">Only if you want to change it.</small>
		</div>
	</div>

	<div class="form-group">
		<label for="password2" class="col-lg-2 control-label">Repeat Password</label>
		<div class="col-lg-4">
			<input type="password" class="form-control" id="password2" name="password2" placeholder="Enter Password Again">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Avatar</label>

		<div class="col-lg-4">
			<?php
			echo get_gravatar( $_[ 'user' ]->user_email, 200, 'mm', 'g', true );
			?>
			<small class="help-block"><a href="https://gravatar.com/">Change your avatar at Gravatar.com.</a></small>
		</div>
	</div>

	<div class="col-lg-offset-2 col-lg-4">
		<button type="submit" class="btn btn-default pull-right">Update</button>
	</div>

</form>


<?php include APP_VIEWS_PATH . 'footer.php'; ?>
