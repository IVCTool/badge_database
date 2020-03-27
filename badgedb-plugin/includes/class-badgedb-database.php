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

	//These are just used to make it a bit easier to deal with the database creation and removal
	const REQCATEGORIES_TABLE_NAME = "reqcategories";
	const BADGES_TABLE_NAME = "badges";

	/**
	 * This function sets up all the database structure when the plugin is installed.
	 * 
	 * @since		1.0.0
	 * 
	 */
	public static function badgedb_database_install() {
		global $wpdb;
		//TODO this is currently just a test
		$table_prefix = $wpdb->prefix . "badgedb_";
		$charset_collate = $wpdb->get_charset_collate();

		//make the requirements categories table
		$reqcat_table_name = $table_prefix . self::REQCATEGORIES_TABLE_NAME;
		$reqcat_query = "CREATE TABLE $reqcat_table_name (
			`name` varchar(255) NOT NULL,
			`description` longtext NOT NULL,
			`identifier` varchar(10) NOT NULL,
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			PRIMARY KEY (`id`)
		  ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;";
		$wpdb->query($reqcat_query);

		//Make the badges table
		$badges_table_name = $table_prefix . self::BADGES_TABLE_NAME;
		$badges_query = "CREATE TABLE $badges_table_name (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`description` longtext NOT NULL,
			`graphicfile` varchar(255) DEFAULT NULL,
			`identifier` varchar(25) NOT NULL,
			PRIMARY KEY (`id`)
		  ) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;";
		  $wpdb->query($badges_query);

	}//end function

	/**
	 * This function deals with anything that needs doing when the plugin is uninstalled.
	 * @since	1.0.0
	 */
	public static function badgedb_database_uninstall() {
		//Drop all the database tables
		self::drop_tables();

	}//end function

	/**
	 * This is just a wrapper to drop the tables.  Dropping is not as complicated as creating as you only need the name.
	 * @since		1.0.0
	 */
	private static function drop_tables() {
		global $wpdb;
		$table_prefix = $wpdb->prefix . "badgedb_";

		$all_tables = array(self::REQCATEGORIES_TABLE_NAME, self::BADGES_TABLE_NAME);
		foreach ($all_tables as $t) {
			$t_name = $table_prefix . $t;
			$sql = "DROP TABLE $t_name;";
			$wpdb->query($sql);
		}//end foreach
	}//end function


}//end class
