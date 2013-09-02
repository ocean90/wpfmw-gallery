<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>
<div id="profile-page">

	<div class="row">
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

	<div class="user-galleries">
	<?php
	if ( empty( $_[ 'galleries' ] ) ) {
		?>
		<div class="alert alert-warning">No galleries available, yet.</div>
		<?php
	} else {
		$i = 0;
		echo '<div class="row">';
		foreach ( $_[ 'galleries' ] as $gallery ) {
			if ( $i != 0 && $i % 3 == 0 ) {
				echo '</div><div class="row">';
			}
			?>
			<div class="col-md-4 col-sm-6 user-gallery">
				<?php
				if ( ! empty( $gallery->images[0] ) ) {
					$image = $gallery->images[0];
					?>
					<div class="thumbnail">
				 		<?php
				 		$image_src = Image_Manager::get_url_of_image( $image );

						printf(
							'<img src="%s" class="image">',
							$image_src
						);
						?>
			 		</div>
					<?php
				}
				?>
			 	<div class="caption">
					<h3><?php echo $gallery->gallery_title; ?></h3>
					<p><?php echo $gallery->gallery_description; ?></p>
					<p><a href="<?php echo $gallery->gallery_url; ?>" class="btn btn-primary btn-lg btn-block">View Gallery</a></p>
				</div>
			</div>
			<?php
			$i = $i + 1;
		}
		echo '</div>';
	}
	?>
	</div>

</div>
<?php include APP_VIEWS_PATH . 'footer.php'; ?>
