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
	 * the atts['interface'] is to be returned.
	 * 
	 * Allowed values:
	 * 			attributes:
	 * 				atts['interface']
	 * 					table		All of the badges as a table
	 * 					single		Info about a single badge
	 *				identifier		Only used if single; the db identifier field for the badge record.
	 */
	public function badgedb_public_entrypoint( $atts, $content = null, $tag='' ) {


		//Lets prep the attributes.
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		
		//We will start with assuming there was an error
		$returnval = "<div class=\"badgedb-error\"><p style=\"color:red\">Sorry, an error occured displaying the Capability Badges.</p></div>";
		//This might be needed in a couple places.
		$paramError = <<<ENDE
		<div class="badgedb-error">
		<p style="color:red">Badge DB Error: Only the attributes 'atts['interface']', and 'identifier' are supported.  
		'atts['interface']' must be set to either 'table' or 'single'.  If 'single' is  used then the second attribute 'identifier' must 
		be set to a valid badge indentifier.</p>
		</div>
		ENDE;

		//check that there are mo more than 2 attributes.
		if (count($atts) < 1 || count($atts) > 2) {
			return $paramError;
		}//end if wrong number of parameters

		//Now make sure the attribute for atts['interface'] is set.
		if(isset($atts['interface'])) {
			if( $atts['interface'] == "table") {
				require_once plugin_dir_path( __FILE__ ) . '/partials/badgedb-public-display-table.php';
			}//end if table
			if ($atts['interface'] == "single") {
				require_once plugin_dir_path( __FILE__ ) . '/partials/badgedb-public-display-single.php';
			}
		} else {
			//something is wrong and atts['interface'] wasn't set. it's required.
			return $paramError;
		}//end if atts['interface'] is set
	
		
		//If we make it this far we should ahve something to show!
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

	}//end function

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

	}//end function

}//end class
