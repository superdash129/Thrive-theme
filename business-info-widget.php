<?php
/**
 * Business_Info_Widget Class
 */
class Business_Info_Widget extends WP_Widget {
    /** constructor */
    function Business_Info_Widget() {
		$widget_ops = array( 'classname' => 'widget_business_info', 'description' => 'Displays business information' );
        parent::WP_Widget( 'business_info', $name = 'Business Info', $widget_ops );	
    }

    /** @see WP_Widget::widget */
    function widget( $args, $instance ) {		
        extract( $args );
		$settings = get_option( 'thrive_global_settings' );
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$addr1 = ( 1 == $instance[ 'address1' ] && ! empty( $settings[ 'address1' ] ) ) ? true : false;
		$addr2 = ( 1 == $instance[ 'address2' ] && ! empty( $settings[ 'address2' ] ) ) ? true : false;
		$city = ( 1 == $instance[ 'city' ] && ! empty( $settings[ 'city' ] ) ) ? true : false;
		$state = ( 1 == $instance[ 'state' ] && ! empty( $settings[ 'state' ] ) ) ? true : false;
		$zip = ( 1 == $instance[ 'zip' ] && ! empty( $settings[ 'zip' ] ) ) ? true : false;
		$phone = ( 1 == $instance[ 'phone' ] && ! empty( $settings[ 'phone' ] ) ) ? true : false;
		$fax = ( 1 == $instance[ 'fax' ] && ! empty( $settings[ 'fax' ] ) ) ? true : false;
		$email = ( 1 == $instance[ 'email' ] && ! empty( $settings[ 'email' ] ) ) ? true : false;
		$city_separator = ( $city && $state ) ? ', ' : '<br />';
		$city_separator = ( $city && $zip && ! $state ) ? ' ' : $city_separator;
		$state_separator = ( $state && $zip ) ? ' ' : '<br />';
		
		echo $before_widget;
		if ( $title ) { echo $before_title . $title . $after_title; }
		if ( $addr1 ) { echo $settings[ 'address1' ] . '<br />'; }
		if ( $addr2 ) { echo $settings[ 'address2' ] . '<br />'; }
		if ( $city ) { echo $settings[ 'city' ] . $city_separator; }
		if ( $state ) { echo $settings[ 'state' ] . $state_separator; }
		if ( $zip ) { echo $settings[ 'zip' ] . '<br />'; }
		if ( $phone ) { echo 'P. ' . $settings[ 'phone' ] . '<br />'; }
		if ( $fax ) { echo 'F. ' . $settings[ 'fax' ] . '<br />'; }
		if ( $email ) { echo '<a href="mailto:' . esc_attr( $settings[ 'email' ] ) . '">Send us an email</a><br />'; }
		echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'address1' ] = $new_instance[ 'address1' ];
		$instance[ 'address2' ] = $new_instance[ 'address2' ];
		$instance[ 'city' ] = $new_instance[ 'city' ];
		$instance[ 'state' ] = $new_instance[ 'state' ];
		$instance[ 'zip' ] = $new_instance[ 'zip' ];
		$instance[ 'phone' ] = $new_instance[ 'phone' ];
		$instance[ 'fax' ] = $new_instance[ 'fax' ];
		$instance[ 'email' ] = $new_instance[ 'email' ];
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {
		$instance = wp_parse_args( ( array ) $instance, array( 'title' => '', 'address1' => 0, 'address2' => 0, 'city' => 0, 'state' => 0, 'zip' => 0, 'phone' => 0, 'fax' => 0, 'email' => 0 ) );
		
        $title = esc_attr( $instance[ 'title' ] );
		$addr1 = $instance[ 'address1' ];
		$addr2 = $instance[ 'address2' ];
		$city = $instance[ 'city' ];
		$state = $instance[ 'state' ];
		$zip = $instance[ 'zip' ];
		$phone = $instance[ 'phone' ];
		$fax = $instance[ 'fax' ];
		$email = $instance[ 'email' ];
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <input class="checkbox" id="<?php echo $this->get_field_id( 'address1' ); ?>" name="<?php echo $this->get_field_name( 'address1' ); ?>" type="checkbox" value="1" <?php checked( $addr1, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'address1' ); ?>"><?php _e( 'Address 1' ); ?></label><br />
            <input class="checkbox" id="<?php echo $this->get_field_id( 'address2' ); ?>" name="<?php echo $this->get_field_name( 'address2' ); ?>" type="checkbox" value="1" <?php checked( $addr2, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'address2' ); ?>"><?php _e( 'Address 2' ); ?></label><br />
            <input class="checkbox" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" type="checkbox" value="1" <?php checked( $city, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'city' ); ?>"><?php _e( 'City' ); ?></label><br />
            <input class="checkbox" id="<?php echo $this->get_field_id( 'state' ); ?>" name="<?php echo $this->get_field_name( 'state' ); ?>" type="checkbox" value="1" <?php checked( $state, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'state' ); ?>"><?php _e( 'State' ); ?></label><br />
            <input class="checkbox" id="<?php echo $this->get_field_id( 'zip' ); ?>" name="<?php echo $this->get_field_name( 'zip' ); ?>" type="checkbox" value="1" <?php checked( $zip, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'zip' ); ?>"><?php _e( 'Zip' ); ?></label><br />
            <input class="checkbox" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="checkbox" value="1" <?php checked( $phone, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e( 'Phone' ); ?></label><br />
            <input class="checkbox" id="<?php echo $this->get_field_id( 'fax' ); ?>" name="<?php echo $this->get_field_name( 'fax' ); ?>" type="checkbox" value="1" <?php checked( $fax, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'fax' ); ?>"><?php _e( 'Fax' ); ?></label><br />
            <input class="checkbox" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="checkbox" value="1" <?php checked( $email, 1 ); ?> /> <label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Email' ); ?></label>
        <?php 
    }

} // class Business_Info_Widget
register_widget('Business_Info_Widget');
?>