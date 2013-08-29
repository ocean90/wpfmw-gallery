<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<h2>File Upload</h2>

<blockquote class="pull-right">
	<p>Today everything exists to end in a photograph.</p>
  <small><cite title="Source Title">Susan Sontag</cite></small>
</blockquote>

<form id="image-uploader" action="" method="post" enctype="multipart/form-data">

	<div class="form-group">
		<label for="images">File input</label>
		<input type="file" id="images" name="images[]" multiple accept="image/*">
		<p class="help-block">Choose one ore more pictures to upload</p>
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-primary">Upload</button>
	</div>
 </form>

 <div id="image-container"></div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
