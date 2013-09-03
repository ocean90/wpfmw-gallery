<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div id="gallery-page">
	<div class="row">
		<div class="col-md-12">
			<div id="carousel-gallery-images" class="carousel slide">
					<ol class="carousel-indicators">
						<?php
						$count = count( $_['gallery']->images );
						$min = min( $count, 5 );
						for ( $i = 0; $i < $min; $i++ ) {
							printf(
								'<li data-target="#carousel-gallery-images" data-slide-to="%d"%s></li>',
								$i,
								$i == 0 ? ' class="active"' : ''
							);
						}
						?>
					</ol>

					<div class="carousel-inner">
						<?php
						$i = 0;
						foreach ( $_['gallery']->images as $image ) {
							// Only show 5 images big
							if ( $i == 5 )
								break;
							$image_thumb_src = Image_Manager::get_url_of_image( $image, 'thumb-1200-300' );
							?>
							<div class="item<?php echo $i == 0 ? ' active' : ''; ?> ">
								<img src="<?php echo $image_thumb_src; ?>" alt="<?php echo $image->image_title; ?>">
								<div class="carousel-caption">
									<h4><?php echo $image->image_title; ?></h4>
									<p><?php echo $image->image_description; ?></p>
								</div>
							</div>
							<?php
							$i++;
						}
						?>
					</div>

					<a class="left carousel-control" href="#carousel-gallery-images" data-slide="prev">
						<span class="icon-prev"></span>
					</a>
					<a class="right carousel-control" href="#carousel-gallery-images" data-slide="next">
						<span class="icon-next"></span>
					</a>
				</div>
			</div>
		</div>
	</div>

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

	<div class="gallery-images-thumbs">
		<?php
		$i = 0;
		echo '<div class="row">';
		foreach ( $_['gallery']->images as $image ) {
			if ( $i != 0 && $i % 3 == 0 ) {
				echo '</div><div class="row">';
			}

			$image_thumb_src = Image_Manager::get_url_of_image( $image, 'thumb-200-200' );
			$image_full_src = Image_Manager::get_url_of_image( $image );

			?>
			<div class="col-sm-2 gallery-images-thumb">
				<a class="fancybox" rel="gallery" href="<?php echo $image_full_src; ?>" title="<?php echo $image->image_title; ?>">
					<div class="thumbnail">
						<img src="<?php echo $image_thumb_src; ?>" alt="<?php echo $image->image_title; ?>">
					</div>
				</a>
			</div>
			<?php
		}
		echo '</div>';
		?>
	</div>

	<div class="fb-like" data-href="<?php echo $_['gallery']->gallery_url ?>" data-show-faces="true" data-send="true"></div>
	<div class="fb-comments" data-href="<?php echo $_['gallery']->gallery_url ?>"></div>
</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
