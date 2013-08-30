<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<legend>File Upload</legend>

<blockquote class="pull-right">
	<p>Today everything exists to end in a photograph.</p>
  <small><cite title="Source Title">Susan Sontag</cite></small>
</blockquote>

<div id="image-uploader-wrapper">
	 <div id="upload-container">
	 	<form id="image-uploader" action="" method="post" enctype="multipart/form-data">
			<input type="file" id="images" name="images[]" multiple accept="image/*">

			<div id="image-container"></div>

	 	</form>
	 </div>
</div>
<?php include APP_VIEWS_PATH . 'footer.php'; ?>
