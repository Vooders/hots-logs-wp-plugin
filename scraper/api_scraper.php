<?php
//error_reporting(E_ALL); ini_set('display_errors',1);
//include('curl.php');

function add_pid($pid){
	$api_url = 'https://www.hotslogs.com/API/Players/';
	$u = $api_url . $pid;
	return scrape($u);
}

function add_btag($tag, $reg){
	$api_url = 'https://www.hotslogs.com/API/Players/';
	$battle_tag = implode('_', explode('#', $tag));
	$u = $api_url . $reg . '/' . $battle_tag;
	return scrape($u);
}

function scrape($url){
	
	$img_url = '//d1i1jxrdh2kvwy.cloudfront.net/Images/Leagues/';
	
	$player_data = array(
			'name' => null,
			'pid' => '0',
			'heroLeague' => '0',
			'quickMatch' => '0',
			'hl_image' => '0',
			'qm_image' => '0'
		);
	
	$page = curl($url);	
	
	$player_data['name'] = scrape_between($page, '"Name":"', '",');
	$player_data['pid'] = scrape_between($page, '"PlayerID":', ',');
	
	$game_modes = scrape_between($page, '[', ']');
	$game_modes = explode('},{', $game_modes);
	foreach($game_modes as $gm){
		$gm = $gm . '}';
		$mode = scrape_between($gm, '"GameMode":"', '",');
		$league = scrape_between($gm, '"LeagueID":', ',');
		$mmr = scrape_between($gm, '"CurrentMMR":', '}');
		if ($mode == 'QuickMatch'){
			$player_data['quickMatch'] = $mmr;
			$player_data['qm_image'] = $img_url . $league . '.png';
		}
		elseif ($mode == 'HeroLeague'){
			$player_data['heroLeague'] = $mmr;
			$player_data['hl_image'] = $img_url . $league . '.png';
		}
	}
	
	if ($player_data['name']!=null)
		return $player_data;
	else
		return false;
}	

// Defining the basic scraping function
    function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }
	
	function get_all($regex){
		preg_match_all($regex, $result, $parts);
		$links = $parts[1];
		return $start.$links.$end;
	}
	
	 // Defining the basic cURL function
    function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
			CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );
         
        $ch = curl_init();  // Initialising cURL 
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL 
        return $data;   // Returning the data from the function 
    }
	
	function get($url, $params=array()) {	
		$url = $url.'?'.http_build_query($params, '', '&');	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
		$response = curl_exec($ch);
		
		curl_close($ch);
//		echo var_dump($response);
		return $response;
	}
?>