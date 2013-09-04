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

	if ( ! extension_loaded( 'gd' ) || ! function_exists( 'gd_info' ) )
		die( 'Your server is not running the GD extension but this application requires it.' );
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
		$result = $db->query( "SHOW TABLES LIKE '$db->galleries'" );

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
 * Returns the content URL of the application.
 *
 * @param  string $path Path relative to the content URL.
 * @return string       Content URL link with optional path appended.
 */
function get_content_url( $path = '' ) {
	$dir = str_replace( APP_PATH, '', APP_CONTENT_PATH );

	$url = get_site_url( $dir );

	if ( $path && is_string( $path ) )
		$url .= ltrim( $path, '/' );

	return $url;
}

/**
 * Prints the content URL of the application.
 *
 * @param  string $path Path relative to the content url.
 * @return void
 */
function content_url( $path = '' ) {
	echo get_content_url( $path );
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
 * Sanitizes a string for using as slug.
 * Only lowercase alphanumeric characters, dashes and underscores are allowed.
 * Replaces whitespace with dashes.
 *
 * @param  string $slug String slug.
 * @return string       Sanitized slug.
 */
function sanitize_slug( $slug ) {
	$slug = strtolower( $slug );
	$slug = str_replace( ' ', '-', $slug );
	return preg_replace( '/[^a-z0-9_\-]/', '', $slug );
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
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
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

/**
 * Escapes a HTML attribute value for output.
 *
 * @param  string $value Text to escape.
 * @return string        Escaped text.
 */
function escape_attribute( $value ) {
	return htmlspecialchars( $value, ENT_QUOTES );
}

/**
 * Starts a timer.
 */
function timer_start() {
	global $time_start;
	$time_start = microtime( true );
}

/**
 * Stops the timer and returns the total time.
 *
 * @param  boolean $echo  Return or echo.
 * @return string         Time difference.
 */
function timer_stop( $echo = false ) {
	global $time_start;

	$time_end = microtime( true );
	$time_total = $time_end - $time_start;

	if ( ! $echo )
		return $time_total;

	echo $time_total;
}

/**
 * Returns serialized data for arrays and objects.
 *
 * @param  mixed  $data Data to serialize.
 * @return mixed        Serialized data.
 */
function maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) ) {
		return serialize( $data );
	}

	return $data;
}

/**
 * Checks if a string is serialized.
 *
 * @param  string  $string Maybe serialized string.
 * @return boolean         True if serialized, false if not.
 */
function is_serialized( $string ) {
	$data = @unserialize( $string );
	if ( $string === 'b:0;' || $data !== false ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Creates a dir with the correct permissions and recursive.
 *
 * @param  string  $target The full path to the dir.
 * @return boolean         True on success, false on failure.
 */
function mkdir_rec_with_perm( $target ) {
	$target = rtrim( $target, '/' );

	if ( file_exists( $target ) )
		return @is_dir( $target );

	// We need to find the permissions of the parent folder that exists and inherit that.
	$target_parent = dirname( $target );
	while ( '.' != $target_parent && ! is_dir( $target_parent ) ) {
		$target_parent = dirname( $target_parent );
	}

	// Get the permission bits.
	if ( $target_parent && '.' != $target_parent ) {
		$stat = @stat( $target_parent );
		$dir_perms = $stat['mode'] & 0007777;
	} else {
		$dir_perms = 0777;
	}

	if ( @mkdir( $target, $dir_perms, true ) ) {
		return true;
	}

	return false;
}

/**
 * Converts given date string into a different format.
 *
 * @param  string $format Format of the date to return.
 * @param  string $date   Date string to convert.
 * @return string         Formatted date string.
 */
function mysql2date( $format, $date ) {
	$i = strtotime( $date );

	return date( $format, $i );
}

/**
 * Shortens an UTF-8 encoded string without breaking words.
 *
 * Source: http://wordpress.stackexchange.com/a/11089
 *
 * @param  string $string     string to shorten
 * @param  int    $max_chars  maximal length in characters
 * @param  string $append     replacement for truncated words.
 * @return string
 */
function utf8_truncate( $string, $max_chars = 200, $append = "\xC2\xA0…" )
{
    $string = strip_tags( $string );
    $string = html_entity_decode( $string, ENT_QUOTES, 'utf-8' );
    // \xC2\xA0 is the no-break space
    $string = trim( $string, "\n\r\t .-;–,—\xC2\xA0" );
    $length = strlen( utf8_decode( $string ) );

    // Nothing to do.
    if ( $length < $max_chars )
    {
        return $string;
    }

    // mb_substr() is in /wp-includes/compat.php as a fallback if
    // your the current PHP installation doesn’t have it.
    $string = mb_substr( $string, 0, $max_chars, 'utf-8' );

    // No white space. One long word or chinese/korean/japanese text.
    if ( FALSE === strpos( $string, ' ' ) )
    {
        return $string . $append;
    }

    // Avoid breaks within words. Find the last white space.
    if ( extension_loaded( 'mbstring' ) )
    {
        $pos   = mb_strrpos( $string, ' ', 'utf-8' );
        $short = mb_substr( $string, 0, $pos, 'utf-8' );
    }
    else
    {
        // Workaround. May be slow on long strings.
        $words = explode( ' ', $string );
        // Drop the last word.
        array_pop( $words );
        $short = implode( ' ', $words );
    }

    return $short . $append;
}
