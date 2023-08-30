<?php
/*
	Template Name: Contact
*/
?>
<?php 
	global $post;
	
	$settings = get_option( 'thrive_global_settings' ); 
	// fonts needed
	wp_enqueue_script( 'thrive-google-map', 'http://maps.google.com/maps/api/js?sensor=false', array( ), '3.0', true );
	wp_enqueue_script( 'thrive-contact', get_bloginfo( 'template_url' ) . '/js/contact.js', array( 'jquery', 'jquery-form', 'json2', 'thrive-google-map', 'thrive-global-first' ), '66', true );
	
	$map = json_decode( $settings[ 'google_map_location' ], true );
	$map_icon = '';
	if( has_post_thumbnail( $post->ID ) ) {
		$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thrive-map-icon' );
		$map_icon = $img[ 0 ];
	}
	
	wp_localize_script( 'thrive-contact', 'Thrive', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ), 
		'map_icon' => $map_icon,
		'map_status' => $map[ 'status' ], 
		'map_address' => $map[ 'results' ][ 0 ][ 'formatted_address' ],
		'map_lat' => $map[ 'results' ][ 0 ][ 'geometry' ][ 'location' ][ 'lat' ],
		'map_lng' => $map[ 'results' ][ 0 ][ 'geometry' ][ 'location' ][ 'lng' ],
		'map_viewport_sw' => $map[ 'results' ][ 0 ][ 'geometry' ][ 'viewport' ][ 'southwest' ][ 'lat' ] . ',' . $map[ 'results' ][ 0 ][ 'geometry' ][ 'viewport' ][ 'southwest' ][ 'lng' ],
		'map_viewport_ne' => $map[ 'results' ][ 0 ][ 'geometry' ][ 'viewport' ][ 'northeast' ][ 'lat' ] . ',' . $map[ 'results' ][ 0 ][ 'geometry' ][ 'viewport' ][ 'northeast' ][ 'lng' ],
	) );
