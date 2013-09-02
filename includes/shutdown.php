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

global $db;

if ( defined( 'DEBUG' ) && DEBUG ) {
	?>
<pre>
Time: <?php timer_stop( true ); ?> | DB Queries: <?php echo $db->queries_count; ?>

Queries:
<?php echo implode( "\n", $db->saved_queries ); ?>
</pre>
	<?php
}

/**
 * Close database connection.
 */
$db->close();
