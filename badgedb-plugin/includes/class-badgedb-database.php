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
	const EXECUTABLETCS_TABLE_NAME = "executabletcs";
	const ABSTRACT_TEST_CASES_TABLE_NAME = "abstracttcs";
	const REQUIREMENTS_TABLE_NAME = "requirements";
	const BADGES_TABLE_NAME = "badges";
	const BADGES_HAS_BADGES_TABLE_NAME = "badges_has_badges";
	const BADGES_HAS_REQUIREMENTS_TABLE_NAME = "badges_has_requirements";
	const ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME = "abstracttcs_has_requirements";
	

	/**
	 * This array is just here to make the drop tables function easier to read.
	 * 
	 * Note: tables with foreign keys need to be dropped before the table they refer to, so make
	 *		sure they are first in this list.  For example requirements needs to be dropped before
	 *		reqcategories.
	*/
	protected static $all_tables = array(
						self::REQUIREMENTS_TABLE_NAME, 
						self::EXECUTABLETCS_TABLE_NAME,
						self::REQCATEGORIES_TABLE_NAME, 
						self::ABSTRACT_TEST_CASES_TABLE_NAME, 
						self::BADGES_HAS_BADGES_TABLE_NAME, 
						self::BADGES_HAS_REQUIREMENTS_TABLE_NAME, 
						self::BADGES_TABLE_NAME, 
						self::ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME
					);

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

		//It would be a lot better to read all of this from an SQL file

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

		//make the abstract test case table
		$abstcs_table_name = $table_prefix . self::ABSTRACT_TEST_CASES_TABLE_NAME;
		$abstcs_query = "CREATE TABLE $abstcs_table_name (
			`filename` varchar(255) CHARACTER SET armscii8 NOT NULL,
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`identifier` varchar(25) NOT NULL,
			`name` varchar(25) DEFAULT NULL,
			`description` longtext NOT NULL,
			`version` varchar(45) NOT NULL,
			PRIMARY KEY (`id`)
		  ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;";
		  $wpdb->query($abstcs_query);

		//make the requirements table
		$req_table_name = $table_prefix . self::REQUIREMENTS_TABLE_NAME;
		$req_query = "CREATE TABLE $req_table_name (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`identifier` varchar(25) NOT NULL,
			`description` longtext NOT NULL,
			`reqcategories_id` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `fk_requirements_reqcategories_idx` (`reqcategories_id`),
			CONSTRAINT `fk_requirements_reqcategories` FOREIGN KEY (`reqcategories_id`) REFERENCES `$reqcat_table_name` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		  ) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;";
		$wpdb->query($req_query);

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

		  //badges_has_badges table
		  $badgeshb_table_name = $table_prefix . self::BADGES_HAS_BADGES_TABLE_NAME;
		  $bhb_query = "CREATE TABLE $badgeshb_table_name (
			`badges_id` int(10) unsigned NOT NULL,
			`badges_id_dependency` int(10) unsigned NOT NULL,
			PRIMARY KEY (`badges_id`,`badges_id_dependency`),
			KEY `fk_badges_has_badges_badges2_idx` (`badges_id_dependency`),
			KEY `fk_badges_has_badges_badges1_idx` (`badges_id`)
		  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		  $wpdb->query($bhb_query);

		  //badges_has_requirements
		  $badgeshr_table_name = $table_prefix . self::BADGES_HAS_REQUIREMENTS_TABLE_NAME;
		  $bhr_query = "CREATE TABLE $badgeshr_table_name (
			`requirements_id` int(10) unsigned NOT NULL,
			`badges_id` int(10) unsigned NOT NULL,
			PRIMARY KEY (`requirements_id`,`badges_id`),
			KEY `fk_requirements_has_badges_badges1_idx` (`badges_id`),
			KEY `fk_requirements_has_badges_requirements1_idx` (`requirements_id`)
		  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		  $wpdb->query($bhr_query);

		  //abstract test case requirements
		  $abstracttchr_table_name = $table_prefix . self::ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME;
		  $atchr_query = "CREATE TABLE $abstracttchr_table_name (
			`abstracttcs_id` int(10) unsigned NOT NULL,
			`requirements_id` int(10) unsigned NOT NULL,
			KEY `requirements_id` (`requirements_id`)
		  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		  $wpdb->query($atchr_query);

		  //executable test cases
		  $etcs_table_name = $table_prefix . self::EXECUTABLETCS_TABLE_NAME;
		  $etcs_query = "CREATE TABLE $etcs_table_name (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`Description` text NOT NULL,
			`classname` varchar(255) NOT NULL,
			`version` varchar(45) NOT NULL,
			`abstracttcs_id` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id`,`abstracttcs_id`),
			KEY `fk_executabletcs_abstracttcs1_idx` (`abstracttcs_id`),
			CONSTRAINT `executabletcs_ibfk_1` FOREIGN KEY (`abstracttcs_id`) REFERENCES `$abstcs_table_name` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
		  ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";
		  $wpdb->query($etcs_query);

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
		foreach (self::$all_tables as $t) {
			$t_name = $table_prefix . $t;
			$sql = "DROP TABLE $t_name;";
			$wpdb->query($sql);
		}//end foreach
	}//end function


}//end class
