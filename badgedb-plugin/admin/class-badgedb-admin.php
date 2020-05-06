<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      0.1.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin
 * @author     Allan Gillis
 */
class Badgedb_Admin {

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

	private $admin_menu_hook;
	private $admin_menu_sub_reqcat_hook;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $badgedb       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->badgedb = $plugin_name;
		$this->version = $version;

	}//end construct


	/**
	 * Takes care of adding the admin menu to the WP admin bar.
	 * 
	 * @since	1.0.0
	 */
	public function add_admin_menu() {

		$this->admin_menu_hook = add_menu_page(
			'BadgeDB Plugin',
			'BadgeDB Plugin',
			'manage_options',
			'badgedb-plugin-admin-menu',
			array($this, 'badgedb_admin_page'),
			'dashicons-store',
			1);

		//$this->admin_menu_sub_reqcat_hook = add_submenu_page(
		$hook = add_submenu_page(
			'badgedb-plugin-admin-menu',
			'Edit Requirement Catagories',
			'Requirement Catagories',
			'manage_options',
			'badgedb-plugin-admin-menu-sub-reqcat',
			array($this, 'badgedb_admin_submenu_reqcat_page'),
			1);
		add_action('load-' . $hook, array($this, 'badgedb_req_cat_page_submit'));

	}//end function

	// public function get_admin_menu_hook() {
	//  	return $this->admin_menu_hook;
	// }

	public function get_admin_menu_sub_reqcat_hook() {
		return $this->admin_menu_sub_reqcat_hook;
	}

	public function badgedb_admin_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-admin-page.php');	
	}//end function

	public function badgedb_admin_submenu_reqcat_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-requirements-catagories-page.php');	
	}//end function

	/**
	 * 
	 */
	public function badgedb_req_cat_page_submit() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-requirements-catagories-formproc.php');
	}//end function

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->badgedb, plugin_dir_url( __FILE__ ) . 'css/badgedb-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->badgedb, plugin_dir_url( __FILE__ ) . 'js/badgedb-admin.js', array( 'jquery' ), $this->version, false );

	}

}//end class
