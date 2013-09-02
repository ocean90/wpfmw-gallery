<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div id="gallery-page">
	<div class="page-header">
		<h2>Gallery: <?php echo $_['gallery']->gallery_title; ?> <small> <?php echo $_['gallery']->gallery_description; ?></small></h2>
		<?php
		$creator = new User_Model( $_['gallery']->user_id );
		if ( ! empty ( $creator->ID ) ) {
			$image = '<img class="img-circle avatar" src="' . get_gravatar( $creator->user_email, 20 ) . '">';
			?>
			<p>Creator: <a href="<?php site_url( 'user/' . $creator->user_login . '/' ); ?>"><?php echo $image . ' ' . $creator->user_login; ?></a></p>
			<?php
		}
		?>
	</div>

	<?php foreach ( $_['gallery']->images as $image ) {
		$image_src = Image_Manager::get_url_of_image( $image );

		printf(
			'<img src="%s" class="image">',
			$image_src
		);
	}
	?>
</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
