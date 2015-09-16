<?php
/*
Plugin Name: Hots Logs - Comparer
Plugin URI: http://vooders.com/
Description: A simple plugin to compare Hots Logs player data.
Version: 0.1
Author: Vooders
Author URI: http://vooders.com
License: GPL
*/

include('hots-options.php'); 	// Load the admin page code
include('widgets/hl-leaderboard.php');		// Load the widget code
include('widgets/qm-leaderboard.php');		// Load the widget code
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Runs when plugin is activated */
register_activation_hook( __FILE__, 'hots_logs_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'hots_logs_uninstall' );
/*
* The installation function0
*/
function hots_logs_install() {
	hots_logs_make_db();
	hots_logs_test_db();	
}

/* The uninstall function */
function hots_logs_uninstall() {
	global $wpdb;	//required global declaration of WP variable

	$table_name = $wpdb->prefix . "hots_logs_plugin";
	$sql = "DROP TABLE ". $table_name;

	$wpdb->query($sql);
}
/*
* Creates the database table to store our player data 
*/
global $jal_db_version;
$jal_db_version = '1.0';

function hots_logs_make_db(){
	global $wpdb;
	
	$table_name = $wpdb->prefix . "hots_logs_plugin"; 
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name tinytext NOT NULL,
	  player_id int(7) NOT NULL,
	  hl_mmr int(5) NOT NULL,
	  qm_mmr int(5) NOT NULL,
	  hl_games int(6) NOT NULL,
	  qm_games int(6) NOT NULL,
	  comb_hero_level int(5) NOT NULL,
	  total_games_played int (6) NOT NULL,
	  UNIQUE KEY id (id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	add_option( 'jal_db_version', $jal_db_version );
}

function getData(){
	global $wpdb;
	/* wpdb class should not be called directly.global $wpdb variable is an instantiation of the class already set up to talk to the WordPress database */ 
	$table_name = $wpdb->prefix . "hots_logs_plugin"; 
	$result = $wpdb->get_results( "SELECT * FROM $table_name "); /*mulitple row results can be pulled from the database with get_results function and outputs an object which is stored in $result */
	//echo "<pre>"; print_r($result); echo "</pre>";
	/* If you require you may print and view the contents of $result object */
	return $result;	 
}

function hots_logs_test_db(){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";
	
	$test_name = 'Vooders';
	$test_id = 1839756;
	$test_hl_mmr = 1717;
	$test_qm_mmr = 2054;
	$test_hl_games = 15;
	$test_qm_games = 300;
	$test_comb_hero_level = 143;
	$test_total_games = 315;
	
	$wpdb->insert(
		$table_name,
		array(
				'name' => $test_name, 'player_id' => $test_id, 'hl_mmr' => $test_hl_mmr, 'qm_mmr' => $test_qm_mmr,
				'hl_games' => $test_hl_games, 'qm_games' => $test_qm_games, 'comb_hero_level' => $test_comb_hero_level,
				'total_games_played' => $test_total_games,
		)
	);
	
	$test_name = 'Moz';
	$test_id = 676397;
	$test_hl_mmr = 1153;
	$test_qm_mmr = 1534;
	$test_hl_games = 25;
	$test_qm_games = 430;
	$test_comb_hero_level = 225;
	$test_total_games = 455;
	
	$wpdb->insert(
		$table_name,
		array(
				'name' => $test_name, 'player_id' => $test_id, 'hl_mmr' => $test_hl_mmr, 'qm_mmr' => $test_qm_mmr,
				'hl_games' => $test_hl_games, 'qm_games' => $test_qm_games, 'comb_hero_level' => $test_comb_hero_level,
				'total_games_played' => $test_total_games,
		)
	);
	
	$test_name = 'Edgey';
	$test_id = 2417768;
	$test_hl_mmr = 0;
	$test_qm_mmr = 1860;
	$test_hl_games = 0;
	$test_qm_games = 104;
	$test_comb_hero_level = 52;
	$test_total_games = 104;
	
	$wpdb->insert(
		$table_name,
		array(
				'name' => $test_name, 'player_id' => $test_id, 'hl_mmr' => $test_hl_mmr, 'qm_mmr' => $test_qm_mmr,
				'hl_games' => $test_hl_games, 'qm_games' => $test_qm_games, 'comb_hero_level' => $test_comb_hero_level,
				'total_games_played' => $test_total_games,
		)
	);
}
?>