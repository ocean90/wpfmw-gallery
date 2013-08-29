<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */

/**
 * Checks if the PHP version matches the required 5.3.0,
 * if mysqli extension is loaded and sessions are enabled
 *
 * @return void
 */
function check_compatibility() {
	$php_version = phpversion();

	if ( version_compare( '5.4.0', $php_version, '>' ) )
		die( sprintf( 'Your server is running PHP version %1$s but this application requires at least 5.4.0.', $php_version ) );

	 if ( ! extension_loaded( 'mysqli' ) )
		die( 'Your server is not running the MySQLi extension but this application requires it.' );

	if ( session_status() === 0 )
		die( 'Your server has sessions disabled but this application requires it.' );
}

/**
 * Sets location header for a redirection with a
 * specific HTTP status.
 *
 * @param  string $location The location to redirect to.
 * @param  int    $status   Status code to use. Default: 302.
 * @return void
 */
function redirect( $location, $status = 302 ) {
	header( "Location: $location", true, $status );
}

/**
 * Checks if application is installed. If not, it redirects
 * to the install page.
 *
 * @return void
 */
function maybe_install() {
	global $db, $request;

	// Prevent redirection loop
	if ( '_install' !== $request->segments[0] ) {
		// Check if a table exists
		$result = $db->query( "SHOW TABLES LIKE '$db->gallery'" );

		// If not, redirect to install page
		if ( ! $result ) {
			redirect( get_site_url( '_install/' ) );
			exit;
		}
	}
}

/**
 * Prints information for debugging in a nice way.
 *
 * @param  mixed   $debug  The information which should be printed.
 * @param  boolean $exit   If true function will exit. Default: false.
 * @return void
 */
function debug( $debug, $exit = false) {
	echo "\n<pre>";

    if ( is_array( $debug ) || is_object( $debug ) )
            echo htmlentities( print_r( $debug, true ) );
	elseif ( is_string( $debug ) )
		echo "string(" . strlen( $debug ) . ") \"" . htmlentities( $debug ) . "\"\n";
	else
		var_dump( $debug );

	echo "</pre>";

	if ( $exit )
		die();
}


/**
 * Returns the site URL of the application.
 *
 * @param  string $path Path relative to the site URL.
 * @return string       Site URL link with optional path appended.
 */
function get_site_url( $path = '' ) {
	global $app;

	$url = rtrim( $app->config->url, '/' );

	if ( $path && is_string( $path ) )
		$url .= '/' . ltrim( $path, '/' );

	return $url;
}

/**
 * Prints the site URL of the application.
 *
 * @param  string $path Path relative to the site url.
 * @return void
 */
function site_url( $path = '' ) {
	echo get_site_url( $path );
}

/**
 * Returns the assets URL of the application.
 *
 * @param  string $path Path relative to the assets URL.
 * @return string       Assets URL link with optional path appended.
 */
function get_assets_url( $path = '' ) {
	global $app;

	$url  = get_site_url( '/assets' );

	if ( $path && is_string( $path ) )
		$url .= '/' . ltrim( $path, '/' );

	return $url;
}

/**
 * Prints the assets URL of the application.
 *
 * @param  string $path Path relative to the assets url.
 * @return void
 */
function assets_url( $path = '' ) {
	echo get_assets_url( $path );
}

/**
 * Sanitizes a string key.
 * Only lowercase alphanumeric characters, dashes and underscores are allowed.
 *
 * @param  string $key String key.
 * @return string      Sanitized key.
 */
function sanitize_key( $key ) {
	$key = strtolower( $key );
	return preg_replace( '/[^a-z0-9_\-]/', '', $key );
}

/**
 * Hashes a password via PasswordHash.
 * See APP_INCLUDES_PATH . 'libs/PasswordHash.php'
 *
 * @param  string $password Password as plain text.
 * @return string           Hashed password.
 */
function hash_password( $password ) {
	global $hasher;

	if ( empty( $hasher ) )
		$hasher = new PasswordHash( 8, false );

	return $hasher->HashPassword( $password );
}

/**
 * Hashes a password via PasswordHash.
 * See APP_INCLUDES_PATH . 'libs/PasswordHash.php'
 *
 * @param  string  $password Password as plain text.
 * @return boolean           False, if the $password does not match the hashed password.
 */
function check_password( $password, $hash ) {
	global $hasher;

	if ( empty( $hasher ) )
		$hasher = new PasswordHash( 8, false );

	return $hasher->CheckPassword( $password , $hash );
}

/**
 * Checks if the current visitor is a logged in user.
 *
 * @return boolean True if user is logged in, false if not logged in.
 */
function is_user_logged_in() {
	global $app;

	if ( $app->current_user !== null )
		return true;

	return false;
}

/**
 * Calls error controller and index method to render
 * 404 view.
 *
 * @return void
 */
function show_404() {
	require APP_INCLUDES_PATH .  "/controllers/class-error-controller.php";
	$controller = new Error_Controller();
	$controller->index();
}
