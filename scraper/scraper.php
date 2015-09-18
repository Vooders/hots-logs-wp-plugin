<?php
error_reporting(E_ALL); ini_set('display_errors',1);
include_once('curl.php');

function scrape($p){
	$url = 'https://www.hotslogs.com/Player/Profile';
	$pid = $p;
	$player_data = array(
					'name' => '',
					'pid' => '0',
					'heroLeague' => '0',
					'quickMatch' => '0',
					'combLevel' => '0',
					'totalGames' => '0'
		);
	
	$player_data['pid'] = $pid;
	$doc = new DOMDocument();
	$page = get($url, array('PlayerID'=>$pid));
	
	$get_name = scrape_between($page, '<h1 class="section-title">', '</h1>');
	$bits = explode(': ', $get_name);
	$player_data['name'] = $bits[1];
	
	$table = scrape_between($page, '<table class="table table-striped tableGeneralInformation">', '</table>');
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML($table);
	libxml_clear_errors();
	$xpath = new DOMXpath($dom);
	
	$data = array();
	$table_rows = $xpath->query('//tr');
	foreach($table_rows as $row => $tr) {
		foreach($tr->childNodes as $child) {
			$data[$row][] = trim($child->nodeValue);
		}
		$data[$row] = array_values(array_filter($data[$row]));
	}

	foreach($data as $d){
		if ($d[0] == 'Hero League'){
			$bits =  explode(' ', $d[1]);
			$mmr = explode(')', end($bits));
			$player_data['heroLeague'] =  $mmr[0];
		} 
		else if ($d[0] == 'Quick Match'){
			$bits =  explode(' ', $d[1]);
			$mmr = explode(')', end($bits));
			$player_data['quickMatch'] =  $mmr[0];
		} 
		else if ($d[0] == 'Combined Hero Level'){
			$player_data['combLevel'] = $d[1];
		} 
		else if ($d[0] == 'Total Games Played'){
			$player_data['totalGames'] = $d[1];
		}
	}
	input($player_data);
}	
?>