?>
<?php get_header(); ?>
		<div id="content" class="container_8">
			<?php 
				$addr1 = ( ! empty( $settings[ 'address1' ] ) ) ? true : false;
				$addr2 = ( ! empty( $settings[ 'address2' ] ) ) ? true : false;
				$city = ( ! empty( $settings[ 'city' ] ) ) ? true : false;
				$state = ( ! empty( $settings[ 'state' ] ) ) ? true : false;
				$zip = ( ! empty( $settings[ 'zip' ] ) ) ? true : false;
				$phone = ( ! empty( $settings[ 'phone' ] ) ) ? true : false;
				$fax = ( ! empty( $settings[ 'fax' ] ) ) ? true : false;
				$email = ( ! empty( $settings[ 'email' ] ) ) ? true : false;
				$hours = ( ! empty( $settings[ 'hours' ] ) ) ? true : false;
			?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="grid_8">
					<h2 class="thrive_title thrive_title_text thrive_separator thrive_tpad29"><?php the_title(); ?></h2> 
					<h3 class="thrive_subtitle"><?php echo get_post_meta( get_the_ID(), '_thrive_subtitle_value', true ); ?></h3>
					<div id="thrive_success_message" class="thrive_success_message" style="display:none;"></div>
					<div id="thrive_error_message" class="thrive_error_message" style="display:none;"><span class="thrive_error_logo"></span><span class="thrive_error_text"><?php _e( 'Please Correct the Fields Below. Hover the icons for help.', 'thrive' ); ?></span></div>
				</div>
				<div class="grid_3">
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'thrive' ) ); ?>
						</div><!-- .entry-content -->
						<form id="thrive_contact_form" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post">
							<div class="thrive_contact_item thrive_tpad9">
								<label class="thrive_contact_label_text" for="thrive_name"><?php _e( 'Name', 'thrive' ); ?></label>
								<label id="thrive-target-1" class="thrive_error_label" for="thrive_name"></label>
								<input id="thrive_name" class="thrive_text_input" type="text" name="thrive_name" />
							</div>
							<div class="thrive_contact_item thrive_tpad9">
								<label class="thrive_contact_label_text" for="thrive_email"><?php _e( 'Email', 'thrive' ); ?></label>
								<label id="thrive-target-2" class="thrive_error_label" for="thrive_email"></label>
								<input id="thrive_email" class="thrive_text_input" type="text" name="thrive_email" />
							</div>
							<div class="thrive_contact_item thrive_tpad9">
								<label class="thrive_contact_label_text" for="thrive_subject"><?php _e( 'Subject', 'thrive' ); ?></label>
								<label id="thrive-target-3" class="thrive_error_label" for="thrive_subject"></label>
								<input id="thrive_subject" class="thrive_text_input" type="text" name="thrive_subject" />
							</div>
							<div class="thrive_contact_item thrive_tpad9">
								<label class="thrive_contact_label_text" for="thrive_message"><?php _e( 'Message', 'thrive' ); ?></label>
								<label id="thrive-target-4" class="thrive_error_label" for="thrive_message"></label>
								<textarea id="thrive_message" class="thrive_text_input" name="thrive_message" cols="40" rows="3"></textarea>
							</div>
							<div class="thrive_contact_submit thrive_tpad18">
								<span class="thrive_contact_submit_text"><input type="submit" value="Send" /></span>
							</div>
							<div class="thrive_alt_contact thrive_tpad18">
								<div class="thrive_contact_email_alt_title"><?php _e( 'Prefer to use your email client?', 'thrive' ); ?></div>
								<span class="thrive_contact_email_alt_text">
								<?php if ( $email ) { ?>
									<?php _e( 'email:', 'thrive' ); ?> <a href="mailto:<?php echo esc_attr( $settings[ 'email' ] ); ?>"><?php echo esc_attr( $settings[ 'email' ] ); ?></a>
								<?php } else { ?>
									<?php _e( 'email:', 'thrive' ); ?> <a href="mailto:info@thrivethinking.com"><?php _e( 'info@thrivethinking.com', 'thrive' ); ?></a>
								<?php } ?>
								</span>
							</div>
						</form>
					</div><!-- #post-## -->
				</div>
			<?php endwhile; // End the loop. Whew. ?>
			<div class="grid_5">
				<div class="grid_5 alpha omega"><h4><?php _e( 'Call or come by', 'thrive' ); ?></h4></div>
				<div id="map_canvas" class="grid_5 alpha omega map_canvas"></div>
				<div class="grid_5 alpha omega thrive_tpad14"></div>
				<?php 
					$addr_separator = ( $addr1 && $addr2 ) ? ', ' : '<br />';
					$city_separator = ( $city && $state ) ? ', ' : '<br />';
					$city_separator = ( $city && $zip && ! $state ) ? ' ' : $city_separator;
					$state_separator = ( $state && $zip ) ? ' ' : '<br />';
				?>
				<?php if ( $addr1 || $addr2 || $city || $state || $zip ) { ?>
					<div class="grid_2 alpha">
						<div class="thrive_contact_info_title"><?php _e( 'Our Address', 'thrive' ); ?></div>
						<span class="thrive_contact_info_text">
						<?php
							if ( $addr1 ) { echo $settings[ 'address1' ] . $addr_separator; }
							if ( $addr2 ) { echo $settings[ 'address2' ] . '<br />'; }
							if ( $city ) { echo $settings[ 'city' ] . $city_separator; }
							if ( $state ) { echo $settings[ 'state' ] . $state_separator; }
							if ( $zip ) { echo $settings[ 'zip' ]; }
							if ( ( $addr1 && ( $city || $state ) ) || $zip ) {
								_e( ' <span class="thrive_contact_pipe">|</span> <a id="thrive_print_map" class="thrive_print_map" href="#" target="_blank">Print Map</a>', 'thrive' );
							}
						?>
						</span>
					</div>
				<?php } ?>
				<?php if ( $phone ) { ?>
					<div class="grid_1">
						<div class="thrive_contact_info_title"><?php _e( 'Call Us', 'thrive' ); ?></div>
						<span class="thrive_contact_info_text"><?php echo $settings[ 'phone' ]; ?></span>
					</div>
				<?php } ?>
				<?php if ( $fax ) { ?>
					<div class="grid_1">
						<div class="thrive_contact_info_title"><?php _e( 'Send A Fax', 'thrive' ); ?></div>
						<span class="thrive_contact_info_text"><?php echo $settings[ 'fax' ]; ?></span>
					</div>
				<?php } ?>
				<?php if ( $hours ) { ?>
					<div class="grid_1 omega">
						<div class="thrive_contact_info_title"><?php _e( 'Hours', 'thrive' ); ?></div>
						<span class="thrive_contact_info_text"><?php echo $settings[ 'hours' ]; ?></span>
					</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="thrive_tpad56"></div>
		<div id="thrive-content-1" class="thrive_tooltip"><?php _e( 'Name is required. Please do not leave empty.', 'thrive' ); ?></div>
		<div id="thrive-content-2" class="thrive_tooltip"><?php _e( 'A valid email is required. Please do not leave empty.', 'thrive' ); ?></div>
		<div id="thrive-content-3" class="thrive_tooltip"><?php _e( 'Subject is required. Please do not leave empty.', 'thrive' ); ?></div>
		<div id="thrive-content-4" class="thrive_tooltip"><?php _e( 'Message is required. Please do not leave empty.', 'thrive' ); ?></div>
<?php get_footer() ?>