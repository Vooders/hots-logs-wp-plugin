<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( is_admin() ){ // Checks user is an admin
	/* Call the html code */
	add_action('admin_menu', 'hots_logs_admin_menu');
	
	function hots_logs_admin_menu() {
		add_options_page('Hots Logs', 'Hots Logs', 'administrator',
		'hots_logs', 'hots_logs_html_page');
	}
}

function hots_logs_html_page() {
?>
<div>
<h1>Hots Logs Options</h1>
<br />
<h2>Add a Player</h2>
<p>Enter the HOTS Logs player IDs of the players you want to track.</p>
<p>You can find the player IDs by looking at the URL at hotslogs.com</p> 
<p>
<form method='post' action='options.php'>
<?php wp_nonce_field('update-options'); ?>
<table width='100%'>
    <tr valign='top'>
        <th width='10%' scope='row'>Player ID</th>
        <td width='90%'>
            <input name='hots_logs_player1' type='text' id='player1_data'
            value='<?php echo get_option('hots_logs_player1'); ?>' />
        </td>
    </tr>   
</table>
<input type='hidden' name='action' value='update' />
<input type='hidden' name='page_options' value='hots_logs_player1' />
<p>
<input type='submit' value='<?php _e('Add Player') ?>' />
</p>
</form>
<br />
<h2>Current Players</h2>
<table width="25%">
<?php
	$result = getData();
	$i = 1;
	foreach($result as $res){
		if ($val == 0)
			$val = 'n/a';
		echo '<tr>';
		echo "<th>" . $res->player_id . "</th><td>" . $res->name . "</td>";
		echo '</tr>';
		$i++;
	}
?>
</table>

</div>
<?php
}
?>