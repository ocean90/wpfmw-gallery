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

( function( $ ){
	/**
	 * Checks strength of passwords.
	 *
	 * @return {void}
	 */
	function check_password_strength() {
		var password1 = $('#password1').val(),
			password2 = $('#password2').val(),
			username = $('#username').val(),
			$progressBar = $('#password-strength-result .progress-bar'),
			$mismatchLabel = $('#password-mismatch'),
			strength;

		$mismatchLabel.css('visibility', 'hidden');
		if ( password1 !== password2 && password2.length > 0 ) {
			$mismatchLabel.css('visibility', 'visible');
			return;
		}

		// Remove existing classes
		$progressBar.removeClass('progress-bar-danger progress-bar-warning progress-bar-success');

		// If password is empty, clear progress bar
		if ( password1.length === 0 ) {
			$progressBar.width('0%');
			return;
		}

		var strength = zxcvbn( password1, [ username ] );

		switch (strength.score) {
			case 0:
				$progressBar.addClass('progress-bar-danger').width('10%');
				break;
			case 1:
				$progressBar.addClass('progress-bar-danger').width('30%');
				break;
			case 2:
				$progressBar.addClass('progress-bar-warning').width('40%');
				break;
			case 3:
				$progressBar.addClass('progress-bar-warning').width('70%');
				break;
			case 4:
				$progressBar.addClass('progress-bar-success').width('100%');
				break;
			default:
				break;
		}
	}

	$( function() {
		$('#password1').keyup( check_password_strength );
		$('#password2').keyup( check_password_strength );
	});

})(jQuery);


