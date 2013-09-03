<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div id="timeline-page">

	<div class="page-header">
		<h2>Timeline <small>Checkout newest galleries from your friends</small></h2>
	</div>

	<div class="row">
		<div class="col-md-8">
			<?php
			if ( empty ( $_[ 'galleries' ]  ) ) {
				echo '<div class="alert alert-warning">No galleries available, yet.</div>';
			} else {
				foreach ( $_[ 'galleries' ] as $gallery ) {
					$id = $gallery->ID;
					?>
					<article class="clearfix gallery">
						<div id="carousel-gallery-<?php echo $id; ?>" class="carousel slide">
							<ol class="carousel-indicators">
								<?php
								$count = count( $gallery->images );
								for ( $i = 0; $i < $count; $i++ ) {
									printf(
										'<li data-target="#carousel-gallery-%d" data-slide-to="%d"%s></li>',
										$id,
										$i,
										$i == 0 ? ' class="active"' : ''
									);
								}
								?>
							</ol>

							<div class="carousel-inner">
								<?php
								$i = 0;
								foreach ( $gallery->images as $image ) {
									$image_thumb_src = Image_Manager::get_url_of_image( $image, 'thumb-750-350' );
									?>
									<div class="item<?php echo $i == 0 ? ' active' : ''; ?> ">
										<img src="<?php echo $image_thumb_src; ?>" alt="<?php echo $image->image_title; ?>">
										<div class="carousel-caption">
											<h4><?php echo $image->image_title; ?></h4>
										</div>
									</div>
									<?php
									$i++;
								}
								?>
							</div>

							<a class="left carousel-control" href="#carousel-gallery-<?php echo $id; ?>" data-slide="prev">
								<span class="icon-prev"></span>
							</a>
							<a class="right carousel-control" href="#carousel-gallery-<?php echo $id; ?>" data-slide="next">
								<span class="icon-next"></span>
							</a>
						</div>

						<header>
							<h2><a href="<?php echo $gallery->gallery_url; ?>"><?php echo $gallery->gallery_title; ?></a></h2>
							<?php
							$creator = new User_Model( $gallery->user_id );
							if ( ! empty ( $creator->ID ) ) {
								$image = '<img class="img-circle avatar" src="' . get_gravatar( $creator->user_email, 20 ) . '">';
								?>
								<p><?php echo mysql2date( 'Y/m/d', $gallery->gallery_created ); ?> | <a href="<?php site_url( 'user/' . $creator->user_login . '/' ); ?>"><?php echo $image . ' ' . $creator->user_login; ?></a></p>
								<?php
							}
							?>
						</header>
						<div clas="gallery-description">
							<?php echo utf8_truncate( $gallery->gallery_description, 300 ); ?>
						</div>
						<footer>
							<p><a href="<?php echo $gallery->gallery_url; ?>" class="btn btn-default pull-right">View Gallery</a></p>
						</footer>
					</article>
					<?php
				}
			}

			?>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Create Your Own Gallery</h3>
				</div>
				<div class="panel-body">
					<p>You can create your own free gallery!</p>
					<p>Share your great photo work with your family and friends. <em>It's just some clicks away!</em></p>
					<a class="btn btn-primary btn-block" href="<?php site_url( 'upload/' ); ?>">Upload Photos Now</a>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Search</h3>
				</div>
				<div class="panel-body">
					<form class="form-inline" method="get" action="<?php site_url( 'search/' ); ?>">
						<div class="form-group">
							<?php
							$search_value = ! empty( $_GET[ 'q' ] ) ? escape_attribute( $_GET[ 'q' ] ) : '';
							?>
							<input type="text" class="form-control" name="q" value="<?php echo $search_value; ?>" placeholder="Enter Search Term">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Search</button>
						</div>
					</form>
				</div>
			</div>


		</div>
	</div>
</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



