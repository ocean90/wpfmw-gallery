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
		<link rel="stylesheet" href="<?php assets_url( 'css/style.css' ); ?>" type="text/css" media="screen">
		<?php
		if ( ! empty( $_['extra_header'] ) )
			echo $_['extra_header'];
		?>
	</head>
	<body>
		<div class="container">
			<div id="content">
