<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann
 * @author    Dario Vizzaccaro
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */

/**
 * Checks if the PHP version matches the required 5.3.0 and
 * if mysqli extension is loaded.
 *
 * @return void
 */
function check_php_mysql_version() {
	$php_version = phpversion();

	if ( version_compare( '5.3.0', $php_version, '>' ) )
		die( sprintf( 'Your server is running PHP version %1$s but this application requires at least 5.3.0.', $php_version ) );

	 if ( ! extension_loaded( 'mysqli' ) )
		die( 'Your server is not running the MySQLi extension but this application requires it.' );
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
			redirect( get_site_url( '_install' ) );
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

	$url  = get_site_url( '/assets/' );

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
