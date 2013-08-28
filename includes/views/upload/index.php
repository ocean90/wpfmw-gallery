<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<h2>File Upload</h2>

<blockquote class="pull-right">
	<p>Today everything exists to end in a photograph.</p>
  <small><cite title="Source Title">Susan Sontag</cite></small>
</blockquote>

<form action="" method="post" enctype="multipart/form-data">

	<div class="form-group">
		<label for="upload">File input</label>
		<input type="file" id="upload" name="upload[]" multiple accept="image/*">
		<p class="help-block">Choose one ore more pictures to upload</p>
	</div>

 </form>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>
