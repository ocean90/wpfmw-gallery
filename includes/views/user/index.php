<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div id="profile-page" class="row">
	<div class="col-lg-2">
		<img src="<?php echo get_gravatar( $_[ 'user' ]->user_email, 200 ); ?>" class="img-circle avatar">
	</div>
	<div class="col-lg-10 page-header">
		<?php
		$name = '';
		if ( ! empty( $_[ 'user' ]->firstname ) ) {
			$name = $_[ 'user' ]->firstname;
		}

		if ( ! empty( $_[ 'user' ]->lastname ) ) {
			if ( empty( $name ) ) {
				$name = $_[ 'user' ]->lastname;
			} else {
				$name .= ' ' . $_[ 'user' ]->lastname;
			}
		}

		if ( ! empty( $name ) ) {
			$name = ' <small>' . $name . '</small>';
		}
		?>
		<h1><?php echo $_[ 'user' ]->user_login; echo $name; ?></h1>

		<?php if ( ! empty( $_[ 'user' ]->user_email ) ) { ?>
		<p>Contact: <a href="mailto:<?php echo $_[ 'user' ]->user_email; ?>"><?php echo $_[ 'user' ]->user_email; ?></a></p>
		<?php } ?>
	</div>
</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
