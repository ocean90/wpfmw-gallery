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
 * Loads a template from a template name.
 */
class View {

	/**
	 * Contains the variables which should be avaiable in the template.
	 *
	 * @var array
	 */
	private $_ = array();

	/**
	 * Contains the current template name
	 *
	 * @var string
	 */
	private $template_name = '';

	/**
	 * Constructor.
	 * Sets the template name.
	 *
	 * @param  string $template_name The template name which should be loaded.
	 */
	function __construct( $template_name = '' ) {
		$this->template_name = $template_name;
	}

	/**
	 * Assigns a value-key pair to use them in the template.
	 *
	 * @param  string $key Key
	 * @param  string $value Value
	 * @return void
	 */
	public function assign( $key, $value ) {
		$this->_[ $key ] = $value;
	}

	/**
	 * Sets page title.
	 *
	 * @param  string $value Value
	 * @return void
	 */
	public function set_page_title( $title ) {
		$this->assign( 'page_title', $title );
	}

	/**
	 * Sets additonal header elements.
	 *
	 * @param  string $value Value
	 * @return void
	 */
	public function set_extra_header( $value ) {
		$this->assign( 'extra_header', $value );
	}

	/**
	 * Sets additonal footer elements.
	 *
	 * @param  string $value Value
	 * @return void
	 */
	public function set_extra_footer( $value ) {
		$this->assign( 'extra_footer', $value );
	}

	/**
	 * Renders a view template.
	 *
	 * @return void
	 */
	public function render() {
		// Set the path
		$path =  APP_INCLUDES_PATH . "/views/$this->template_name.php";

		// Check if template exists
		if ( ! is_file( $path ) ) {
			echo 'Template not found';
			return;
		}

		// Make $_ available for using in template, both will work
		$_ = $this->_;

		// Enable buffer
		ob_start();

		// Load the template file
		require $path;

		// Print the buffer
		echo ob_get_clean();
	}

}
