				<div class="clearfix"></div>
			</div>
		</div>

		<footer id="footer">
			<h4>WPF Moderne Webanwendungen</h4>
			<p>Eine Galerie entstanden im Rahmen des Wahlpflichtfaches an der Fachhochschule Köln</p>
		</footer>

	<script src="<?php assets_url( 'js/libs/jquery.min.js' ); ?>"></script>
	<script src="<?php assets_url( 'js/libs/bootstrap.min.js' ); ?>"></script>
	<script src="<?php assets_url( 'js/libs/jquery.mousewheel-3.0.6.pack.js' ); ?>"></script>
	<script src="<?php assets_url( 'js/libs/jquery.fancybox.pack.js'); ?>"></script>
	<script src="<?php assets_url( 'js/libs/jquery.fancybox-thumbs.js'); ?>"></script>
	<?php
	if ( ! empty( $_['extra_footer'] ) )
		echo $_['extra_footer'];
	?>
	</body>
</html>
