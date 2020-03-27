<?php

/**
 * The class responsible for dealing with the badge database tables.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Badgedb
 * @subpackage Badgedb/includes
 * @author     Allan Gillis
 */
class Badgedb_Database {


	public static function badgedb_database_install() {
		global $wpdb;
		//TODO this is currently just a test
		$table_name = $wpdb->prefix . "_badgedb_test";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name tinytext NOT NULL,
			text text NOT NULL,
			url varchar(55) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		  ) $charset_collate;";
		  
		  $wpdb->query($sql, $table_name);

	}//end function

	public static function badgedb_database_uninstall() {
		global $wpdb;
		//TODO this is currently just a test
		$table_name = $wpdb->prefix . "_badgedb_test";
		$sql = "DROP TABLE $table_name;";
		$wpdb->query($sql);

	}//end function


}//end class
