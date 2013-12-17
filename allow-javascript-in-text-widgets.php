<?php
/*
Plugin Name: Allow Javascript in Text Widgets
Plugin URI: http://philipjohn.co.uk/#pj-better-multisite-text-widget
Description: Replaces the default text widget with one that allows Javascript so you can do basic things like add Google Ads to your sidebar without using other plugins.
Version: 0.3
Author: Philip John
Author URI: http://philipjohn.co.uk
License: GPL2
Network: true
Text Domain: allow-javascript-in-text-widgets
*/

/**
 * Text widget class
 */
class WP_Widget_Text_With_JS extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML', 'allow-javascript-in-text-widgets'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('text', __('Text', 'allow-javascript-in-text-widgets'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div class="textwidget"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] =  $new_instance['text'];
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
		$text = esc_textarea($instance['text']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'allow-javascript-in-text-widgets'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs', 'allow-javascript-in-text-widgets'); ?></label></p>
<?php
	}
}
function wp_widget_text_with_js_init(){
	unregister_widget('WP_Widget_Text');
	register_widget('WP_Widget_Text_With_JS');
}
add_action('widgets_init', 'wp_widget_text_with_js_init', 1);
?>