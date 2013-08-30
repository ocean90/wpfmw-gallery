<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>
Home Screen

<script type="text/javascript">
	$(document).ready(function () {
		$("#makeMeScrollable").smoothDivScroll({
			mousewheelScrolling: "allDirections",
			manualContinuousScrolling: true,
			autoScrollingMode: "onStart"
		});
	});
</script>

<div id="makeMeScrollable">
		<img src="bg.jpg" alt="Field" id="field" />
		<img src="bg.jpg" alt="Gnome" id="gnome" />
		<img src="bg.jpg" alt="Pencils" id="pencils" />
		<img src="bg.jpg" alt="Golf" id="golf" />
		<img src="bg.jpg" alt="River" id="river" />
		<img src="bg.jpg" alt="Train" id="train" />
		<img src="bg.jpg" alt="Leaf" id="leaf" />
		<img src="bg.jpg" alt="Dog" id="dog" />
	</div>

<?php include APP_VIEWS_PATH . 'footer.php'; ?>



