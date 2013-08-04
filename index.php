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
 * Loads all the needed classes, inits application setup and
 * database connection, etc
 */
require 'includes/bootstrap.php';


/**
 * Handles the content.
 */
require 'includes/load.php';


/**
 * Loads stuff, which needs to be run at the end of script.
 */
require 'includes/shutdown.php';
