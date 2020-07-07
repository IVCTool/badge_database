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
 * For dealing with the badgedb database tables
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
	 * This section has the constants that define the maximum field lengths for using in form validation
	 */
	public const REQCATEGORIES_NAME_FIELD_MAX = 255;
	public const REQCATEGORIES_DESCRIPTION_FIELD_MAX = 1431655765;
	public const REQCATEGORIES_IDENTIFIER_FIELD_MAX = 10;

	public const REQUIREMENTS_IDENTIFIER_FIELD_MAX = 25;
	public const REQUIREMENTS_DESCRIPTION_FIELD_MAX = 1431655765;
	public const REQUIREMENTS_CATAGORY_FIELD_MAX = 10;

	public const BADGES_DESCRIPTION_FIELD_MAX = 1431655765;
	public const BADGES_IDENTIFIER_FIELD_MAX = 25;
	public const BADGES_WPID_FIELD_MAX = 20;

	//public const ABSTRACT_TEST_CASES_FILENAME_FIELD_MAX = 255;
	public const ABSTRACT_TEST_CASES_IDENTIFIER_FIELD_MAX = 25;
	public const ABSTRACT_TEST_CASES_NAME_FIELD_MAX = 25;
	public const ABSTRACT_TEST_CASES_DESCRIPTION_FIELD_MAX = 1431655765;
	public const ABSTRACT_TEST_CASES_VERSION_FIELD_MAX = 45;
	public const ABSTRACT_TEST_CASES_WPID_FIELD_MAX = 20;
	

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
		//Due to the way WordPress handles file uploads the table needs
		//to be different than it was in the original.  Basically we shouldn't
		//use the file name, but instead need to track the ID that WordPress 
		//uses in it's internal database for posts.  We can then retrieve
		//the file by running a query on the wp_posts table with that ID.
		//
		//NOTE: THIS MAKES THE TWO VERSIONS OF THE DATABASE SCHEMA INCOMPATIBLE!
		$abstcs_table_name = $table_prefix . self::ABSTRACT_TEST_CASES_TABLE_NAME;
		$abstcs_query = "CREATE TABLE $abstcs_table_name (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`identifier` varchar(25) NOT NULL,
			`name` varchar(25) DEFAULT NULL,
			`description` longtext NOT NULL,
			`version` varchar(45) NOT NULL,
			`wpid` bigint(20) UNSIGNED NOT NULL,
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
			`wpid` bigint(20) UNSIGNED,
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

		  //fill in the base data
		  self::insert_base_data();

	}//end function

	/**
	 * This just inserts a new requirement catagory record.
	 * 
	 * @since	1.0.0
	 */
	public static function insert_new_reqcat($theIdent, $theName, $theDesc) {
		global $wpdb;
		//$table_prefix = $wpdb->prefix . "badgedb_";
		$table_name = $wpdb->prefix . "badgedb_" . self::REQCATEGORIES_TABLE_NAME;
		$theData = array('identifier' => $theIdent, 'name' => $theName, 'description' => $theDesc);
		$theFormat = array('%s', '%s', '%s');
		$wpdb->insert($table_name, $theData, $theFormat);
	}//end function

	/**
	 * Gets back all the requirement catagories.
	 * 
	 * @since	1.0.0
	 */
	public static function get_requirement_catagories() {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::REQCATEGORIES_TABLE_NAME;
		$q = "SELECT * FROM " . $table_name . ";";
		$results = $wpdb->get_results($q, ARRAY_A);
		if (count($results) < 1) {
			//It does something wierd when there are no results, so lets just set it to null if the array is empty.
			//The strange behaviour could also be related to being in WP_DEBUG = true mode and not show up in production.
			return null;
		} else {
			return $results;
		}

	}//end function

	/**
	 * Deletes the requirement catagory with the id passed in.
	 * 
	 * @since	1.0.0
	 */
	public static function delete_reqcat($theId) {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::REQCATEGORIES_TABLE_NAME;
		$where = array('id' => $theId);
		$wpdb->delete($table_name, $where, array('%d'));

	}//end function

	/**
	 * Updates a requirement catagory record.
	 * 
	 * @since	1.0.0
	 */
	public static function update_reqcat($theId, $theIdent, $theName, $theDesc) {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::REQCATEGORIES_TABLE_NAME;
	
		//For whatever reason, you need to call update this way instead of how I did it for 'insert' above.
		//If you don't you get array to string conversion errors when you try to pass the arrays into the update function.
		$wpdb->update($table_name, array('identifier' => $theIdent, 'name' => $theName, 'description' => $theDesc), 
					array('id' => $theId), array('%s', '%s', '%s'), array('id' => $theId));
	}//end update_reqcat


	/**
	 * This just inserts a new requirement record.
	 * 
	 * @since	1.0.0
	 */
	public static function insert_new_requirement($theIdent, $theDesc, $theCat) {
		global $wpdb;
		//$table_prefix = $wpdb->prefix . "badgedb_";
		$table_name = $wpdb->prefix . "badgedb_" . self::REQUIREMENTS_TABLE_NAME;
		$theData = array('identifier' => $theIdent, 'description' => $theDesc, 'reqcategories_id' => $theCat);
		$theFormat = array('%s', '%s', '%d');
		$wpdb->insert($table_name, $theData, $theFormat);
	}//end function

	/**
	 * Gets back all the requirements.
	 * 
	 * @since	1.0.0
	 */
	public static function get_requirements() {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::REQUIREMENTS_TABLE_NAME;
		$q = "SELECT * FROM " . $table_name . ";";
		$results = $wpdb->get_results($q, ARRAY_A);
		if (count($results) < 1) {
			//It does something wierd when there are no results, so lets just set it to null if the array is empty.
			//The strange behaviour could also be related to being in WP_DEBUG = true mode and not show up in production.
			return null;
		} else {
			return $results;
		}
	}//end function	

	/**
	 * Deletes the requirement with the id passed in.
	 * 
	 * @since	1.0.0
	 */
	public static function delete_requirement($theId) {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::REQUIREMENTS_TABLE_NAME;
		$where = array('id' => $theId);
		$wpdb->delete($table_name, $where, array('%d'));

	}//end function

	/**
	 * Updates a requirement record.
	 * 
	 * @since	1.0.0
	 */
	public static function update_requirement($theId, $theIdent, $theDesc, $theCatagory) {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::REQUIREMENTS_TABLE_NAME;
	
		//For whatever reason, you need to call update this way instead of how I did it for 'insert' above.
		//If you don't you get array to string conversion errors when you try to pass the arrays into the update function.
		$wpdb->update($table_name, array('identifier' => $theIdent, 'description' => $theDesc, 'reqcategories_id' => $theCatagory), 
					array('id' => $theId), array('%s', '%s', '%d'), array('id' => $theId));
	}//end update_reqcat


	/**
	 * This just inserts a new abstract test case.
	 * 
	 * @since	1.0.0
	 */
	public static function insert_new_atcs($theIdent, $theDesc, $theName, $theFileID, $theVersion, $theRequirements) {
		global $wpdb;
		//$table_prefix = $wpdb->prefix . "badgedb_";
		$table_name = $wpdb->prefix . "badgedb_" . self::ABSTRACT_TEST_CASES_TABLE_NAME;
		$theData = array('identifier' => $theIdent, 'description' => $theDesc, 'name' => $theName, 'wpid' => $theFileID, 'version' => $theVersion);
		$theFormat = array('%s', '%s', '%s', '%d', '%s');
		$inserted = $wpdb->insert($table_name, $theData, $theFormat);
		
		//We need to create all the relevant records in abstracttcs_has_requirements
		//We need the id of the new record
		$newID = $wpdb->insert_id;
		error_log("Requiremens for the new atcs: " . count($theRequirements));
		//We need to make sure the record was inserted AND that there is an array of requirements
		if ($inserted != false && is_array($theRequirements)) {
			$tn = $wpdb->prefix . "badgedb_" . self::ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME;
			foreach ($theRequirements as $r) {
				$wpdb->insert($tn, array('abstracttcs_id' => $newID, 'requirements_id' => $r), array('%d', '%d'));
			}//end loop
		}//end if

	}//end function

		/**
	 * Gets back all the abstract test cases.
	 * 
	 * @since	1.0.0
	 */
	public static function get_abstract_test_cases() {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::ABSTRACT_TEST_CASES_TABLE_NAME;
		$q = "SELECT * FROM " . $table_name . ";";
		$results = $wpdb->get_results($q, ARRAY_A);
		if (count($results) < 1) {
			//It does something wierd when there are no results, so lets just set it to null if the array is empty.
			//The strange behaviour could also be related to being in WP_DEBUG = true mode and not show up in production.
			return null;
		} else {
			return $results;
		}

	}//end function

	/**
	 * Deletes the abstract test case with the id passed in.
	 * 
	 * @since	1.0.0
	 */
	public static function delete_abstract_test_case($theId, $attachementId) {
		global $wpdb;
		//First we need to delete the abstract test case.
		$table_name = $wpdb->prefix . "badgedb_" . self::ABSTRACT_TEST_CASES_TABLE_NAME;
		$where = array('id' => $theId);
		$wpdb->delete($table_name, $where, array('%d'));

		//once that's done we can delete the attachment.
		wp_delete_attachment($attachementId, true);

		//And then all the records in the list of requirements
		$table_name = $wpdb->prefix . "badgedb_" . self::ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME;
		$where = array('abstracttcs_id' => $theId);
		$wpdb->delete($table_name, $where, array('%d'));
	}//end function

	
	/**
	 * This modifys an existing abstract test case.
	 * 
	 * @since	1.0.0
	 */
	public static function modify_atcs($theId, $theIdent, $theDesc, $theName, $theVersion, $fileUploaded, $theRequirements, $theFileID = -1) {
		global $wpdb;
		//$table_prefix = $wpdb->prefix . "badgedb_";
		$table_name = $wpdb->prefix . "badgedb_" . self::ABSTRACT_TEST_CASES_TABLE_NAME;

		//We need to handle the update differently if the file was altered.
		if ($fileUploaded) {
			//remove the old attachment
			//If you don't select all from the row in the query you don't get what you're expecting.
			//For example SELCET 'wpid" .... will return a row with a field called wpid with the string value wpid, not the int value you
			$q = "SELECT * FROM " . $table_name . " WHERE id=" . $theId . ";";
			$oldRecord = $wpdb->get_row($q, ARRAY_A);
			wp_delete_attachment($oldRecord['wpid'], true);
			//For whatever reason, you need to call update this way instead of how I did it for 'insert' above.
			//If you don't you get array to string conversion errors when you try to pass the arrays into the update function.
			$wpdb->update($table_name, array('identifier' => $theIdent, 'description' => $theDesc, 'name' => $theName, 'version' => $theVersion, 'wpid' => $theFileID), 
				array('id' => $theId), array('%s', '%s', '%s', '%s', '%d'));
		} else {
			//and if the file didn't change we just won't change it.
			$wpdb->update($table_name, array('identifier' => $theIdent, 'description' => $theDesc, 'name' => $theName, 'version' => $theVersion), 
				array('id' => $theId), array('%s', '%s', '%s', '%s'));
		} //end if the file wasn't updated

		//Now we also need to update the requirements associated with this.
		//First just blow away whatever was in there
		$table_name = $wpdb->prefix . "badgedb_" . self::ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME;
		$where = array('abstracttcs_id' => $theId);
		$wpdb->delete($table_name, $where, array('%d'));
		//Now put in the ones that were passed, if there were any
		if (is_array($theRequirements)) {
			$tn = $wpdb->prefix . "badgedb_" . self::ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME;
			foreach ($theRequirements as $r) {
				$wpdb->insert($tn, array('abstracttcs_id' => $theId, 'requirements_id' => $r), array('%d', '%d'));
			}//end loop
		}//end if

	}//end function

		/**
	 * Gets back all the badges.
	 * 
	 * @since	1.0.0
	 */
	public static function get_badges() {
		global $wpdb;
		$table_name = $wpdb->prefix . "badgedb_" . self::BADGES_TABLE_NAME;
		$q = "SELECT * FROM " . $table_name . ";";
		$results = $wpdb->get_results($q, ARRAY_A);
		if (count($results) < 1) {
			//It does something wierd when there are no results, so lets just set it to null if the array is empty.
			//The strange behaviour could also be related to being in WP_DEBUG = true mode and not show up in production.
			return null;
		} else {
			return $results;
		}
	}//end function	

	/**
	 * Adds a new badge to the database
	 * 
	 * @since 1.0.0
	 */
	public static function insert_new_badge($theIdent, $theDesc, $theRequirements, $theBadgeDeps, $fileID) {
		global $wpdb;
		
		$table_name = $wpdb->prefix . "badgedb_" . self::BADGES_TABLE_NAME;
		$theData = array('identifier' => $theIdent, 'description' => $theDesc, 'wpid' => $fileID);
		$theFormat = array('%s', '%s', '%d');
		$inserted = $wpdb->insert($table_name, $theData, $theFormat);
		error_log("Badge insert query: " . $wpdb->last_query);
		
		//We need to create all the relevant records in badges_has_requirements
		//We need the id of the new record
		$newID = $wpdb->insert_id;
		error_log("Requiremens for the new badge: " . count($theRequirements));
		//We need to make sure the record was inserted AND that there is an array of requirements
		if ($inserted != false && is_array($theRequirements)) {
			$tn = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_REQUIREMENTS_TABLE_NAME;
			foreach ($theRequirements as $r) {
				$wpdb->insert($tn, array('badges_id' => $newID, 'requirements_id' => $r), array('%d', '%d'));
			}//end loop
		}//end if


		//now lets add all the badges_has_badges records
		error_log("Badge dependancies: " . count($theBadgeDeps));
		if ($inserted != false && is_array($theBadgeDeps)) {
			$btn = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_BADGES_TABLE_NAME;
			foreach ($theBadgeDeps as $r) {
				$wpdb->insert($btn, array('badges_id' => $newID, 'badges_id_dependency' => $r), array('%d', '%d'));
			}//end loop
		}//end if


	}//end insert_new_badge

	/**
	 * Deletes a badge from the database.
	 * 
	 * @since 1.0.0
	 */
	public static function delete_badge($theID) {
		error_log("Attempting to delete bage with id " . $theID);
		//get the record we're going to delete
		global $wpdb;
		$tn = $wpdb->prefix . "badgedb_" . self::BADGES_TABLE_NAME;
		$q = "SELECT * FROM " . $tn . " WHERE id=" . $theID;
		$records = $wpdb->get_results($q, ARRAY_A);
		//There really should only be one, so lets just pop the last one.
		$r = array_pop($records);
		$theAttachmentId = $r['wpid'];

		//Delete the attachment record
		if ($theAttachmentId != "NULL" && !is_null($theAttachmentId)) {
			wp_delete_attachment($theAttachmentId, true);
		}

		//Delete the requirements recrods
		$where = array('badges_id' => $theID);
		$rtn = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_REQUIREMENTS_TABLE_NAME;
		$wpdb->delete($rtn, $where, array('%d'));

		//Delete the badge prequisite records.
		$where = array('badges_id' => $theID);
		$btn = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_BADGES_TABLE_NAME;
		$wpdb->delete($btn, $where, array('%d'));

		//Delete the record.
		$where = array('id' => $theID);
		$wpdb->delete($tn, $where, array('%d'));
	}

	/**
	 * Modifies an existing badge record
	 * 
	 * @since 1.0.0
	 */
	public static function modify_badge($theID, $theIdent, $theDesc, $theRequirements, $theBadgeDeps, $fileUploaded, $theFileID = -1) {
		global $wpdb;
		//$table_prefix = $wpdb->prefix . "badgedb_";
		$table_name = $wpdb->prefix . "badgedb_" . self::BADGES_TABLE_NAME;

		//We need to handle the update differently if the file was altered.
		if ($fileUploaded) {
			//remove the old attachment
			//If you don't select all from the row in the query you don't get what you're expecting.
			//For example SELCET 'wpid" .... will return a row with a field called wpid with the string value wpid, not the int value you
			// $q = "SELECT * FROM " . $table_name . " WHERE id=" . $theID . ";";
			// $oldRecord = $wpdb->get_row($q, ARRAY_A);
			// error_log($q);
			// error_log("Editing atcs and removing old attachment with post id: " . $oldRecord['wpid']);
			// wp_delete_attachment($oldRecord['wpid'], true);
			// //For whatever reason, you need to call update this way instead of how I did it for 'insert' above.
			// //If you don't you get array to string conversion errors when you try to pass the arrays into the update function.
			// $wpdb->update($table_name, array('identifier' => $theIdent, 'description' => $theDesc, 'name' => $theName, 'version' => $theVersion, 'wpid' => $theFileID), 
			// 	array('id' => $theID), array('%s', '%s', '%s', '%s', '%d'));
		} else {
			//and if the file didn't change we just won't change it.
			$wpdb->update($table_name, array('identifier' => $theIdent, 'description' => $theDesc), array('id' => $theID), 
				array('%s', '%s', '%d'));
		} //end if the file wasn't updated

		//Now we also need to update the requirements associated with this.
		//First just blow away whatever was in there
		$table_name = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_REQUIREMENTS_TABLE_NAME;
		$where = array('badges_id' => $theID);
		$wpdb->delete($table_name, $where, array('%d'));
		//Now put in the ones that were passed, if there were any
		if (is_array($theRequirements)) {
			$tn = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_REQUIREMENTS_TABLE_NAME;
			foreach ($theRequirements as $r) {
				$wpdb->insert($tn, array('badges_id' => $theID, 'requirements_id' => $r), array('%d', '%d'));
			}//end loop
		}//end if

		//And again for badges the record depoends on
		//First just blow away whatever was in there
		$table_name = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_BADGES_TABLE_NAME;
		$where = array('badges_id' => $theID);
		$wpdb->delete($table_name, $where, array('%d'));
		//Now put in the ones that were passed, if there were any
		if (is_array($theBadgeDeps)) {
			$tn = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_BADGES_TABLE_NAME;
			foreach ($theBadgeDeps as $r) {
				$wpdb->insert($tn, array('badges_id' => $theID, 'badges_id_dependency' => $r), array('%d', '%d'));
			}//end loop
		}//end if

	}//end modify_badge


	/**
	 * This returns the HTML for a select list for forms.  You need to say which table you want.
	 * The resulting select will have a name attribute equal to what you pass in.  If you want one
	 * flagged as selected, pass the value of the selected item as the second parameter.
	 * 
	 * Valid options:
	 * 			- catagory:	requirement catagories
	 * 			- 
	 * 
	 * If you pass something not on the list you will get a select list with ERROR as the only
	 * option.
	 * 
	 * I'm doing it this way to control what can get passed into this function.  In the past
	 * I'd done this sort of thing with a general php function, but it feels like with WordPress
	 * it's going to be safer to restrict who's calling the code and how.
	 * 
	 * @since	1.0.0
	 */
	public static function get_form_select($whichTable, $isRequired, $selected = null) {
		global $wpdb;

		//first, define some variables we will set in the if clause
		//for the chosen table.
		$valid = false;
		$table_Name = null;
		$fields = null;
		$valueField = null;
		$labelField = null;
		if (!is_bool($isRequired)) { $isRequired = false; }  #just in case it get passed in wrong

		//Make sure it's something we support
		if ($whichTable == "catagory") {
			$valid = true;
			$table_name = $wpdb->prefix . "badgedb_" . self::REQCATEGORIES_TABLE_NAME;
			$fields = "id, name";
			$valueField = "id";
			$labelField = "name";
			$selectfield = 'id';
		}

		//See if it's not a valid choice, send back something to show there was an error.
		if ($valid == false) {
			return "<select name=\"ERROR\"><option value=\"ERROR\">ERROR</option></select>";
		}//end if it's not valid


		//if now let's get the records we need
		$q = "SELECT " . $fields . " FROM " . $table_name . ";";
		$result = $wpdb->get_results($q, ARRAY_A);

		//Build up the select box
		$selectbox = "<select ";
		if ($isRequired) {
			$selectbox .= "required name=\"" . $whichTable . "\">";
		} else {
			$selectbox .= "name=\"" . $whichTable . "\">";
		}
		//now the options
		foreach ($result as $row) {
			$selectbox .= "<option ";
			if ($selected != null && $selected == $row[$selectfield]) {
				$selectbox .= "selected ";
			}
			$selectbox .= "value=\"" . $row[$valueField] . "\">" . $row[$labelField] . "</option>";
		}//end loop over records
		$selectbox .= "</select>";

		return $selectbox;
	}//end functions

	/**
	 * This returns the HTML for a select list for forms where more than one item can
	 * be selected.  You need to say which table you want the options to come from and
	 * give an array (optional) of the selected values
	 * 
	 * The resulting select will have a name attribute equal to what you pass in.  If you want one
	 * flagged as selected, pass the value of the selected item as the second parameter.
	 * 
	 * Valid options:
	 * 			- atcs-req  (abstract test case has requirements)
	 * 			- badges-req (badges has requirements)
	 * 			- badges-badge (badges has badges)
	 * 
	 * If you pass something not on the list you will get a select list with ERROR as the only
	 * option.
	 * 
	 * The rational for doing it this way is as for the single select.
	 * 
	 * @param	$optionTable	Code for what table to use.  Valid codes:
	 * @param	$formFieldName	What the HTML form element will have for a name
	 * @param	$isRequired		true/false should the select be marked as required for validation
	 * @param	$selectorID		If you need to pre-select options this is the id what will be used for the database query.
	 * 
	 * @return	string containing complete HTML syntax for a multi-select form element.
	 * @since	1.0.0
	 */
	public static function get_form_multi_select($optionTable, $formFieldName, $isRequired, $selectorID = -1) {
		global $wpdb;

		//first, define some variables we will set in the if clause
		//for the chosen table.
		$valid = false;			//just a flag to decide if a valid option was passed for the table
		$table_Name = null;		//the table we will use
		$fields = null;			//the fields that will be retrieved
		$valueField = null;		//the field that will have the option value for the form
		$labelField = null;		//the field that will be used to show the option text to users
		$selectedValues = null;	//which values should be pre-selected.  FOr multi-select this will have to come form a query.
		$optionQuery = null;	//the query that will select the option data
		$selectedQuery = null;	//the query that will get the values that need to be pre-selected.
		$selectedValueField = null; //the field name for the selected elements result.
		if (!is_bool($isRequired)) { $isRequired = false; }  #just in case it get passed in wrong

		//Make sure it's something we support
		if ($optionTable == "atcs-req") {
			$valid = true;
			//The two tables we need for the query
			$ahrTableName = $wpdb->prefix . "badgedb_" . self::ABSTRACTTCS_HAS_REQUIREMENTS_TABLE_NAME;
			$reqTableName = $wpdb->prefix . "badgedb_" . self::REQUIREMENTS_TABLE_NAME;
			//The way I'm building the query the option table name should have all the SQL syntax needed 
			//to get the data, so it can include joins or what have you
			$selected_table_name = "(" . $reqTableName . 
				" JOIN " . $ahrTableName . " ON " . $reqTableName . ".id = " . $ahrTableName . ".requirements_id)";
			$fields = "identifier, requirements_id";
			$selector = "abstracttcs_id";
			$valueField = "id";
			$selectedValueField = "requirements_id";

			$labelField = "identifier";
			$optionQuery = "SELECT id, identifier FROM " . $reqTableName . ";";	
			//make up the selected query if we need to
			if ($selectorID != -1) {
				$selectedQuery = "SELECT requirements_id FROM " . $selected_table_name . " where " . $selector . " = " . $selectorID . ";";
			}
		} elseif ($optionTable == "badges-req") {
			$valid = true;
			//The two tables needed for the query
			$bhrTableName = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_REQUIREMENTS_TABLE_NAME;
			$reqTableName = $wpdb->prefix . "badgedb_" . self::REQUIREMENTS_TABLE_NAME;
			//Now make up the poart of teh query that specifies the data.
			$data_source = "(" . $reqTableName . " JOIN " . $bhrTableName . 
				" ON " . $reqTableName . ".id = " . $bhrTableName . ".requirements_id)";
			$fields = "identifier, requirements_id";
			$selector = "badges_id";
			$valueField = "id";
			$selectedValueField = "requirements_id";

			$labelField = "identifier";
			$optionQuery = "SELECT id, identifier FROM " . $reqTableName . ";";	
			//make up the selected query if we need to
			if ($selectorID != -1) {
				$selectedQuery = "SELECT requirements_id FROM " . $data_source . " where " . $selector . " = " . $selectorID . ";";
			}
		} elseif ($optionTable == "badges-badge") {
			$valid = true;
			//The two tables needed for the query
			$bhbTableName = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_BADGES_TABLE_NAME;
			$badgesTableName = $wpdb->prefix . "badgedb_" . self::BADGES_TABLE_NAME;
			//Now make up the poart of teh query that specifies the data.
			$data_source = "(" . $badgesTableName . " JOIN " . $bhbTableName . 
				" ON " . $badgesTableName . ".id = " . $bhbTableName . ".badges_id_dependency)";
			$fields = "identifier, badges_id_dependency";
			$selector = "badges_id";
			$valueField = "id";
			$selectedValueField = "badges_id_dependency";

			$labelField = "identifier";
			$optionQuery = "SELECT id, identifier FROM " . $badgesTableName . ";";	
			//make up the selected query if we need to
			if ($selectorID != -1) {
				$selectedQuery = "SELECT badges_id_dependency FROM " . $data_source . " where " . $selector . " = " . $selectorID . ";";
			}
		}

		//See if it's not a valid choice, send back something to show there was an error.
		if ($valid == false) {
			return "<select name=\"ERROR\"><option value=\"ERROR\">ERROR</option></select>";
		}//end if it's not valid


		//Now get the whole set of options
		$optionRecords = $wpdb->get_results($optionQuery, ARRAY_A);


		//get the selected ones if we need to
		$selectedRecords = array();
		if (isset($selectedQuery)) {
			$selectedRecords = $wpdb->get_results($selectedQuery, ARRAY_A);
		}

		//Build up the select box
		//Make the opening select tag
		$selectbox = "<select ";
		if ($isRequired) {
			$selectbox .= "required name=\"" . $formFieldName . "[]\" multiple>";
		}
		else {
			$selectbox .= "name=\"" . $formFieldName . "[]\" multiple>";
		}
		//Now add the options
		foreach ($optionRecords as $row) {
			$selectbox .= "<option ";
			$selected = false;
			foreach ($selectedRecords as $s) {
				if($row[$valueField] == $s[$selectedValueField]) { 
					$selected = true; 
					break;
				}
			}
			if ($selected) { $selectbox .= "selected "; }
			$selectbox .= "value=\"" . $row[$valueField] . "\">" . $row[$labelField] . "</option>";
		}//end loop over records
		$selectbox .= "</select>";

		return $selectbox;
	}//end functions

	/**
	 * This function deals with anything that needs doing when the plugin is uninstalled.
	 * @since	1.0.0
	 */
	public static function badgedb_database_uninstall() {
		//Get rid of all the files we uploaded (need to get them from wp_posts) 
		//TODO 
		//  *abstract test cases generate them
		//	*badges generate them
		global $wpdb;
		$atcstable_name = $wpdb->prefix . "badgedb_" . self::ABSTRACT_TEST_CASES_TABLE_NAME;
		$badgeTable_name = $wpdb->prefix . "badgedb_" . self::BADGES_TABLE_NAME;
		$fields = "wpid";
		$q = "SELECT " . $fields . " FROM " . $atcstable_name . " UNION SELECT " . $fields . " FROM " . $badgeTable_name . ";";
		$result = $wpdb->get_results($q, ARRAY_A);

		//Drop all the database tables
		self::drop_tables();

		//we only delete the attachments here as it causes a FK constraint failure if 
		// you try to delte them while the badge database tables still exist.
		foreach ($result as $file ) {
			//This should remove the entry in the wp_posts table and get rid of the file.
			if (!is_null($file['wpid']))
			wp_delete_attachment($file['wpid'], true);
		}//end foreach

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


	/**
	 * This function fills in the new database tables with the base data.
	 * TODO - it would be better to have this stuff get read in from a file
	 * 			that was more easilly updated.
	 */
	private static function insert_base_data() {
		global $wpdb;

		//Requirement catagories
		$table_name = $wpdb->prefix . "badgedb_" . self::REQCATEGORIES_TABLE_NAME;
		$q = "INSERT INTO `" . $table_name . "` (`name`, `description`, `identifier`, `id`) VALUES
		('Best Practice Conformance',	'Requirements related to best practices for distributed simulation',	'BP',	1),
		('Documentation Conformance',	'Requirements for documenting interoperability capabilities',	'DOC',	6),
		('Simulation Object Model Conformance',	'Requirements related to the Conformance of a SuT to the SOM provided in CS\r\n',	'SOM',	8),
		('NETN Requirments',	'Requirements related to NETN FAFD, AMSP-04 Ed A, STANREC 4800',	'NETN',	9),
		('RPR2 Requirements',	'Requirements related to RPR-FOM v2.0',	'RPR2',	10);";
		$wpdb->query($q);

		//Requirements
		$table_name = $wpdb->prefix . "badgedb_" . self::REQUIREMENTS_TABLE_NAME;
		$q = "INSERT INTO `" . $table_name . "` (`id`, `identifier`, `description`, `reqcategories_id`) VALUES
		(1,	'IR-BP-0001',	'SuT shall provide attribute value updates for requested attributes owned by the SuT',	1),
		(2,	'IR-DOC-0001',	'SuT interoperability capabilities shall be documented in a Conformance Statement including a SOM and a FOM with a minimum set of supporting FOM modules',	6),
		(5,	'IR-BP-0002',	'SuT shall create a federation execution before joining, if it does not already exist',	1),
		(6,	'IR-BP-0003',	'SuT shall create or join a federation execution with only those FOM modules that are specified in CS',	1),
		(7,	'IR-SOM-0001',	'SuT CS/SOM shall be valid',	8),
		(8,	'IR-SOM-0002',	'SuT CS/SOM shall be consistent',	8),
		(9,	'IR-SOM-0003',	'SuT shall publish all object classes attributes defined as published in CS/SOM',	8),
		(10,	'IR-SOM-0004',	'SuT shall not publish any object class attribute that is not defined as published in CS/SOM',	8),
		(11,	'IR-SOM-0005',	'SuT shall publish all interaction classes defined as published is CS/SOM',	8),
		(12,	'IR-SOM-0006',	'SuT shall not publish any interaction class that is not defined as published is CS/SOM',	8),
		(13,	'IR-SOM-0007',	'SuT shall subscribe to all object classes attributes defined as subscribed in CS/SOM',	8),
		(14,	'IR-SOM-0008',	'SuT shall not subscribe to any object class attribute that is not defined as subscribed in CS/SOM',	8),
		(15,	'IR-SOM-0009',	'SuT shall subscribe to all interaction classes defined as subscribed in CS/SOM',	8),
		(16,	'IR-SOM-0010',	'SuT shall not subscribe to any interaction class that is not defined as subscribed in CS/SOM',	8),
		(17,	'IR-SOM-0011',	'SuT shall register at least one object instance for each published object class',	8),
		(18,	'IR-SOM-0012',	'SuT shall discover object instances for all object classes with attributes defined as subscribed in CS/SOM.',	8),
		(19,	'IR-SOM-0013',	'SuT shall update attribute values for each published object class attribute',	8),
		(20,	'IR-SOM-0014',	'SuT shall reflect attribute values for each subscribed object class attribute',	8),
		(21,	'IR-SOM-0015',	'SuT shall send at least one interaction for each published interaction class',	8),
		(22,	'IR-SOM-0016',	'SuT shall recieve interactions for each subcribed interaction class',	8),
		(23,	'IR-SOM-0017',	'SuT shall encode all updated attribute values according to CS/SOM',	8),
		(24,	'IR-SOM-0018',	'SuT shall encode all sent interaction class parameters according to CS/SOM',	8),
		(25,	'IR-SOM-0019',	'SuT shall implement/use all HLA services as described as implemented/used in CS/SOM',	8),
		(26,	'IR-SOM-0020',	'SuT shall not implement/use any HLA service that is not described as implemented/used in CS/SOM',	8),
		(27,	'IR-SOM-0027',	'SuT shall be able to decode attribute value updates of all object class attributes defined as subscribed in CS/SOM',	8),
		(28,	'IR-SOM-0028',	'SuT shall be able to decode interaction class parameters for all interaction classes defined as subscribed in CS/SOM',	8),
		(29,	'IR-BP-0004',	'SuT shall be configurable for the following parameters: FederateType, FederateName, FederationName',	1),
		(30,	'IR-NETN-0001',	'SuT shall comply with STANREC 4800, AMSP-04 NETN FAFD Ed A, xx December 20xx',	9),
		(31,	'IR-NETN-0002',	'SuT shall define BaseEntity.AggregateEntity.NETN_Aggregate as published and/or subscribed in CS/SOM',	9),
		(32,	'IR-NETN-0003',	'SuT shall update the following required attributes for NETN_Aggregate object instances registered by SuT: UniqueID, Callsign, Status, Echelon, HigherHeadquarters, AggregateState, Dimensions, EntityIdentifier, EntityType, Spatial.',	9),
		(33,	'IR-NETN-0004',	'SuT updates of NETN_Aggregate instance attributes shall be valid according to STANREC 4800.',	9),
		(34,	'IR-NETN-0005',	'SuT shall assume default values for optional attributes on instances of NETN_Aggregate object class.',	9),
		(35,	'IR-NETN-0006',	'SuT shall not rely on updates of optional attributes on instances of NETN_Aggregate object class.',	9),
		(36,	'IR-NETN-0007',	'SuT shall use pre-defined IDs to generate the same UniqueID for an NETN_Aggregate instance in different Federation Executions.',	9),
		(37,	'IR-NETN-0008',	'SuT shall document in CS if it acts as a NETN TMR Trigger, Requesting and/or Responding federate',	9),
		(38,	'IR-NETN-0009',	'SuT triggering TMR shall define TMR_InitiateTransferModellingResponsibility as published in CS/SOM.',	9),
		(39,	'IR-NETN-0010',	'SuT triggering TMR shall define TMR_OfferTransferModellingResponsibility as subscribed in CS/SOM.',	9),
		(40,	'IR-NETN-0011',	'SuT triggering TMR shall define TMR_TransferResult as subscribed in CS/SOM.',	9),
		(41,	'IR-NETN-0012',	'SuT requesting TMR shall define TMR_InitiateTransferModellingResponsibility as subscribed in CS/SOM',	9),
		(42,	'IR-NETN-0013',	'SuT requesting TMR shall define TMR_OfferTransferModellingResponsibility as published and subscribed in CS/SOM.',	9),
		(43,	'IR-NETN-0014',	'SuT requesting TMR shall define TMR_TransferResult as published in CS/SOM.',	9),
		(44,	'IR-NETN-0015',	'SuT requesting TMR shall define TMR_RequestTransferModellingResponsibility as published in CS/SOM.',	9),
		(45,	'IR-NETN-0016',	'SuT requesting TMR shall define TMR_CancelRequest as published in CS/SOM.',	9),
		(46,	'IR-NETN-0017',	'SuT responding to TMR shall define TMR_RequestTransferModellingResponsibility as subscribed in CS/SOM.',	9),
		(47,	'IR-NETN-0018',	'SuT responding to TMR shall define TMR_OfferTransferModellingResponsibility as published in CS/SOM.',	9),
		(48,	'IR-NETN-0019',	'SuT responding to TMR shall define TMR_CancelRequest as',	9),
		(49,	'IR-NETN-0020',	'SuT triggering TMR shall comply with TMR design pattern for a TMR Triggering federate as documented in NETN FAFD, STANREC 4800.',	9),
		(50,	'IR-NETN-0021',	'SuT requesting TMR shall comply with TMR design pattern for a TMR Requesting federate as documented in NETN FAFD, STANREC 4800.',	9),
		(51,	'IR-NETN-0022',	'SuT responding to TMR shall comply with TMR design pattern for TMR Responding federate as documented in NETN FAFD, STANREC 4800.',	9),
		(52,	'IR-NETN-0023',	'SuT shall respond to a TMR_InitiateTransferModellingResponsibility directed to the SuT with a negative TMR_OfferTransferModellingResponsibility if it is not possible to initiate a transfer of modelling responsibility.',	9),
		(53,	'IR-NETN-0024',	'SuT shall respond to a TMR_InitiateTransferModellingResponsibility directed to the SuT with a positive TMR_OfferTransferModellingResponsibility if it is possible to initiate a transfer of modelling responsibility.',	9),
		(54,	'IR-NETN-0025',	'SuT shall respond to a TMR_InitiateTransferModellingResponsibility directed to the SuT with a TMR_TransferResult.',	9),
		(55,	'IR-NETN-0026',	'SuT shall not respond to a TMR_InitiateTransferModellingResponsibility if it is not directed to the SuT.',	9),
		(56,	'IR-NETN-0027',	'SuT shall respond to a TMR_RequestTransferModellingResponsibility directed to the SuT with a negative TMR_OfferTransferModellingResponsibility if it is not possible to perform a transfer of modelling responsibility.',	9),
		(57,	'IR-NETN-0028',	'SuT shall respond to a TMR_RequestTransferModellingResponsibility directed to the SuT with a positive TMR_OfferTransferModellingResponsibility if it is possible to perform a transfer of modelling responsibility.',	9),
		(58,	'IR-NETN-0029',	'SuT shall not respond to a TMR_RequestTransferModellingResponsibility if it is not directed to the SuT.',	9),
		(59,	'IR-NETN-0030',	'SuT shall, if SuT responds positive to a TMR_RequestTransferModellingResponsibility, use HLA services to perform TMR according to pattern defined in NETN FAFD, STANREC 4800.',	9),
		(61,	'IR-NETN-0031',	'SuT shall cancel or not perform TMR as a response to a TMR_CancelRequest directed to the SuT.',	9),
		(62,	'IR-NETN-0032',	'SuT shall document time-out condition for receiving a TMR_OfferTransferModellingResponsibility corresponding to a TMR_RequestTransferModellingResponsibility sent by the SuT.',	9),
		(63,	'IR-NETN-0033',	'SuT shall send TMR_CancelRequest after TMR_RequestTransferModellingResponsibility sent by SuT has timed-out.',	9),
		(64,	'IR-NETN-0034',	'SuT acting as a MRM Service Provider shall define interaction class MRM_AggregationRequest as published in CS/SOM.',	9),
		(65,	'IR-NETN-0035',	'SuT acting as a MRM Service Provider shall define interaction class MRM_AggregationResponse as subscribed in CS/SOM.',	9),
		(66,	'IR-NETN-0036',	'SuT acting as a MRM Service Provider shall define interaction class MRM_ActionComplete as published in CS/SOM.',	9),
		(67,	'IR-NETN-0037',	'SuT MRM Service Provider shall respond to interaction MRM_Trigger with interaction MRM_TriggerResponse.',	9),
		(68,	'IR-NETN-0038',	'SuT MRM Service Provider shall send interaction MRM_ActionComplete, positive result when MRM actions are completed.',	9),
		(69,	'IR-NETN-0040',	'SuT MRM Aggregate Federate shall comply with MRM design pattern for a MRM Service Provider federate as documented in NETN FAFD, STANREC 4800.',	9),
		(70,	'IR-NETN-0041',	'SuT acting as a Aggregate Federate shall define object class NETN_Aggregate as published and subscribed in CS/SOM.',	9),
		(71,	'IR-NETN-0042',	'SuT acting as a Aggregate Federate shall define interaction class MRM_DisaggregationRequest as subscribed in CS/SOM.',	9),
		(72,	'IR-NETN-0043',	'SuT acting as a Aggregate Federate shall define interaction class MRM_DisaggregationResponse as published in CS/SOM.',	9),
		(73,	'IR-NETN-0044',	'SuT acting as a Aggregate Federate shall define interaction class MRM_AggregationRequest as subscribed in CS/SOM.',	9),
		(74,	'IR-NETN-0045',	'SuT acting as a Aggregate Federate shall define interaction class MRM_AggregationResponse as published in CS/SOM.',	9),
		(75,	'IR-NETN-0046',	'SuT acting as a Aggregate Federate shall define interaction class MRM_ActionComplete as subscribed in CS/SOM.',	9),
		(76,	'IR-NETN-0047',	'SuT Aggregate Federate shall respond to interaction MRM_DisaggregationRequest with interaction MRM_DisaggregationResponse.',	9),
		(77,	'IR-NETN-0048',	'SuT Aggregate Federate shall respond to interaction MRM_AggregationRequest with interaction MRM_AggregationResponse.',	9),
		(78,	'IR-NETN-0049',	'SuT MRM Higher Resolution Federate shall comply with MRM design pattern for a MRM Service Provider federate as documented in NETN FAFD, STANREC 4800',	9),
		(79,	'IR-NETN-0050',	'SuT acting as a Higher Resolution Federate shall define the NETN-Physical leaf object classes as published and subscribed in CS/SOM',	9),
		(80,	'IR-NETN-0051',	'SuT acting as a Higher Resolution Federate shall define interaction class MRM_DisaggregationRequest as subscribed in CS/SOM.',	9),
		(81,	'IR-NETN-0052',	'SuT acting as a Higher Resolution Federate shall define interaction class MRM_DisaggregationResponse as published in CS/SOM.',	9),
		(82,	'IR-NETN-0053',	'SuT acting as a Higher Resolution Federate shall define interaction class MRM_AggregationRequest as subscribed in CS/SOM.',	9),
		(83,	'IR-NETN-0054',	'SuT acting as a Higher Resolution Federate shall define interaction class MRM_AggregationResponse as published in CS/SOM.',	9),
		(84,	'IR-NETN-0055',	'SuT acting as a Higher Resolution Federate shall define interaction class MRM_ActionComplete as subscribed in CS/SOM.',	9),
		(85,	'IR-NETN-0056',	'SuT Higher Resolution Federate shall respond to interaction MRM_DisaggregationRequest with interaction MRM_DisaggregationResponse.',	9),
		(86,	'IR-NETN-0057',	'SuT Higher Resolution Federate shall respond to interaction MRM_AggregationRequest with interaction MRM_AggregationResponse.',	9),
		(87,	'IR-NETN-0058',	'SuT MRM Service Provider shall, if SuT receives positive MRM_DisaggregationResponse, use HLA services and TMR interactions to perform MRM disaggregation according to pattern defined in NETN FAFD, STANREC 4800.',	9),
		(88,	'IR-NETN-0059',	'SuT MRM Service Provider shall, if SuT receives positive MRM_AggregationResponse, use HLA services and TMR interactions to perform MRM aggregation according to pattern defined in NETN FAFD, STANREC 4800.',	9),
		(89,	'IR-NETN-0060',	'SuT Aggregate or Higher Resolution Federate shall, if SuT responds positive to a MRM_DisaggregationRequest, use HLA services and TMR interactions to perform MRM disaggregation according to pattern defined in NETN FAFD, STANREC 4800.',	9),
		(90,	'IR-NETN-0061',	'SuT Aggregate or Higher Resolution Federate shall, if SuT responds positive to a MRM_AggregationRequest, use HLA services and TMR interactions to perform MRM aggregation according to pattern defined in NETN FAFD, STANREC 4800.',	9),
		(91,	'IR-NETN-0062',	'SuT Aggregate or Higher Resolution Federate shall, if SuT responds positive to a MRM_AggregationRequest, use HLA services and TMR interactions to perform MRM aggregation according to pattern defined in NETN FAFD, STANREC 4800.',	9),
		(92,	'IR-NETN-0063',	'SuT shall define BaseEntity.AggregateEntity.NETN_Aggregate or a subclass and/or a NETN subclass of BaseEntity.PhysicalEntity as published and/or subscribed in CS/SOM.',	9),
		(93,	'IR-NETN-0064',	'SuT defined as producer in CS/SOM shall for LBMLMessage.LBMLTask leaf interactions provide the following required parameters for the LBMLMessage.LBMLTask leaf classes: Task, Taskee, Tasker, TaskType.',	9),
		(94,	'IR-NETN-0065',	'SuT defined as producer in CS/SOM shall for LBMLMessage.LBMLTask leaf interactions provide all required parameters defined in the LBMLMessage.LBMLTask leaf interaction class.',	9),
		(95,	'IR-NETN-0066',	'SuT shall define NETN LBMLMessage.LBMLTask.MoveToLocation and LBMLMessage.LBMLTask.MoveToUnit as published and/or subscribed in CS/SOM.',	9),
		(96,	'IR-NETN-0067',	'SuT shall define at least one leaf interaction class of NETN LBMLMessage.LBMLTaskManagement (CancelAllTasks, CancelSpecifiedTasks) as published and/or subscribed in CS/SOM.',	9),
		(97,	'IR-NETN-0068',	'SuT shall define NETN LBMLReport.StatusReport.TaskStatusReport as subscribed in CS/SOM if SuT has defined leaf classes of LBMLTas as published in CS/SOM',	9),
		(98,	'IR-NETN-0069',	'SuT shall define NETN LBMLReport.StatusReport.TaskStatusReport as published in CS/SOM if SuT has defined leaf classes of LBMLTas as subscribed in CS/SOM',	9),
		(99,	'IR-NETN-0070',	'SuT shall define NETN LBMLMessage.LBMLTask.FireAtLocation and LBMLMessage.LBMLTask.FireAtUnit or subclasses of these as published and/or subscribed in CS/SOM.',	9),
		(100,	'IR-NETN-0071',	'SuT defined as consumer in CS/SOM shall for NETN LBMLMessage.LBMLTask.FireAtLocation and LBMLMessage.LBMLTask.FireIndirectWM fire at the specified location.',	9),
		(101,	'IR-NETN-0072',	'SuT defined as consumer in CS/SOM shall for NETN LBMLMessage.LBMLTask.FireAtUnit and LBMLMessage.LBMLTask.FireDirectWM fire at the specified unit.',	9),
		(102,	'IR-NETN-0074',	'SuT defined as a consumer in CS/SOM shall clear the tasks at the entity that is specified in the LBMLMessage.LBMLTaskManagement.CancelSpecifiedTasks when it is received.',	9),
		(103,	'IR-NETN-0075',	'SuT defined as consumer in CS/SOM shall for NETN LBMLMessage.LBMLTask.MoveToLocation and LBMLMessage.LBMLTask.MoveToUnit move the specified unit to the specified location and if the route is specified use it.',	9),
		(104,	'IR-NETN-0076',	'SuT defined as a producer of NETN LBMLReport.StatusReport.TaskStatusReport in CS/SOM shall respond to a leaf class of LBMLMessage.LBMLTask with a status report of the task (Accepted/Refused).',	9),
		(105,	'IR-NETN-0077',	'SuT defined as a producer of NETN LBMLReport.StatusReport.TaskStatusReport in CS/SOM shall update the status of the task (Aborted/Completed) when the status change.',	9),
		(106,	'IR-NETN-0078',	'SuT shall define LBMLReport.SpotReport.ActivitySpotReport.CurrentActivitySpotReport as published and/or subscribed in CS/SOM.',	9),
		(107,	'IR-NETN-0079',	'SuT defined as a provider in CS/SOM shall define BaseEntity.AggregateEntity.NETN_Aggregate or a subclass and/or a NETN subclass of BaseEntity.PhysicalEntity as subscribed in CS/SOM.',	9),
		(108,	'IR-NETN-0080',	'SuT defined as a provider in SOM/CS shall send LBMLReport.SpotReport.ActivitySpotReport.CurrentActivitySpotReport about spotted enemies, neutral, or unknown units (in realation to the observer) when these are able to observ (determined by the SuT observing model).',	9),
		(109,	'IR-NETN-0081',	'SuT shall define LBMLReport.StatusReport.ActivityStatusReport.CurrentActivityStatusReport as published and/or subscribed in CS/SOM.',	9),
		(110,	'IR-NETN-0082',	'SuT defined as a provider in CS/SOM shall define BaseEntity.AggregateEntity.NETN_Aggregate or a subclass and/or a NETN subclass of BaseEntity.PhysicalEntity as published in CS/SOM.',	9),
		(111,	'IR-NETN-0083',	'SuT defined as a provider in SOM/CS shall send LBMLReport.StatusReport.ActivityStatusReport.CurrentActivityStatusReport from friendly units about their own (perceived) state.',	9),
		(112,	'IR-NETN-0084',	'SuT defined as a consumer in SOM/CS shall receive LBMLReport.StatusReport.ActivityStatusReport.CurrentActivityStatusReport for friendly units about their (perceived) state and base its low level BML tasks on this perceived truth data of blue units instead of RPR ground truth data.',	9),
		(113,	'IR-NETN-0085',	'SuT defined as a consumer in SOM/CS shall receive LBMLReport.SpotReport.ActivitySpotReport.CurrentActivitySpotReport for spotted enemy, neutral, or unknown unit andl base its low level BML tasks on this perceived truth data on non friendly / unknown units instead of RPR ground truth data.',	9),
		(114,	'IR-RPR2-0001',	'SuT shall comply with SISO-STD-001-2015, Standard for Guidance, Rationale, and Interoperability Modalities for the Real-time Platform Reference Federation Object Model, Version 2.0, 10 August 2015',	10),
		(115,	'IR-RPR2-0002',	'SuT shall define BaseEntity.AggregateEntity as published or define a subclass of BaseEntity.AggregateEntity as published and/or define BaseEntity.AggregateEntity as subscribed in CS/SOM.',	10),
		(116,	'IR-RPR2-0003',	'SuT shall update the following required attributes for AggregateEntity object instances registered by SuT: AggregateState, Dimensions, EntityIdentifier, EntityType, Spatial.',	10),
		(117,	'IR-RPR2-0004',	'SuT shall assume default values for optional attributes on instances of AggregateEntity object class.',	10),
		(118,	'IR-RPR2-0006',	'SuT shall not rely on updates of optional attributes on instances of AggregateEntity object class.',	10),
		(119,	'IR-RPR2-0007',	'SuT shall be configurable for the following parameters: SiteID, ApplicationID.',	10),
		(120,	'IR-RPR2-0008',	'SuT shall define at least one leaf object class of BaseEntity.PhysicalEntity as published and/or subscribed in CS/SOM.',	10),
		(121,	'IR-RPR2-0009',	'SuT shall in CS specify the use of Articulated Parts for all published and subscribedBaseEntity.PhysicalEntity and subclasses.',	10),
		(122,	'IR-RPR2-0010',	'SuT shall in CS specify the use of Dead-Reckoning algorithms for all published and subscribed BaseEntity.PhysicalEntity and subclasses.',	10),
		(123,	'IR-RPR2-0011',	'SuT shall update the following required attributes for PhysicalEntity subclass object instances registered by SuT: EntityIdentifier, EntityType, Spatial.',	10),
		(124,	'IR-RPR2-0012',	'SuT shall not update non-applicable PhysicalEntity Attributes as specified in Domain Appropriateness table in SISO-STD-001-2015.',	10),
		(125,	'IR-RPR2-0013',	'SuT updates of instance attributes shall, for BaseEntity.PhysicalEntity and subclasses, be valid according to SISO-STD-001-2015 and SISO-STD-001.1-2015.',	10),
		(126,	'IR-RPR2-0014',	'SuT updates of instance attribute Spatial shall, for BaseEntity.PhysicalEntity and subclasses, include valid Dead-Reckoning parameters for supported algorithms as specified in CS.',	10),
		(127,	'IR-RPR2-0015',	'SuT shall assume default values for optional attributes on instances of BaseEntity.PhysicalEntity and subclasses according to SISO-STD-001-2015.',	10),
		(128,	'IR-RPR2-0016',	'SuT shall not rely on updates of optional attributes on instances of BaseEntity.PhysicalEntity and subclasses.',	10),
		(129,	'IR-RPR2-0017',	'SuT shall define BaseEntity.PhysicalEntity.Munition or at least one leaf object class as published or subscribed in CS/FOM when tracked munitions is used (e.g. torpedoes, missiles, etc.)',	10),
		(130,	'IR-RPR2-0018',	'SuT shall define interaction class WeaponFire or at least one leaf class as published and/or subscribed in CS/SOM.',	10),
		(131,	'IR-RPR2-0019',	'SuT shall provide the following required parameters for the WeaponFire interaction: EventIdentifier, FiringLocation, FiringObjectIdentifier, FuseType, InitialVelocityVector, MunitionType, WarheadType.',	10),
		(132,	'IR-RPR2-0020',	'SuT shall when tracked munition is used provide the WeaponFire parameter MunitionObjectIdentifier.',	10),
		(133,	'IR-RPR2-0021',	'SuT shall provide parameters for sent interactions of WeaponFire and subclasses according to SISO-STD-001-2015 and SISO-STD-001.1-2015.',	10),
		(134,	'IR-RPR2-0022',	'SuT shall assume default values for optional parameters at interactions of WeaponFire and subclasses according to SISO-STD-001-2015.',	10),
		(135,	'IR-RPR2-0023',	'SuT shall not rely on receiving optional parameters on interactions of WeaponFire and subclasses.',	10),
		(136,	'IR-RPR2-0024',	'SuT shall define interaction class MunitionDetonation or at least one leaf class as published and/or subscribed in CS/SOM.',	10),
		(137,	'IR-RPR2-0025',	'SuT shall provide the following required parameters for the MunitionDetonation interaction: DetonationLocation, EventIdentifier, FuseType, MunitionType, WarheadType.',	10),
		(138,	'IR-RPR2-0026',	'SuT shall when munition type is not a mine provide the following required parameters for the MunitionDetonation interaction if: FiringObjectIdentifier, FinalVelocityVector.',	10),
		(139,	'IR-RPR2-0027',	'SuT shall when tracked munition is used provide the MunitionDetonation parameter MunitionObjectIdentifier.',	10),
		(140,	'IR-RPR2-0028',	'SuT shall when the parameter TargetObjectIdentifier at MunitionDetonation is provided, provide the parameter RelativeDetonationLocation.',	10),
		(141,	'IR-RPR2-0029',	'SuT shall provide parameters for sent interactions of MunitionDetonation and subclasses according to SISO-STD-001-2015 and SISO-STD-001.1-2015.',	10),
		(142,	'IR-RPR2-0030',	'SuT shall assume default values for optional parameters on interactions of MunitionDetonation and subclasses according to SISO-STD-001-2015.',	10),
		(143,	'IR-RPR2-0031',	'SuT shall not rely on receiving optional parameters on interactions of MunitionDetonation and subclasses.',	10),
		(144,	'IR-RPR2-0032',	'SuT shall when munition type was not a mine provide the same value on parameter EventIdentifier at the WeaponFire and the corresponding MunitionDetonation interaction.',	10),
		(145,	'IR-RPR2-0033',	'SuT shall when receiving a MunitionDetonation interaction with a specified target (Direct Fire) and SuT has the modelling responsibility for the damage assessment at that entity, update the BaseEntity.PhysicalEntity attribute DamageState with an appropriate value.',	10),
		(146,	'IR-RPR2-0034',	'SuT shall when receiving a MunitionDetonation without a specified target (Indirect Fire) but the same location as an entity and SuT has the modelling responsibility for the damage assessment at that entity, update the BaseEntity.PhysicalEntity attribute DamageState with an appropriate value.',	10),
		(147,	'IR-RPR2-0005',	'SuT shall assume default values for optional attributes on instances of AggregateEntity object class.',	10),
		(149,	'IR-NETN-0073',	'SuT defined as a consumer in CS/SOM shall clear all tasks at the entity when an LBMLMessage.LBMLTaskManagement.CancelAllTasks is received',	9),
		(150,	'HLA-Verification-2016',	'This test case is equivalent to the FCTT_NG configuration verification step.',	6);";
		$wpdb->query($q);

		$table_name = $wpdb->prefix . "badgedb_" . self::BADGES_TABLE_NAME;
		$q = "INSERT INTO `" . $table_name . "` (`id`, `description`, `wpid`, `identifier`) VALUES
		(20,	'Basic CS/SOM and Best Practices compliance',	NULL,	'HLA-BASE-2016'),
		(21,	'NETN-FOM v2.0 Aggregate FOM Module',	NULL,	'NETN-AGG-2016'),
		(22,	'NETN FOM v2.0 Physical FOM Module',	NULL,	'NETN-ENTITY-2016'),
		(23,	'NETN FOM v2.0 MRM FOM Module',	NULL,	'NETN-MRM-2016'),
		(24,	'Basic support for NETN TMR pattern (AMSP-04 Ed A). SuT is able to respond to TMR requests.',	NULL,	'NETN-TMR-2016'),
		(25,	'RPR-FOM v2.0 Aggregate FOM Module',	NULL,	'RPR-AGG-2016'),
		(26,	'RPR-FOM v2.0 Physical FOM Module support. GRIM compliance wrt. Platforms, Lifeforms etc. representation of required attributes.',	NULL,	'RPR-ENTITY-2016'),
		(30,	'RPR-Warfare v2.0 FOM Module support.',	NULL,	'RPR-WARFARE-2016'),
		(31,	'NETN-FOM v2.0 LBML FOM Module',	NULL,	'NETN-LBML-TASK-2016'),
		(32,	'NETN-FOM v2.0 LBML FOM Module',	NULL,	'NETN-LBML-INTREP-2016'),
		(33,	'NETN-FOM v2.0 LBML FOM Module',	NULL,	'NETN-LBML-OWNSITREP-2016'),
		(34,	'Test',	NULL,	'ATC-Test2');";
		$wpdb->query($q);

		//No default data to insert yet.
		$table_name = $wpdb->prefix . "badgedb_" . self::ABSTRACT_TEST_CASES_TABLE_NAME;

		//No default data to insert yet
		$table_name = $wpdb->prefix . "badgedb_" . self::BADGES_HAS_BADGES_TABLE_NAME;

	}//end function insert base data

}//end class
