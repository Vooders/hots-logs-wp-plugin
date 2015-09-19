<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( is_admin() ){ // Checks user is an admin
	/* Call the html code */
	add_action('admin_menu', 'hots_logs_admin_menu');
	
	function hots_logs_admin_menu() {
		add_options_page('Hots Logs', 'Hots Logs', 'administrator',
		'hots_logs', 'hots_logs_html_page');
	}
	
	if(isset($_POST['submit'])){
		include_once('scraper/scraper.php');
		$valid_player = scrape($_POST['player_id']);
		if ($valid_player != false){
			insert_player($valid_player);	
			add_action( 'admin_notices', 'player_added_notice' );
		} else {
			add_action( 'admin_notices', 'error_notice' ); 
		}
	}
}

function hots_logs_html_page() {
?>
<div>
<h1>Hots Logs Options</h1>
<p> Add your friends to create your own leaderboards from data scraped from hotslogs.com</p>
<h2>Add a Player</h2>
<p>Enter the HOTS Logs player IDs of the players you want to track.</p>
<form method='post' action=''>
<?php 
	settings_fields('hots_logs'); 
	do_settings_sections('hots_logs');
?>
<table width='400px'>
    <tr valign='top'>
        <th width='20%' scope='row'>Player ID</th>
        <td width='60%'>
            <input name='player_id' type='text' id='player_data' value='' />
        </td>
        <td width="20%">
        	<?php submit_button('Add'); ?>
        </td>
    </tr>   
</table>
<input type='hidden' name='action' value='update' />
<input type='hidden' name='page_options' value='player_id' />
<p>You can find the player ID by looking at their profile on hotslogs.com and taking it from the URL.<br />
 <small>www.hotslogs.com/Player/Profile?PlayerID=</small><b><big>1839756</big></b></p> 
</form>
<h2>Current Players</h2>
<p>These are the players currently in the database.</p>
<table class="widefat" cellspacing="0">
<thead>
<tr> 
    <th align="left" width="30%">Player ID</th>
    <th align="left" width="60%">Name</th>
    <th align="left" width="10%">Delete</th> 
</tr>
</thead>
<tbody>
<?php
	$result = getData();
	$i = 1;
	foreach($result as $res){
		echo 	
		"<tr> 
			<td>" . $res->player_id . "</td>
			<td>" . $res->name . "</td>
			<td>" . '<input type="submit" name="del_'.$res->name.'" id="del_'.$res->name.'" class="button delete" value="X">' . "</td>
		</tr>";	
		$i++;
	}
?>
</tbody>
</table>
</div>
<?php
}
function player_added_notice() {
    ?>
    <div class="updated">
        <p><?php _e( 'New player has been added', 'my-text-domain' ); ?></p>
    </div>
    <?php
}


function error_notice() {
	$class = "error";
	$message = "Error! You entered an invalid HOTS Logs player ID.";
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
}

?>