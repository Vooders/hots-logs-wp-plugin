<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Creating the widget 
class hots_logs_all_data_widget extends WP_Widget {

function __construct() {
	parent::__construct(
		// Base ID of your widget
		'hots_logs_all_data_widget', 
		
		// Widget name will appear in UI
		__('HOTS Logs | All player data table', 'hots_logs_all_data_widget_domain'), 
		
		// Widget description
		array( 'description' => __( 'Displays a leaderboard of quick match MMRs.', 'hots_logs_all_data_widget_domain' ), ) 
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
	$results = getData();								 	// Get the table from the db
	echo __('
			<table class="widefat" cellspacing="0">
		<thead>
		<tr> 
			<th>Player ID</th>
			<th>Name</th>
			<th>Quick Match MMR</th> 
			<th>Hero League MMR</th> 
			<th>Combined Hero Level</th> 
			<th>Total Games Played</th> 
		</tr>
		</thead>
		<tbody>
		', 'hots_logs_all_data_widget_domain');
	foreach($results as $result){
		echo __('
			<tr>
				<td>'.$result->player_id.'</td>
				<td>'.$result->name.'</td>
				<td>'.$result->qm_mmr.'</td>
				<td>'.$result->hl_mmr.'</td>
				<td>'.$result->comb_hero_level.'</td>
				<td>'.$result->total_games_played.'</td>
			</tr>
		', 'hots_logs_all_data_widget_domain');
	}
	echo __('</tbody>', 'hots_logs_all_data_widget_domain');
	echo __('</table>', 'hots_logs_all_data_widget_domain');				// Close the table
	echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	} else {
		$title = __( 'Player Data', 'hots_logs_all_data_widget_domain' );
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
} // Class hots_logs_all_data_widget ends here

// Register and load the widget
function hots_logs_all_data_load_widget() {
	register_widget( 'hots_logs_all_data_widget' );
}

add_action( 'widgets_init', 'hots_logs_all_data_load_widget' );
?>