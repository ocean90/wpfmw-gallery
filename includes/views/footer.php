<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro <dario.vizzaccaro@smail.fh-koeln.de>
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */
?>

				<div class="clearfix"></div>
			</div>
		</div>

		<footer id="footer">
			<h4>WPF Moderne Webanwendungen</h4>
			<p>Eine Galerie entstanden im Rahmen des Wahlpflichtfaches an der Fachhochschule Köln</p>
		</footer>

	<script src="<?php assets_url( 'js/libs/jquery.min.js' ); ?>"></script>
	<script src="<?php assets_url( 'js/libs/bootstrap.min.js' ); ?>"></script>
	<?php
	if ( ! empty( $_['extra_footer'] ) )
		echo $_['extra_footer'];
	?>
	</body>
</html>
