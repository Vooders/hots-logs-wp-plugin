<?php
/*
Plugin Name: Hots Logs - Leaderboards
Plugin URI: http://vooders.com/
Description: A simple plugin to compare Hots Logs player data.
Version: 0.1
Author: Vooders
Author URI: http://vooders.com
License: GPL
*/

include('hots-options.php'); 	// Load the admin page code
include('widgets/hl-leaderboard.php');		// Load the hero league widget code
include('widgets/qm-leaderboard.php');		// Load the quick mach widget code
include('widgets/hots_logs_all_data_widget.php');
include_once('scraper/scraper.php');			// Load the scraper
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Runs when plugin is activated */
register_activation_hook( __FILE__, 'hots_logs_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'hots_logs_uninstall' );

add_action('wp_loaded', 'update_hotslogs_data');

/*
* The installation function
*/
function hots_logs_install() {
	add_option('hots_logs_last_scrape', '0', '', 'yes');	
	hots_logs_make_db();
}

/* The uninstall function */
function hots_logs_uninstall() {
	delete_option('hots_logs_last_scrape');
	global $wpdb;
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
		hl_image_src VARCHAR(2083) NOT NULL,
		qm_image_src VARCHAR(2083) NOT NULL,
		UNIQUE KEY player_id (player_id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version );
}

/*
* Returns the player data table as an array
*/
function getData(){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin"; 
	$result = $wpdb->get_results( "SELECT * FROM $table_name "); 
	return $result;	 
}

/*
* Inserts a new player into the database
* If player allready exists
* Updates the player information
*/
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
			'total_games_played' => $playerArray['totalGames'],
			'hl_image_src' => $playerArray['hl_image'],
			'qm_image_src' => $playerArray['qm_image']
		),
		array (
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		)
	);	
}

/*
* Updates the hotslogs.com data for all players in the database
* Slows down page load when run!
* Limited to 1 run every 60 min
*/
function update_hotslogs_data(){
	$last_scrape = get_option('hots_logs_last_scrape');
	if ((current_time('timestamp')-$last_scrape) >= 3600 ){ 
		global $wpdb;
		$table_name = $wpdb->prefix . "hots_logs_plugin";	
		$pids = $wpdb->get_col("SELECT player_id FROM $table_name");
		foreach ($pids as $pid){
			insert_player(scrape($pid));
		}
		$last_scrape = current_time('timestamp');
		update_option('hots_logs_last_scrape', $last_scrape);
	}	
}

/*
* Deletes a player from the database
*/
function delete_player($pid){
	global $wpdb;
	$table_name = $wpdb->prefix . "hots_logs_plugin";
	$wpdb->delete($table_name, array('player_id' => $pid));
}

/*
* Dev Function!
* Logs text to a file (log.txt)
*/
function logThis($textString){
	$date = date_create();									// Get the date/time for the timestamp
	$stamp = date_format($date, '[d-m][H:i:s] ');			// Format it 
	$file = fopen("log.txt","a") or die ('fopen failed'); 	// Open the log.txt file
	fwrite($file, $stamp . $textString . PHP_EOL);			// Add the line to the end of the file
	fclose($file) or die ('fclose failed');					// Close the file
}
?>