<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>

<div class="page-header">
	<h2>Upload <small>Create a new gallery</small></h2>
</div>

<blockquote class="pull-right">
	<p>Today everything exists to end in a photograph.</p>
	<small><cite>Susan Sontag</cite></small>
</blockquote>
<div class="clearfix"></div>
<div id="image-uploader-wrapper">
	<div id="upload-container">
		<div id="image-uploader">
			<form>
				<input type="file" id="images" name="images[]" multiple accept="image/*">
			</form>

			<button id="image-upload-button" type="button" class="btn btn-success btn-lg btn-block">Select Images To Upload</button>

			<form method="post" action="">
				<div id="image-container" class="clearfix">
				</div>
				<div id="gallery-container" class="hidden clearfix">
					<div class="form-group">
						<label for="gallery-title" class="control-label">Gallery Title</label>
						<input type="text" class="form-control" id="gallery-title" name="gallery-title" placeholder="Enter Gallery Title">
					</div>

					<div class="form-group">
						<label for="gallery-description" class="control-label">Gallery Description</label>
						<textarea class="form-control" id="gallery-description" rows="10" name="gallery-description" placeholder="Enter Gallery Description"></textarea>
					</div>

					<div class="form-group">
						<button id="create-gallery-button" type="submit" class="btn btn-primary btn-lg btn-block pull-right">Create New Gallery</button>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>
<?php include APP_VIEWS_PATH . 'footer.php'; ?>
