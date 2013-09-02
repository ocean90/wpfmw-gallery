<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div id="welcome-page">
	<div class="row">
		<div class="col-md-6 home-welcome">
			<h1>Welcome to the online gallery.</h1>
			<p>
			This gallery offers the opportunity to create your own galleries to manage images or see other galleries.</p>
			<p><a href="<?php site_url( '/login/')?>" class="btn btn-block btn-primary btn-lg">Login</a></p>
			<p>New? Create an <a href="<?php site_url( '/register/')?>">account</a> now!</p>
		</div>
		<div class="col-md-6">
			<div id="carousel-home-welcome" class="carousel slide">
				<ol class="carousel-indicators">
					<li data-target="#carousel-home-welcome" data-slide-to="0" class="active"></li>
					<li data-target="#carousel-home-welcome" data-slide-to="1"></li>
					<li data-target="#carousel-home-welcome" data-slide-to="2"></li>
					<li data-target="#carousel-home-welcome" data-slide-to="3"></li>
				</ol>

				<div class="carousel-inner">
					<div class="item active">
						<img src="<?php assets_url( 'img/home-1.jpg' ); ?>" alt="Four-mast bark Passat">
						<div class="carousel-caption">
							<h4>Four-mast bark Passat</h4>
						</div>
					</div>
					<div class="item">
						<img src="<?php assets_url( 'img/home-2.jpg' ); ?>" alt="Wood trunks">
						<div class="carousel-caption">
							<h4>Wood trunks</h4>
						</div>
					</div>
					<div class="item">
						<img src="<?php assets_url( 'img/home-3.jpg' ); ?>" alt="Flower bouqet">
						<div class="carousel-caption">
							<h4>Flower bouqet</h4>
						</div>
					</div>
					<div class="item">
						<img src="<?php assets_url( 'img/home-4.jpg' ); ?>" alt="Tower Bridge, London">
						<div class="carousel-caption">
							<h4>Tower Bridge, London</h4>
						</div>
					</div>
				</div>

				<a class="left carousel-control" href="#carousel-home-welcome" data-slide="prev">
					<span class="icon-prev"></span>
				</a>
				<a class="right carousel-control" href="#carousel-home-welcome" data-slide="next">
					<span class="icon-next"></span>
				</a>
			</div>
		</div>
	</div>
</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



