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
<h2>Hots Logs Options</h2>
<hr />
<p>Enter the HOTS Logs player IDs of the players you want to track.</p>
<p>You can find the player IDs by looking at the URL at hotslogs.com</p> 
ex. <small>www.hotslogs.com/Player/Profile?PlayerID=</small><b>1839756</b>
<p>
<form method='post' action='options.php'>
<?php wp_nonce_field('update-options'); ?>
<table width='100%'>
    <tr valign='top'>
        <th width='10%' scope='row'>Player 1</th>
        <td width='90%'>
            <input name='hots_logs_player1' type='text' id='player1_data'
            value='<?php echo get_option('hots_logs_player1'); ?>' />
        </td>
    </tr>
     <tr valign='top'>
        <th width='10%' scope='row'>Player 2</th>
        <td width='90%'>
            <input name='hots_logs_player2' type='text' id='player2_data'
            value='<?php echo get_option('hots_logs_player2'); ?>' />
        </td>
    </tr>
     <tr valign='top'>
        <th width='10%' scope='row'>Player 3</th>
        <td width='90%'>
            <input name='hots_logs_player3' type='text' id='player3_data'
            value='<?php echo get_option('hots_logs_player3'); ?>' />
        </td>
    </tr>
     <tr valign='top'>
        <th width='10%' scope='row'>Player 4</th>
        <td width='90%'>
            <input name='hots_logs_player4' type='text' id='player4_data'
            value='<?php echo get_option('hots_logs_player4'); ?>' />
        </td>
    </tr>
     <tr valign='top'>
        <th width='10%' scope='row'>Player 5</th>
        <td width='90%'>
            <input name='hots_logs_player5' type='text' id='player5_data'
            value='<?php echo get_option('hots_logs_player5'); ?>' />
        </td>
    </tr>
    
</table>
<input type='hidden' name='action' value='update' />
<input type='hidden' name='page_options' value='hots_logs_player1' />
<p>
<input type='submit' value='<?php _e('Save Changes') ?>' />
</p>
</form>
</div>
<?php
}
?>