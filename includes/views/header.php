<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<?php
		$title = ! empty( $_['page_title'] ) ? $_['page_title'] : ''; ?>
		<title><?php echo $title; ?></title>
		<link rel="stylesheet" href="<?php assets_url( 'css/libs/bootstrap.min.css' ); ?>" type="text/css" media="screen">
		<?php
		if ( ! empty( $_['extra_header'] ) )
			echo $_['extra_header'];
		?>
		<link rel="stylesheet" href="<?php assets_url( 'css/style.css' ); ?>" type="text/css" media="screen">
		<link rel="shortcut icon" href="<?php assets_url( 'img/favicon.ico' ); ?>" type="image/x-icon">
	</head>
	<body>
		<div id="fb-root"></div>
                <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
  		if (d.getElementById(id)) return;
  		js = d.createElement(s); js.id = id;
 	        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
			<div class="container">
				<div id="content">
