<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro <dario.vizzaccaro@smail.fh-koeln.de>
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */
?>

<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div id="gallery-page">
	<div class="row">
		<div class="col-md-12">
			<?php
			$teaser_image = $_['gallery']->images[0];
			$image_thumb_src = Image_Manager::get_url_of_image( $teaser_image, 'thumb-1200-300' );
			?>
			<div class="thumbnail">
				<img src="<?php echo $image_thumb_src; ?>" alt="<?php echo $teaser_image->image_title; ?>">
			</div>
		</div>
	</div>

	<div class="page-header">
	<?php
		$private_label = '';
		if ( ! $_['gallery']->is_public ) {
			$private_label = '<span class="label label-info pull-right">Private</span>';
		}

		printf(
			'<h2>%sGallery: %s <small>%s</small></h2>',
			$private_label,
			$_['gallery']->gallery_title,
			nl2br( $_['gallery']->gallery_description )
		);

		$creator = new User_Model( $_['gallery']->user_id );
		if ( ! empty ( $creator->ID ) ) {
			$image = '<img class="img-circle avatar" src="' . get_gravatar( $creator->user_email, 20 ) . '">';
			?>
			<p>Creator: <a href="<?php site_url( 'user/' . $creator->user_login . '/' ); ?>"><?php echo $image . ' ' . $creator->user_login; ?></a></p>
			<?php
		}
		?>
		<p>Created: <?php echo mysql2date( 'Y/m/d', $_['gallery']->gallery_created ); ?></p>
	</div>

	<div class="gallery-images-thumbs">
		<?php
		// Store locations for a map
		$locations = array();

		$i = 0;
		echo '<div class="row">';
		foreach ( $_['gallery']->images as $image ) {

			// Get the location meta and store it, if exists
			if ( ! empty( $image->geolocation ) ) {
				$locations[] = $image->geolocation;
			}

			if ( $i != 0 && $i % 3 == 0 ) {
				echo '</div><div class="row">';
			}

			$image_thumb_src = Image_Manager::get_url_of_image( $image, 'thumb-200-200' );
			$image_full_src = Image_Manager::get_url_of_image( $image );

			?>
			<div class="col-sm-2 gallery-images-thumb">
				<a class="fancybox" data-full-image="<?php echo $image_full_src; ?>"rel="gallery" href="#image-box-<?php echo $image->ID; ?>" title="<?php echo $image->image_title; ?>">
					<div class="thumbnail">
						<img src="<?php echo $image_thumb_src; ?>" alt="<?php echo $image->image_title; ?>">
					</div>
				</a>
			</div>
			<div id="image-box-<?php echo $image->ID; ?>" class="image-box">
				<p class="loading">Loading...</p>
				<div class="row">
					<div class="col-sm-9 image-box-left">
						<img class="image">
					</div>
					<div class="col-sm-3 image-box-right">
						<div class="image-box-scroll">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $image->image_title; ?></div>
								<div class="panel-body">
									<p><?php echo nl2br( $image->image_description ); ?></p>
								</div>
							</div>
							<?php if ( ! empty( $image->geolocation ) ) { ?>
							<div class="panel panel-default">
								<div class="panel-heading">Location</div>
								<div class="panel-body">
									<p><strong>Coordinates:</strong> <?php echo $image->geolocation['lat'] . ', ' . $image->geolocation['lng']; ?></p>
									<p><button class="btn btn-sm btn-info pull-right fetch-loaction-infos" data-lat="<?php echo $image->geolocation['lat']; ?>" data-lng="<?php echo $image->geolocation['lng']; ?>">Load Location Infos</button></p>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			$i++;
		}
		echo '</div>';
		?>
	</div>

	<?php if ( ! empty( $locations ) ) { ?>
		<div class="row maps-container">
			<h3>Location Map</h3>
			<p>The markers represent the locations where the photos are taken.</p>
			<?php
			$markers = array();
			foreach ( $locations as $location ) {
				$markers[] = sprintf(
					'markers=color:blue%%7C%s,%s',
					$location[ 'lat' ],
					$location[ 'lng' ]
				);
			}

			$url = 'https://maps.googleapis.com/maps/api/staticmap?zoom=3&amp;scale=2&amp;size=640x300&amp;sensor=true&amp;maptype=terrain&amp;';
			$url .= implode( '&amp;', $markers );

			echo '<div class="thumbnail"><img width="640" height="300" src="' . $url . '" alt="Location Map"></div>';
			?>
		</div>
	<?php } ?>

	<div class="row facebook-container">
		<h3>Feedback</h3>
		<p>Like or comment this gallery.</p>
		<div class="fb-like" data-href="<?php echo $_['gallery']->gallery_url ?>" data-show-faces="true" data-send="true"></div>
		<div class="fb-comments" data-href="<?php echo $_['gallery']->gallery_url ?>"></div>
	</div>
</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
