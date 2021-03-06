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
 * Load the controller based on the current request.
 * http://example.com/{controller}/{action}/[{subactions}]
 */

// Get the controller
$controller = str_replace( '_', '', $request->segments[0] );

// Handle special controllers for some segments
if ( empty( $controller ) )
	$controller = 'home';

if ( ! empty( $request->segments[3] ) )
	$controller = 'gallery';

// Get the action, if none set to index
$action = empty( $request->segments[1] ) ? 'index' : $request->segments[1];

// Get the controller class
$class = '';
switch ( $controller ) {
	case 'install' :
	case 'home' :
	case 'login' :
	case 'logout' :
	case 'register' :
	case 'settings' :
	case 'user' :
	case 'upload' :
	case 'gallery' :
	case 'ajax' :
	case 'search' :
		$class = ucfirst( $controller ) . '_Controller';
		require APP_INCLUDES_PATH . "/controllers/class-$controller-controller.php";
		break;
	default:
}

// Handle special actions for some controllers
if ( $controller === 'user' )
	$action = 'index';

if ( $controller === 'gallery' )
	$action = 'index';

// Check if the current action hadnler exists in the controller class
if ( ! method_exists( $class, $action ) ) {
	$controller = 'error';
	$class = 'Error_Controller';
	require APP_INCLUDES_PATH .  "/controllers/class-$controller-controller.php";
	$action = 'index';
}

// Create an instance of the controller class
$object = new $class;

// Call the action methode on the controller and pass the current request
call_user_func_array( array( $object, $action ), array( 'request' => $request ) );
