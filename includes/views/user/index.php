<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div id="profile-page" class="row">
	<div class="col-sm-2">
		<img src="<?php echo get_gravatar( $_[ 'user' ]->user_email, 200 ); ?>" class="img-circle avatar">
	</div>
	<div class="col-sm-10 page-header">
		<?php
		$nicename = '';
		if ( ! empty( $_[ 'user' ]->user_nicename ) ) {
			$nicename = ' <small>' . $_[ 'user' ]->user_nicename . '</small>';
		}
		?>
		<h2><?php echo $_[ 'user' ]->user_login; echo $nicename; ?></h2>

		<?php if ( ! empty( $_[ 'user' ]->user_email ) ) { ?>
		<p>Contact: <a href="mailto:<?php echo $_[ 'user' ]->user_email; ?>"><?php echo $_[ 'user' ]->user_email; ?></a></p>
		<?php } ?>
	</div>
</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
