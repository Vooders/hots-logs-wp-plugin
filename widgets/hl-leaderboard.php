<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Creating the widget 
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
	// Base ID of your widget
	'hots_logs_hl_leaderboard_widget', 
	
	// Widget name will appear in UI
	__('HOTS Logs | Hero League Leaderboard', 'wpb_widget_domain'), 
	
	// Widget description
	array( 'description' => __( 'Displays a leaderboard of hero league MMRs for all players tracked.', 'wpb_widget_domain' ), ) 
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
$result = getData();
echo __('<table width="100%">', 'wpb_widget_domain');
$i=1;
$filtered_result = array();
foreach($result as $res){
	$k = $res->name;
	$v = $res->hl_mmr;
	
	$filtered_result[$k] = $v;
}
arsort($filtered_result);
foreach($filtered_result as $key => $val){
	if ($val == 0)
		$val = 'n/a';
	echo __('<tr>', 'qm_widget_domain');
	echo __("<th>" . $i ."</th><td>".$key."</td><td>".$val."</td>", 'qm_widget_domain' );
	echo __('</tr>', 'qm_widget_domain');
	$i++;
}
echo __('</table>', 'wpb_widget_domain');
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Hero League Leaderboard', 'wpb_widget_domain' );
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
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}


add_action( 'widgets_init', 'wpb_load_widget' );
?>