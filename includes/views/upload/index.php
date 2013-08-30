<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div class="page-header">
	<h2>Upload <small>Create a new gallery</small></h2>
</div>

<blockquote class="pull-right">
	<p>Today everything exists to end in a photograph.</p>
	<small><cite>Susan Sontag</cite></small>
</blockquote>

<div id="image-uploader-wrapper clearfix">
	 <div id="upload-container">
	 	<form id="image-uploader" action="" method="post" enctype="multipart/form-data">
			<input type="file" id="images" name="images[]" multiple accept="image/*">

			<button id="image-upload-button" type="button" class="btn btn-primary btn-lg btn-block">Select Images To Upload</button>

			<div id="image-container"></div>

	 	</form>
	 </div>
</div>
<?php include APP_VIEWS_PATH . 'footer.php'; ?>
