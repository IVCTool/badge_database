<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
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
	//private $admin_menu_sub_reqcat_hook;

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

		//I'm finding that sub menus need to be defined in here so that we
		//	can get ahold of the hook that we need to pre-process
		//	the page request.  This allows us to submit the forms
		//	on the page back to themselves for processing.

		//the admin sub menu for editing the requirement catagories.
		//TODO - explain what the last parameter (numeral) does.
		$hook = add_submenu_page(
			'badgedb-plugin-admin-menu',
			'Edit Requirement Catagories',
			'Requirement Catagories',
			'manage_options',
			'badgedb-plugin-admin-menu-sub-reqcat',
			array($this, 'badgedb_admin_submenu_reqcat_page'), 
			4);
		add_action('load-' . $hook, array($this, 'badgedb_req_cat_page_submit'));

		//The admin sub menu for editing requirements
		$reqHook = add_submenu_page(
			'badgedb-plugin-admin-menu',
			'Edit Requirements',
			'Requirements',
			'manage_options',
			'badgedb-plugin-admin-menu-sub-req',
			array($this, 'badgedb_admin_submenu_req_page'), 
			3);
		add_action('load-' . $reqHook, array($this, 'badgedb_req_page_submit'));

		//The admin sub menu for editing badges
		$badgeHook = add_submenu_page(
			'badgedb-plugin-admin-menu',
			'Edit Badges',
			'Badges',
			'manage_options',
			'badgedb-plugin-admin-menu-sub-badges',
			array($this, 'badgedb_admin_submenu_badges_page'), 
			2);
		add_action('load-' . $badgeHook, array($this, 'badgedb_badges_page_submit'));

		//The admin sub menu for editing abstract test cases
		$atcsHook = add_submenu_page(
			'badgedb-plugin-admin-menu',
			'Edit Abstract Test Cases',
			'Abstract Test Cases',
			'manage_options',
			'badgedb-plugin-admin-menu-sub-abstract',
			array($this, 'badgedb_admin_submenu_atcs_page'), 
			1);
		add_action('load-' . $atcsHook, array($this, 'badgedb_atcs_page_submit'));

		//The sub menus for executable test cases
		$etcsHook = add_submenu_page(
			'badgedb-plugin-admin-menu',
			'Edit Executable Test Cases',
			'Executable Test Cases',
			'manage_options',
			'badgedb-plugin-admin-menu-sub-executabletcs',
			array($this, 'badgedb_admin_submenu_executabletcs_page'),
			3);
		add_action('load-' . $etcsHook, array($this, 'badgedb_executabletcs_page_submit'));

	}//end function

	public function badgedb_admin_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-admin-page.php');	
	}//end function

	public function badgedb_admin_submenu_reqcat_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-requirements-catagories-page.php');	
	}//end function

	public function badgedb_admin_submenu_req_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-requirements-page.php');	
	}

	public function badgedb_admin_submenu_badges_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-badges-page.php');	
	}

	public function badgedb_admin_submenu_atcs_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-abstract-test-case-page.php');
	}

	public function badgedb_admin_submenu_executabletcs_page() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-executabletcs-page.php');
	}

	/**
	 * Called by the hook to handle pre-loading of the requirement catagory admin menu page.
	 */
	public function badgedb_req_cat_page_submit() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-requirements-catagories-formproc.php');
	}//end function

	/**
	 * Called by the hook to handle pre-loading of the requirements admin menu page.
	 */
	public function badgedb_req_page_submit() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-requirements-formproc.php');
	}//end function

	
	/**
	 * Called by the hook to handle pre-loading of the badge admin menu page.
	 */
	public function badgedb_badges_page_submit() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-badges-formproc.php');
	}//end function

	public function badgedb_atcs_page_submit() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-abstract-test-case-formproc.php');
	}//end function

	public function badgedb_executabletcs_page_submit() {
		include( plugin_dir_path(__FILE__) . 'partials/badgedb-executabletcs-formproc.php');
	}

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
