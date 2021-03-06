<?php
/**
 * Register an address widget
 *
 * A widget that displays the address data set in the options table.
 *
 * @link       http://dev-notes.eu
 * @since      1.0.0
 *
 * @package    Address
 * @subpackage Address/includes
 * @see https://codex.wordpress.org/Widgets_API
 * 
 */
class Address_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		parent::__construct(
			'address_widget',                                             // Base ID
			__( 'Address', 'address' ),                                   // Name
			array( 'description' => __( 'Display address', 'address' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args Widget arguments
	 * @param array $instance Saved Values from Database
	 */
	public function widget( $args, $instance ) {

		// outputs the content of the widget
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {

			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];

		}

    echo Address_Public::get_address();
		//echo __( 'Hello, World!', 'text_domain' );

		echo $args['after_widget'];

	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		// outputs the options form on admin
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php

	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {

		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;

	}
}
