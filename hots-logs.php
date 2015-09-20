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
* The installation function
*/
function hots_logs_install() {
	$the_time = current_time('unix');
	add_option('hots_logs_last_scrape', $the_time, '', 'yes');	
	hots_logs_make_db();
	
}


/* The uninstall function */
function hots_logs_uninstall() {
	delete_option('hots_logs_last_scrape');
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
		player_id int(9) NOT NULL,
		name tinytext NOT NULL,
		hl_mmr int(5) NOT NULL,
		qm_mmr int(5) NOT NULL,
		comb_hero_level int(5) NOT NULL,
		total_games_played int (9) NOT NULL,
		UNIQUE KEY player_id (player_id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	add_option( 'jal_db_version', $jal_db_version );
}

function getData(){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin"; 
	$result = $wpdb->get_results( "SELECT * FROM $table_name "); 
	return $result;	 
}

function input($playerArray){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";

	$sql = "INSERT INTO $table_name(player_id, name, hl_mmr, qm_mmr, comb_hero_level, total_games_played) 
			VALUES(%d,%s,%s,%s,%s,%s) 
			ON DUPLICATE KEY UPDATE(hl_mmr = %s, qm_mmr = %s, comb_hero_level = %s, total_games_played = %s)";

	$sql = $wpdb->prepare(
		$playerArray['pid'],
		$playerArray['name'],
		$playerArray['heroLeague'],
		$playerArray['quickMatch'],
		$playerArray['combLevel'],
		$playerArray['totalGames']
		);
		
	$wpdb->query($sql);
}	
// Warning: mysql_num_rows() expects parameter 1 to be resource, null given in /home/vooders/public_html/wp-content/plugins/hots-logs/hots-logs.php on line 81

function insert_player($playerArray){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";
	$wpdb->replace(
		$table_name,
		array(
			'player_id' => $playerArray['pid'], 
			'name' => $playerArray['name'], 
			'hl_mmr' => $playerArray['heroLeague'], 
			'qm_mmr' => $playerArray['quickMatch'],
			'comb_hero_level' => $playerArray['combLevel'],	
			'total_games_played' => $playerArray['totalGames']
		),
		array (
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		)
	);	
}

function update_hotslogs_data(){
	$last_scrape = get_option('hots_logs_last_scrape');
	if ($last_scrape != false){
		
	}
	
}

function delete_player($pid){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";
	$wpdb->delete($table_name, array('player_id' => $pid));
}


?>