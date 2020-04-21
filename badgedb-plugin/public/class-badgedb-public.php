<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Badgedb
 * @subpackage Badgedb/public
 * @author     Allan Gillis
 */
class Badgedb_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $badgedb    The ID of this plugin.
	 */
	private $badgedb;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $badgedb       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $badgedb, $version ) {

		$this->badgedb = $badgedb;
		$this->version = $version;

	}


	/**
	 * Handles the [badgedbpi] shortcode that allows access to the database.
	 * The function requires one argument that says what part of
	 * the interface is to be returned.
	 * 
	 * Allowed values:
	 * 			attributes:
	 * 				interface
	 * 					pubbadges	-the public badge view (is this mnaybe all the public needs?)
	 *					areqcat		-the admin interface to edit requirement catagories.
	 *			TODO Others
	 */
	public function badgedb_public_entrypoint( $atts ) {

		//We will start with assuming there was an error
		$returnval = "Sorry, an error occured.";

		//check that there is an att
		if (count($atts) > 1) {
			$returnval = $returnval . "  Too many attribnutes used.  Only the attribute 'interface' is supported.";
			return $returnval;
		}

		//setup the 
		$atts = shortcode_atts(
			array(
				'interface'   => 'pubbadges'
			),
			$atts
		);

	
		// code...
	
		//$var = ( strtolower( $args['arg1']) != "" ) ? strtolower( $args['arg1'] ) : 'default';
		//$var = "HELLO GENERAL PUBLIC!";
	
		// code...
		require_once plugin_dir_path( __FILE__ ) . '../dbinterface/badgedb_new_requirement_catagory.php';
		return $returnval;
	}//end function

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Badgedb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Badgedb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->badgedb, plugin_dir_url( __FILE__ ) . 'css/badgedb-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Badgedb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Badgedb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->badgedb, plugin_dir_url( __FILE__ ) . 'js/badgedb-public.js', array( 'jquery' ), $this->version, false );

	}

}
