<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Creating the widget 
class qm_widget extends WP_Widget {

function __construct() {
	parent::__construct(
		// Base ID of your widget
		'hots_logs_qm_leaderboard_widget', 
		
		// Widget name will appear in UI
		__('HOTS Logs | Quick Match Leaderboard', 'qm_widget_domain'), 
		
		// Widget description
		array( 'description' => __( 'Displays a leaderboard of quick match MMRs.', 'qm_widget_domain' ), ) 
	);
	
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	// before and after widget arguments are defined by themes
	echo $args['before_widget'];
	if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];
	
	// This is where you run the code and display the output
	$result = getData();								 	// Get the table from the db
	$filtered_result = array();								// Create an array for our filtered data
	foreach($result as $res){								// For each result in the table...
		$k = $res->name;										// Save the key(player name) to k
		$v = $res->qm_mmr;										// Save the value(mmr) to v
		$filtered_result[$k] = $v;								// Add k and v to our filtered array
	}
	arsort($filtered_result);								// Sort the filtered array so highest mmr is top
	$i=1;													// Declare an int to count the positions
	echo __('<table width="100%">', 'qm_widget_domain');	// Write our table headers
	foreach($filtered_result as $key => $val){				// For each filtered result
		echo __('<tr>', 'qm_widget_domain');					// Start table row
		echo __("<th>" . $i ."</th><td>".$key."</td><td>".		// Write the position, name and mmr 
					$val."</td>", 'qm_widget_domain' );			// to the table
		echo __('</tr>', 'qm_widget_domain');					// End table row
		$i++;													// Increase position by 1
	}
	echo __('</table>', 'qm_widget_domain');				// Close the table
	echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	} else {
		$title = __( 'Quick Match Leaderboard', 'qm_widget_domain' );
	}
	// Widget admin form
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class qm_widget ends here

// Register and load the widget
function qm_load_widget() {
	register_widget( 'qm_widget' );
}

add_action( 'widgets_init', 'qm_load_widget' );
?>