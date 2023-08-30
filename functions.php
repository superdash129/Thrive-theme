<?php
include_once( 'business-info-widget.php' );

// menu doc: http://justintadlock.com/archives/2010/06/01/goodbye-headaches-hello-menus
function thrive_setup() {
	$role = get_role( 'editor' );
	$role->add_cap( 'edit_users' );
	$role->add_cap( 'edit_theme_options' );
	$role->add_cap( 'manage_options' );
	
	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails', array( 'thrive_case_study', 'thrive_homepage', 'page' ) );
	add_image_size( 'thrive-map-icon', 140, 101 );
	add_image_size( 'thrive-casestudy-thumb', 210, 118 );
	add_image_size( 'thrive-service-lg', 710, 412, false );
	add_image_size( 'thrive-homepage-fea', 292, 168 );
	add_image_size( 'thrive-homepage-gal', 960, 399 );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'thrive' ),
	) );
	
	/* CHANGEABLE HEADER */
	define( 'HEADER_TEXTCOLOR', '' );
	define( 'HEADER_IMAGE', '%s/images/headers/logo.gif' );
	define( 'HEADER_IMAGE_WIDTH', 335 );
	define( 'HEADER_IMAGE_HEIGHT', 162 );
	define( 'NO_HEADER_TEXT', true );
	add_custom_image_header( '', 'thrive_admin_header' );
	/* @end CHANGEABLE HEADER */
}
add_action( 'after_setup_theme', 'thrive_setup' );

function thrive_widgets_init() {
	// Area 1, located at the footer of the page
	register_sidebar( array(
		'name' => __( 'Footer Widget Area', 'thrive' ),
		'id' => 'footer-widget-area',
		'description' => __( 'The footer widget area', 'thrive' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s grid_1 thrive_tpad25 thrive_footer_text">',
		'after_widget' => '</div>',
		'before_title' => '<span class="thrive_footer_title_text">',
		'after_title' => '</span><br />',
	) );
}
add_action( 'widgets_init', 'thrive_widgets_init' );

add_action( 'init', 'thrive_global_scripts' );
function thrive_global_scripts() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', get_bloginfo( 'template_directory' ) . '/js/jquery.min.js');
        wp_enqueue_script( 'jquery' );
    }
}

function thrive_admin_header() {
	
}

function thrive_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'thrive' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'thrive' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'thrive' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'thrive' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'thrive' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'thrive'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

/* @start UTILITY FUNCTIONS */
function thrive_get_total_posts( $type = 'post' ) {
	global $wpdb;
	
	$type = esc_sql( $type );
	$numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type= '$type'");
	if ( 0 < $numposts ) $numposts = number_format($numposts);
	
	return $numposts;
}

/* Returns boolean whether the page slug exists */
function thrive_have_page( $slugs ) {
	if ( empty( $slugs ) ) {
		return false;
	}
	
	$pages = get_pages();
	
	if ( is_array( $slugs ) ) {
		$existance = array();
		foreach ( $slugs as $slug ) {
			$existance[ $slug ] = false;
			foreach ( $pages as $page ) {
				if ( $page->post_name == $slug ) {
					$existance[ $slug ] = true;
				}
			}
		}
		
		return $existance;
	}
	
	$slug = $slugs;
	foreach ( $pages as $page ) {
		if ( $page->post_name == $slug ) {
			return true;
		}
	}
	
	return false;
}
/* @end UTILITY FUNCTIONS */

/* @start GLOBAL BUSINESS INFORMATION */
add_action('admin_menu', 'thrive_admin_menu');

function thrive_admin_menu() {
	add_options_page('Business Information', 'Business Info', 'manage_options', 'thrive-business-information', 'thrive_business_options');
	
	add_action( 'admin_init', 'thrive_register_settings' );
}

function thrive_globalsettings_validate( $content ) {
	$options = get_option('thrive_global_settings');
	
	$detect_address_change = false;
	if ( $options[ 'address1' ] != $content['address1'] ) { $detect_address_change = true; }
	if ( $options[ 'address2' ] != $content['address2'] ) { $detect_address_change = true; }
	if ( $options[ 'city' ] != $content['city'] ) { $detect_address_change = true; }
	if ( $options[ 'state' ] != $content['state'] ) { $detect_address_change = true; }
	if ( $options[ 'zip' ] != $content['zip'] ) { $detect_address_change = true; }
	
	$content['address1'] = trim( strip_tags( $content['address1'] ) );
	$content['address2'] = trim( strip_tags( $content['address2'] ) );
	$content['city'] = trim( strip_tags( $content['city'] ) );
	$content['state'] = trim( strip_tags( $content['state'] ) );
	$content['zip'] = trim( strip_tags( $content['zip'] ) );
	$content['phone'] = trim( strip_tags( $content['phone'] ) );
	$content['fax'] = trim( strip_tags( $content['fax'] ) );
	$content['email'] = trim( strip_tags( $content['email'] ) );
	$content['hours'] = trim( strip_tags( $content['hours'] ) );
	$content['copyright'] = preg_replace( '@\n@', '<br />', trim( strip_tags( $content['copyright'] ) ) );
	
	if ( $detect_address_change ) {
		// get new google map geocode
		$addy = '';
		$addy .= ( ! empty( $options[ 'address1' ] ) ) ? $options[ 'address1' ] : '';
		$addy .= ( ! empty( $options[ 'address2' ] ) ) ? '+' . $options[ 'address2' ] : '';
		$addy .= ( ! empty( $options[ 'city' ] ) ) ? ',+' . $options[ 'city' ] : '';
		$addy .= ( ! empty( $options[ 'state' ] ) ) ? ',+' . $options[ 'state' ] : '';
		$addy .= ( ! empty( $options[ 'zip' ] ) ) ? ',+' . $options[ 'zip' ] : '';
		$addy = str_replace( ' ', '+', $addy );
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?address=' . esc_attr( $addy ) . '&sensor=false' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$gmaps_json = curl_exec( $ch );
		curl_close ($ch);
		
		$content[ 'google_map_location' ] = str_replace( "\n", '', $gmaps_json );
	} else {
		$content[ 'google_map_location' ] = $options[ 'google_map_location' ];
	}
	
	return $content;
}

function thrive_register_settings() {
	//register our settings
	register_setting( 'thrive-settings-group', 'thrive_global_settings', 'thrive_globalsettings_validate' );
}

function thrive_business_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap">
		<h2>Business Information</h2>

		<form method="post" action="options.php">
            <?php settings_fields('thrive-settings-group'); ?>
            <?php $options = get_option('thrive_global_settings'); ?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">Address 1</th>
					<td><input class="regular-text" type="text" name="thrive_global_settings[address1]" value="<?php echo isset( $options['address1'] ) ? $options['address1'] : ''; ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Address 2</th>
					<td><input class="regular-text" type="text" name="thrive_global_settings[address2]" value="<?php echo isset( $options['address2'] ) ? $options['address2'] : ''; ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">City / State / Zip</th>
					<td>
						<input class="regular-text" type="text" name="thrive_global_settings[city]" value="<?php echo isset( $options['city'] ) ? $options['city'] : ''; ?>" />
						<input class="small-text" type="text" name="thrive_global_settings[state]" value="<?php echo isset( $options['state'] ) ? $options['state'] : ''; ?>" />
						<input class="small-text" type="text" name="thrive_global_settings[zip]" value="<?php echo isset( $options['zip'] ) ? $options['zip'] : ''; ?>" />
					</td>
				</tr>				
				<tr valign="top">
					<th scope="row">Phone</th>
					<td><input class="regular-text" type="text" name="thrive_global_settings[phone]" value="<?php echo isset( $options['phone'] ) ? $options['phone'] : ''; ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Fax</th>
					<td><input class="regular-text" type="text" name="thrive_global_settings[fax]" value="<?php echo isset( $options['fax'] ) ? $options['fax'] : ''; ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Contact Email</th>
					<td><input class="regular-text" type="text" name="thrive_global_settings[email]" value="<?php echo isset( $options['email'] ) ? $options['email'] : ''; ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Hours</th>
					<td><input class="regular-text" type="text" name="thrive_global_settings[hours]" value="<?php echo isset( $options['hours'] ) ? $options['hours'] : ''; ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Copyright</th>
					<td><textarea name="thrive_global_settings[copyright]" cols="52" rows="1" style="width:412px; height:50px;"><?php echo isset( $options['copyright'] ) ? str_replace( '<br />', '', $options['copyright'] ) : ''; ?></textarea></td>
				</tr>
			</table>
			
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php
}
/* @end GLOBAL CONTACT INFORMATION */

add_action( 'admin_init', 'thrive_metabox_setup' );

function thrive_metabox_setup() {
	add_action('add_meta_boxes', 'thrive_casestudy_metabox');
	add_action('add_meta_boxes', 'thrive_homepage_metabox');
	add_action('add_meta_boxes', 'thrive_subtitle_metabox');
	
	add_action('save_post', 'thrive_company_save');
	add_action('save_post', 'thrive_hp_props_save');
	add_action('save_post', 'thrive_subtitle_save');
}

/* @start HOMEPAGE META BOXES */
function thrive_homepage_metabox() {
	add_meta_box( 'thrive_hp_type', __( 'Element Position', 'thrive' ), 'thrive_hp_props_inner_custom_box', 'thrive_homepage', 'normal', 'high' );
}

function thrive_hp_props_inner_custom_box() {
	global $post;
	
	// Use nonce for verification
	wp_nonce_field( plugin_basename(__FILE__), 'thrive_hp_props_noncename' );
	
	$v = get_post_meta( $post->ID, '_thrive_hp_type_value', true );
	// The actual fields for data entry
	?>
		<label for="thrive_hp_type" class="screen-reader-text">Position: </label>
		<select id="thrive_hp_type" name="thrive_hp_type" class="all-options">
			<option value="" disabled="disabled" <?php selected( $v, '' ); ?>>Select one</option>
			<option value="GAL" <?php selected( $v, 'GAL' ); ?>>Gallery Area</option>
			<option value="FEA" <?php selected( $v, 'FEA' ); ?>>Feature</option>
		</select>
	<?php
}

function thrive_hp_props_save( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( isset( $_POST[ 'thrive_hp_props_noncename' ] ) && ! wp_verify_nonce( $_POST['thrive_hp_props_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}


	// Check permissions
	if ( isset( $_POST[ 'post_type' ] ) && 'thrive_homepage' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	$data_type = strip_tags( $_POST['thrive_hp_type'] );

	if ( $data_type != get_post_meta( $post_id, '_thrive_hp_type_value', true ) ) {
		update_post_meta( $post_id, '_thrive_hp_type_value', $data_type );
	} elseif ( empty( $data_type ) ) {
		delete_post_meta( $post_id, '_thrive_hp_type_value', get_post_meta( $post_id, '_thrive_hp_type_value', true ) );
	}

	return $data_type;
}
/* @end HOMEPAGE META BOXES */

/* @start COMPANY META BOX */
function thrive_casestudy_metabox() {
	add_meta_box( 'thrive_company', __( 'Company Name', 'thrive' ), 'thrive_company_inner_custom_box', 'thrive_case_study', 'normal', 'high' );
	add_meta_box( 'thrive_pdf', __( 'Case Study Attachment', 'thrive' ), 'thrive_cs_pdf_inner_custom_box', 'thrive_case_study', 'normal', 'high' );
}

/* Prints the box content */
function thrive_company_inner_custom_box( ) {
	global $post;
	
  // Use nonce for verification
  wp_nonce_field( plugin_basename(__FILE__), 'thrive_company_noncename' );

  // The actual fields for data entry
  ?>
	<label for="thrive_company" class="screen-reader-text">Company Name</label>
	<input id="thrive_company" class="large-text" name="thrive_company" value="<?php echo get_post_meta( $post->ID, '_thrive_company_value', true ); ?>" />
  <?php
}

function thrive_company_save( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( isset( $_POST[ 'thrive_company_noncename' ] ) && ! wp_verify_nonce( $_POST['thrive_company_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}


	// Check permissions
	if ( isset( $_POST[ 'post_type' ] ) && 'thrive_case_study' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	$data = strip_tags( $_POST['thrive_company'] );

	if ( $data != get_post_meta( $post_id, '_thrive_company_value', true ) ) {
		update_post_meta( $post_id, '_thrive_company_value', $data );
	} elseif ( empty( $data ) ) {
		delete_post_meta( $post_id, '_thrive_company_value', get_post_meta( $post_id, '_thrive_company_value', true ) );
	}

	return $data;
}

function thrive_cs_pdf_inner_custom_box( ) {
	global $post;
	
  // Use nonce for verification
  wp_nonce_field( plugin_basename(__FILE__), 'thrive_pdf_noncename' );

  // The actual fields for data entry
  ?>
	<label for="thrive_company" class="screen-reader-text">Attach Case Study Document</label>
	<p> 
		<a title="Add a Document" class="thickbox" id="add_image" href="media-upload.php?post_id=<?php echo esc_attr( $post->ID ); ?>&amp;type=file&amp;TB_iframe=1&amp;width=640&amp;height=175">Upload/Insert</a>
	</p>
	<p>
		<label>Case Study link: </label> 
		<?php
		$args = array(
			'post_type' => 'attachment',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_parent' => $post->ID
		); 
		$attachments = get_posts($args);
		$cslink = '';
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				if( 0 == preg_match( '@image/.@', $attachment->post_mime_type, $r ) ) {
					$cslink = get_the_attachment_link( $attachment->ID );
					break;
				}
			}
		}

		echo empty( $cslink ) ? __( 'No documents have been attached.', 'thrive' ) : $cslink;
		?>
	</p>
	<?php
}
/* @end COMPANY META BOX */

/* @start SUBTITLE META BOX */
function thrive_subtitle_metabox() {
	add_meta_box( 'thrive_subtitle', __( 'Sub Title', 'thrive' ), 'thrive_subtitle_inner_custom_box', 'page', 'normal', 'high' );
}

/* Prints the box content */
function thrive_subtitle_inner_custom_box( ) {
	global $post;
	
  // Use nonce for verification
  wp_nonce_field( plugin_basename(__FILE__), 'thrive_subtitle_noncename' );

  // The actual fields for data entry
  ?>
	<label for="thrive_subtitle" class="screen-reader-text">Sub Title</label>
	<textarea id="thrive_subtitle" name="thrive_subtitle" cols="40" rows="1" style="width:98%; height:4em; margin:0;" ><?php echo get_post_meta( $post->ID, '_thrive_subtitle_value', true ); ?></textarea>
  <?php
}

/* When the post is saved, saves our custom data */
function thrive_subtitle_save( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( isset( $_POST[ 'thrive_subtitle_noncename' ] ) && ! wp_verify_nonce( $_POST['thrive_subtitle_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}


	// Check permissions
	if ( isset( $_POST[ 'post_type' ] ) && 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	$data = strip_tags( $_POST['thrive_subtitle'] );

	if ( $data != get_post_meta( $post_id, '_thrive_subtitle_value', true ) ) {
		update_post_meta( $post_id, '_thrive_subtitle_value', $data );
	} elseif ( empty( $data ) ) {
		delete_post_meta( $post_id, '_thrive_subtitle_value', get_post_meta( $post_id, '_thrive_subtitle_value', true ) );
	}

	return $data;
}
/* @end SUBTITLE META BOX */

/* @start GALLERY */
function thrive_gallery( $input, $attr ) {
	global $post, $wp_locale;

	static $instance = 0;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr[ 'orderby' ] ) ) {
		$attr[ 'orderby' ] = sanitize_sql_orderby( $attr[ 'orderby' ] );
		if ( ! $attr[ 'orderby' ] )
			unset( $attr[ 'orderby' ] );
	}

	extract( shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'thrive_type'=> '',
		'thrive_link'=> ''
	), $attr ) );

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( ! empty( $include ) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty( $attachments ) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	if ( 'xp-gal' == $attr[ 'thrive_type' ] ) {
		$columns = 4;
		$output = "
		<style type='text/css'>
			.gallery galleryid-{$id} { margin-top:-5px; }
			.xpgal_link { display:block; width:210px; height:119px; position:relative; margin-top:40px; }
			.xpgal_link:hover .xpgal_hover { display:none; }
			.xpgal_hover { position:absolute; top:0; left:0; }
			.xpgal_image {}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->
		<div id='$selector' class='gallery galleryid-{$id}'>";
		
		$blank = '';
		$tmp = '';
		$i = 1;
		foreach ( $attachments as $id => $attachment ) {
			$img = wp_get_attachment_image( $id, $size, false );
			if ( 1 == preg_match( '/blank[^\.]*.(gif|jpg|png)"/', $img ) ) {
				$blank .= '<div class="grid_2 thrive_tpad40">';
				$blank .= $img;
				$blank .= '</div>';
				continue;
			}
			
			if( empty( $linkto ) ) {
				$linkto = get_post_meta( $id, '_thrive_linkto_url', true );
			}
			
			if ( 0 == $i % 2 ) {
				$grid_mod = ( 0 == ( $i / 2 ) % $columns ) ? ' omega' : '';
				$grid_mod = ( 1 == ( $i / 2 ) % $columns ) ? ' alpha' : $grid_mod;
				
				$no_link = '';
				if ( empty( $linkto ) ) {
					$linkto = 'javascript:void();';
					$no_link = ' no_link';
				}
				
				$output .= "
<div class='grid_2{$grid_mod}'>
	<div class='xpgal_link{$no_link}'>
		$tmp
		<div class='xpgal_hover'>{$img}</div>
	</div>
</div>
				";
				$tmp = '';
				$linkto = '';
			} else {
				$tmp .= "
<div class='xpgal_image'><a class='{$no_link}' href='{$linkto}'>{$img}</a></div>
				";
			}
			
			$i++;
		}
		$blanks = ( ( $i - 1 ) / 2 ) % $columns;
		if ( $blanks > 0 ) {
			$omegablank = str_replace( 'grid_2', 'grid_2 omega', $blank );
			for ( $j = 0; $j < $blanks - 1; $j++ ) {
				$output .= $blank;
			}
			$output .= $omegablank;
		}
		
		$output .= '<div class="clear"></div></div>';
		return $output;
	}
	
	$output = apply_filters('thrive_gallery_style', "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 15px;
				text-align: center;
				width: {$itemwidth}%;			
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->
		<div id='$selector' class='gallery galleryid-{$id}'>");

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$linkto = get_post_meta( $id, '_thrive_linkto_url', true );
		
		if ( $attr[ 'thrive_link' ] == 'forced' ) {
			$link = isset( $attr[ 'link' ] ) && 'file' == $attr[ 'link' ] ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );		
		} else {
			if( empty( $linkto ) ) {
				$link = wp_get_attachment_image( $id, $size, false );
			} else {
				$link = '<a href="' . $linkto . '">' . wp_get_attachment_image( $id, $size, false ) . '</a>';
			}
		}

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "<{$icontag} class='gallery-icon'>$link</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;
}
add_filter( 'post_gallery', 'thrive_gallery', 10, 2 );
/* @end GALLERY */

/* @start CUSTOM TYPES */
add_action('init', 'thrive_register_custom_types');
function thrive_register_custom_types() {
  $labels = array(
    'name' => _x('Case Studies', 'post type general name'),
    'singular_name' => _x('Case Study', 'post type singular name'),
    'add_new' => _x('Add New', 'Case Study'),
    'add_new_item' => __('Add New Case Study'),
    'edit_item' => __('Edit Case Study'),
    'new_item' => __('New Case Study'),
    'view_item' => __('View Case Study'),
    'search_items' => __('Search Case Studies'),
    'not_found' =>  __('No case studies found'),
    'not_found_in_trash' => __('No case studies found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'author', 'thumbnail', 'excerpt' ),
	'taxonomies' => array( 'category' )
  ); 
  register_post_type( 'thrive_case_study', $args );
  
  $labels = array(
    'name' => _x('HP Features', 'post type general name'),
    'singular_name' => _x('Homepage Feature', 'post type singular name'),
    'add_new' => _x('Add New', 'Homepage'),
    'add_new_item' => __('Add New Homepage Feature'),
    'edit_item' => __('Edit Homepage Feature'),
    'new_item' => __('New Homepage Feature'),
    'view_item' => __('View Homepage Feature'),
    'search_items' => __('Search homepage features'),
    'not_found' =>  __('No homepage features found'),
    'not_found_in_trash' => __('No homepage features found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	'taxonomies' => array( 'category' )
  ); 
  register_post_type( 'thrive_homepage', $args );
}
/* @end CUSTOM TYPES */



/* @start EDIT GALLERY CHANGES */
//http://blancer.com/tutorials/65949/creating-custom-fields-for-attachments-in-wordpress/
/**
 * Adding our custom fields to the $form_fields array
 *
 * @param array $form_fields
 * @param object $post
 * @return array
 */
function thrive_attachment_fields_to_edit( $form_fields, $post ) {
	// $form_fields is a special array of fields to include in the attachment form
	// $post is the attachment record in the database
	//     $post->post_type == 'attachment'
	// (attachments are treated as posts in WordPress)
	
	// add our custom field to the $form_fields array
	// input type="text" name/id="attachments[$attachment->ID][custom1]"
	$form_fields[ 'thrive_linkto_url' ] = array(
		'label' => __( 'External URL' ),
		'input' => 'text', // this is default if "input" is omitted
		'value' => get_post_meta( $post->ID, '_thrive_linkto_url', true ),
		'helps' => __( 'The url that the gallery image links to when clicked on.' )
	);

	return $form_fields;
}
// attach our function to the correct hook
add_filter( 'attachment_fields_to_edit', 'thrive_attachment_fields_to_edit', null, 2 );


/**
 * @param array $post
 * @param array $attachment
 * @return array
 */
add_filter( 'attachment_fields_to_save', 'thrive_attachment_fields_to_save', null, 2 );
function thrive_attachment_fields_to_save( $post, $attachment ) {
	// $attachment part of the form $_POST ($_POST[attachments][postID])
	// $post attachments wp post array - will be saved after returned
	//     $post['post_type'] == 'attachment'

	if( isset( $attachment[ 'thrive_linkto_url' ] ) ) {
		// update_post_meta(postID, meta_key, meta_value);
		update_post_meta( $post[ 'ID' ], '_thrive_linkto_url', $attachment[ 'thrive_linkto_url' ] );
	}
	return $post;
}

/* @end EDIT GALLERY CHANGES */

/* @start CASE_STUDIES SHORTCODE */
add_shortcode( 'case_studies', 'do_case_studies' );
function do_case_studies() {
	$page_size = 16;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	query_posts( array( 'post_type' => 'thrive_case_study', 'posts_per_page' => $page_size, 'paged' => $paged ) );
	
	$columns = 4;
	$i = 1; 
	?>
	
	<div class="thrive_tpad21"></div>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php
			$grid_mod = ( 0 == $i % $columns ) ? ' omega' : '';
			$grid_mod = ( 1 == $i % $columns ) ? ' alpha' : $grid_mod;
			$categories = get_the_category();
			$category = empty( $categories ) ? '' : $categories[ 0 ]->category_nicename;
			
			$args = array(
				'post_type' => 'attachment',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_parent' => get_the_ID()
			); 
			$attachments = get_posts($args);
			$cslink = 'javascript:void();';
			$no_link = ' no_link';
			if ( $attachments ) {
				foreach ( $attachments as $attachment ) {
					if( 0 == preg_match( '@image/.@', $attachment->post_mime_type, $r ) ) {
						$cslink = esc_attr( wp_get_attachment_url( $attachment->ID ) );
						$no_link = '';
						break;
					}
				}
			}
		?>
		<div class="grid_2 thrive_tpad14<?php echo $grid_mod; ?>">
			<div class="thrive_swapbx thrive_cs_swapbx<?php echo $no_link; ?>">
				<div class="thrive_cs_quote thrive_tpad20<?php echo empty( $category ) ? '' : ' thrive_cs_service_' . $category; ?>">
					<div class="thrive_cs_md_break"></div>
					<p class="thrive_cs_service"><?php echo strtoupper( $category ); ?></p>
					<div class="thrive_cs_blurb"><?php the_excerpt(); ?></div>
					<div class="thrive_cs_sm_break"></div>
					<div class="thrive_tpad7"></div>
					<p class="thrive_cs_link"><a href="<?php echo $cslink; ?>" class="<?php echo $no_link; ?>" target="_blank">view the case study</a></p>
				</div>
				<div class="thrive_swapbx_hover thrive_cs_swapbx_hover"><?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'thrive-casestudy-thumb' );
					}
					?>
					<span class="thrive_cs_product_text"><?php the_title(); ?></span><br />
					<span class="thrive_cs_company_text"><?php echo get_post_meta( get_the_ID(), '_thrive_company_value', true ); ?></span>
				</div>
				<form id="thrive_<?php the_ID(); ?>"><input type="hidden" name="thrive_dl_link_<? the_ID(); ?>" id="thrive_dl_link_<? the_ID(); ?>" value="<?php echo $cslink; ?>" /></form>
			</div>
		</div>
		<?php $i++ ?>
	<?php
	endwhile;
	
	if ( $i < $page_size - $columns ) {
		for( $j=0; $j < $columns; $j++ ) {
			$grid_mod = ( 0 == $j % $columns ) ? ' omega' : '';
			$grid_mod = ( 1 == $j % $columns ) ? ' alpha' : $grid_mod;
			?>
			<div class="grid_2 thrive_tpad14<?php echo $grid_mod; ?>">
				<div class="thrive_empty_swapbx"></div>
			</div>
			<?php
		}
	}
	
	$pagination_first_item = ( $paged * $page_size ) - ($page_size - 1 );
	$pagination_last_item = $pagination_first_item + $i - 2;
	?>
	<div class="grid_8 thrive_tpad28"></div>
	<div class="grid_7 alpha">
		<span class="thrive_cs_pagination">
			<span class="thrive_cs_pagination_number_text"><?php echo $pagination_first_item; ?> - <?php echo $pagination_last_item; ?></span>
			<span class="thrive_cs_pagination_filler_text">of</span>
			<span class="thrive_cs_pagination_number_text"><?php echo thrive_get_total_posts( 'thrive_case_study' ); ?></span>
			<span class="thrive_cs_pagination_filler_text">items</span>
		</span>
	</div>
	<div class="grid_1 omega thrive_pagination">
		<?php 
			$prev = get_previous_posts_link( '<div class="thrive_pagination_prev"></div>' );
			$next = get_next_posts_link( '<div class="thrive_pagination_next"></div>' ); 
			
			if ( empty( $prev ) ) { ?>
				<div class="thrive_pagination_prev_disabled"></div>
			<?php } else { echo $prev; } 
			
			if ( empty ( $next ) ) { ?>
				<div class="thrive_pagination_next_disabled"></div>
			<?php } else { echo $next; }
		?>
		<div class="thrive_clear"></div>
	</div>
	<div class="thrive_clear"></div>
	<?php
	wp_reset_query();
}
/* @end CASE_STUDIES SHORTCODE */

/* @start COLUMN_BLOCK SHORTCODE */
add_shortcode( 'column_block', 'do_column_block' );
function do_column_block( $atts, $content ) {
	static $instance = 0;
	$instance++;
	
	$a = shortcode_atts( array(
		'title' => '',
		'alpha' => null,
		'omega' => null
	), $atts );
	
	$ao_class = '';
	$alpha_test = is_null( $a[ 'alpha' ] ) ? ( 1 == $instance ) : $a[ 'alpha' ];
	$ao_class = ( $alpha_test ) ? ' alpha' : $ao_class;
	$omega_test = is_null( $a[ 'omega' ] ) ? ( 3 == $instance ) : $a[ 'omega' ];
	$ao_class = ( $omega_test ) ? ' omega' : $ao_class;
	
	
	$output = '<div class="grid_2 thrive_column_block thrive_column_block_' . $instance . $ao_class . '">';
	$output .= ( empty( $a[ 'title' ] ) ) ? '' : '<b>' . $a[ 'title' ] . '</b>';
	$output .= strip_tags( $content, '<a><b><em><strong><span><li><ul><ol>' );
	$output .= '</div>';
	
	return $output;
}
/* @end COLUMN_BLOCK SHORTCODE */

/* @start AJAX */
// if both logged in and not logged in users can send this AJAX request,
// add both of these actions, otherwise add only the appropriate one
add_action( 'wp_ajax_nopriv_thrive-gallery-page', 'thrive_gallery_page' );
add_action( 'wp_ajax_thrive-gallery-page', 'thrive_gallery_page' );

function thrive_gallery_page() {
	// get the submitted parameters
	$gallery_page = isset( $_POST['page'] ) ? $_POST[ 'page' ] : 1;
	$exclude_ids = isset( $_POST[ 'exclude' ] ) ? explode( ',', $_POST[ 'exclude' ] ) : array();

	$page_size = 4;
	$columns = 4;
	query_posts( array( 'post_type' => 'thrive_case_study', 'posts_per_page' => $page_size, 'paged' => $gallery_page, 'post__not_in' => $exclude_ids ) );
	
	$i = 1;
	$output = '';
	while ( have_posts() ) : the_post();
		$grid_mod = ( 0 == $i % $columns ) ? ' omega' : '';
		$grid_mod = ( 1 == $i % $columns ) ? ' alpha' : $grid_mod;
		$categories = get_the_category();
		$category = empty( $categories ) ? '' : $categories[ 0 ]->category_nicename;
		
		$args = array(
			'post_type' => 'attachment',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_parent' => get_the_ID()
		); 
		$attachments = get_posts($args);
		$cslink = 'javascript:void();';
		$no_link = ' no_link';
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				if( 0 == preg_match( '@image/.@', $attachment->post_mime_type, $r ) ) {
					$cslink = esc_attr( wp_get_attachment_url( $attachment->ID ) );
					$no_link = '';
					break;
				}
			}
		}
						
		$cat_type = ( empty( $category ) ? '' : ' thrive_cs_service_' . $category );
		$cat_title = strtoupper( $category );
		$excerpt = get_the_excerpt();
		$cs_id = get_the_ID();
		$cs_image = ( has_post_thumbnail() ) ? get_the_post_thumbnail( $cs_id, 'thrive-casestudy-thumb' ) : '';
		$cs_title = get_the_title();
		$company = get_post_meta( $cs_id, '_thrive_company_value', true );
		
		
		$output .= "
<div class='grid_2{$grid_mod}'>
	<div class='thrive_swapbx thrive_cs_swapbx{$no_link}'>
		<div class='thrive_cs_quote thrive_tpad20{$cat_type}'>
			<div class='thrive_cs_md_break'></div>
			<p class='thrive_cs_service'>{$cat_title}</p>
			<div class='thrive_cs_blurb'><p>{$excerpt}</p></div>
			<div class='thrive_cs_sm_break'>
		</div>
		<div class='thrive_tpad7'></div>
		<p class='thrive_cs_link'><a href='{$cslink}' class='{$no_link}'>view the case study</a></p></div>
		<div class='thrive_swapbx_hover thrive_cs_swapbx_hover'>
			{$cs_image}
			<span class='thrive_cs_product_text'>{$cs_title}</span><br />
			<span class='thrive_cs_company_text'>{$company}</span>
		</div>
		<form id='thrive_{$cs_id}'><input type='hidden' name='thrive_dl_link_{$cs_id}' id='thrive_dl_link_{$cs_id}' value='{$cslink}' /></form>
	</div>
</div>
		";
		
		$i++;
	endwhile;
	$output .= '<div class="clear"></div>';
	
	// generate the response
	$response = json_encode( array( 'success' => true, 'page_result' =>  $output ) );

	// response output
	echo $response;

	// IMPORTANT: don't forget to "exit"
	exit;
}

add_action( 'wp_ajax_nopriv_thrive-contact-form', 'thrive_contact_form' );
add_action( 'wp_ajax_thrive-contact-form', 'thrive_contact_form' );
function thrive_contact_form() {
	$name = isset( $_POST[ 'thrive_name' ] ) ? $_POST[ 'thrive_name' ] : '';
	$email = isset( $_POST[ 'thrive_email' ] ) ? $_POST[ 'thrive_email' ] : '';
	$subject = isset( $_POST[ 'thrive_subject' ] ) ? $_POST[ 'thrive_subject' ] : '';
	$message = isset( $_POST[ 'thrive_message' ] ) ? $_POST[ 'thrive_message' ] : '';
	
	if( empty( $name ) || empty( $email ) || empty( $subject ) || empty( $message ) ) {
		$response = json_encode( array( 'success' => false ) );	
		exit;
	}
	
	$settings = get_option( 'thrive_global_settings' ); 
	$response = json_encode( array( 'success' => true ) );
	
	/* SEND EMAIL HERE */
	$admin_email = empty( $settings[ 'email' ] ) ? 'info@thrivethinking.com' : $settings[ 'email' ];
	$from_header = 'From: ' . $email;
	$contact_message = 'Name: ' . $name . "\n";
	$contact_message .= 'Email: ' . $email . "\n";
	$contact_message .= 'Subject: ' . $subject . "\n";
	$contact_message .= 'Message: ' . $message . "\n";
	$contact_message = wordwrap( $contact_message, 70 );
	
	//send mail - $subject & $contents come from surfer input
	$retval = @mail( $admin_email, $subject, $contact_message, $from_header );
	
	if ( ! $retval ) {
		$response = json_encode( array( 'success' => false ) );
	}
	
	echo $response;
	
	exit;
}
/* @end AJAX */

/* @start DASHBOARD HELP */
/* Removing Wordpress' default dashboard modules*/
add_action('wp_dashboard_setup', 'thrive_remove_dashboard_widgets' );
function thrive_remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;

	// Remove from first column
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
	// Remove from second column
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
}

add_action('wp_dashboard_setup', 'thrive_dashboard_help');
function thrive_dashboard_help(){
	//first parameter is the ID of the widget (the div holding the widget will have that ID)
	//second parameter is title (shown in the header of the widget) -> see picture bellow
	//third parameter is the function name we are calling to get the content of our widget
	wp_add_dashboard_widget('thrive_help_welcome', __( 'Site Guidelines', 'thrive' ), 'thrive_help_welcome');
	wp_add_dashboard_widget('thrive_help_hpfeatures', __( 'HP Features Guide', 'thrive' ), 'thrive_help_hpfeatures');
	wp_add_dashboard_widget('thrive_help_pages', __( 'Pages Guide', 'thrive' ), 'thrive_help_pages');
	wp_add_dashboard_widget('thrive_help_casestudies', __( 'Case Studies Guide', 'thrive' ), 'thrive_help_casestudies');
	wp_add_dashboard_widget('thrive_help_media', __( 'Media Guide', 'thrive' ), 'thrive_help_media');
	wp_add_dashboard_widget('thrive_help_footer', __( 'Footer Guide', 'thrive' ), 'thrive_help_footer');
	wp_add_dashboard_widget('thrive_help_appearances', __( 'Appearances Guide', 'thrive' ), 'thrive_help_appearances');
	wp_add_dashboard_widget('thrive_help_settings', __( 'Settings Guide', 'thrive' ), 'thrive_help_settings');
}

function thrive_help_welcome() {
	_e( '<p>Welcome to the help section.</p>', 'thrive' );
}

function thrive_help_hpfeatures() {
	?>
	<p><strong><?php _e( 'How to add a new HP Feature:', 'thrive' ); ?></strong></p>
	<p>
		<ol>
			<li><?php _e( 'Click the Add new link under HP Features on the menu to the left.', 'thrive' ); ?></li>
			<li><?php _e( 'Enter your title for the Feature', 'thrive' ); ?></li>
			<li><?php _e( 'In the editor, enter the text you want for your blurb. To create a "more" link for your feature:', 'thrive' ); ?>
				<ol>
					<li><?php _e( 'Type the text for the link (ensure that the text is on its own line apart from the blurb).', 'thrive' ); ?></li>
					<li><?php _e( 'Ensure you are on the Visual Tab mode of the editor (found at the top right of the editor window.', 'thrive' ); ?></li>
					<li><?php _e( 'Highlight the text you want linked and click on the "Insert/edit link" or press Alt+Shift+A.', 'thrive' ); ?></li>
					<li><?php _e( 'If you are in the HTML tab then click on the Visual tab. Make sure your cursor is on the same line as your link text. Click on the "Unordered List" button or press Alt+Shift+U.', 'thrive' ); ?></li>
				</ol>
			</li>
			<li><?php _e( 'Select a position for your element. If no position is selected then the element will not show up. The "Gallery Area" position will put the feature in the main area of the hompage where the carousel is. The "Feature" position puts the feature below the carousel in one of three boxes. If more then 3 HP Features with position set to feature are available then only the first 3 are used.', 'thrive' ); ?></li>
			<li><?php _e( 'Select "Set Featured Image" link in the Featured Image box. Either upload the image from your computer or choose an image from the Media Library. Once the image is selected then click on the link that say "Use as featured image" when in the image&#39;s properties. Then you may close the dialog box.', 'thrive' ); ?></li>
			<li><?php _e( 'Categories are only for HP Features that are of position Gallery Area. Category determines the color of the box that the text lives in on a carousel slide. If no category is choosen then the default white box will display.', 'thrive' ); ?></li>
			<li><?php _e( 'Order under Attributes box is only for HP Features that are of position Feature. This determines the order of the box on the Features area. For example, if it is desired to have the box as the first Feature then mark the Order as 1. If there are two HP Features with the same order number then the one with the youngest creation date will be choosen first.', 'thrive' ); ?></li>
			<li><?php _e( 'Click Publish button to publish out and save the HP Feature.', 'thrive' ); ?></li>
		</ol>
	</p>
	<?php
}

function thrive_help_pages() {
	?>
	<p><strong><?php _e( 'Overall', 'thrive' ); ?></strong></p>
	<p><?php _e( 'Pages represent the various pages of your site. Each page title listed corresponds with a menu item on your website&#39;s main navigation menu. Removing a page of the menu item title will remove the menu item from the navigation menu. In addition pages may be created with a generic template that integrates with the site&#39;s design.', 'thrive' ); ?></p>
	<p><strong><?php _e( 'Menu Item Page Options', 'thrive' ); ?></strong></p>
	<p><?php _e( 'All of the pages pages have the following list of options available to them, however, not all pages utilize or were design to utilize all the page option. There is a table at the end of this discourse as to what pages use what options.', 'thrive' ); ?></p>
	<ol>
		<li><?php _e( 'Template -- All menu item pages use the template option. Any other page will leave it as Default Template.', 'thrive' ); ?></li>
		<li><?php _e( 'Title -- All pages use title. It puts the title of the page at the top.', 'thrive' ); ?></li>
		<li><?php _e( 'Subtitle -- Most menu item pages use subtitle to put the text just below the title text.', 'thrive' ); ?></li>
		<li><?php _e( 'Editor -- This allows text to be added in the body of the page. Formatting can be achived with the Formatting menu buttons at the top of the editor. When copying text into the editor the best way is to use either the "Paste as Plain Text" or "Paste from Word" buttons. Many times sources you may be copying from will put in unusual characters that are not HTML friendly resulting in unexpected results. For page headings use "Heading 4" in the Format drop down box to create FS Me fonted headings. Use the "Ordered List" button to create lists with the drop cap style of the first character. The editor also uses the [column_block] short code. It will allow lists to be created side by side with an optional header above the list. In the editor type: [column_block] LIST TEXT HERE [/column_block]. Make sure the "unordered list" button is used to create the list.', 'thrive' ); ?></li>
		<li><?php _e( 'Featured Image -- Clicking on the "Set featured image" link will bring up the Media Library. Choose an image from the media library by clicking show, then click on the link "Use as featured image". This is utilized differently based on the template. The Service template will put the featured image just below the subtitle and the Contact template uses the image as the map icon for the info window on the google map.', 'thrive' ); ?></li>
		<li><?php _e( 'Image Gallery -- An image can be accessed from the editior through the Upload/Insert "Add an Image" button or if the gallery short code is being used clicking on the picture of the camera and generic photo and clicking on the "Edit Gallery" button. Images in the gallery can be moved by dragging and dropping the list. Each image has a show link that will display the properties of the image. This is utilized differently based on the template. The About template will display the images on the right side of the page where the Experience template uses the gallery to display the company logos gallery with their hover states.', 'thrive' ); ?></li>
	</ol>
	<p><strong><?php _e( 'Additional notes on pages', 'thrive' ); ?></strong></p>
	<ol>
		<li><?php _e( 'ABOUT -- Use the Upload/Image "Add an Image" button to access the image gallery that is displayed on the right side of the page. Images can be moved up and down the side by dragging and dropping the image order in the gallery.', 'thrive' ); ?></li>
		<li><?php _e( 'CASE STUDIES -- The [case_studies] was intended for the only text in the editor. This creates the gallery wall of case studies that users can browse through.', 'thrive' ); ?></li>
		<li><?php _e( 'DESIGN/INSIGHT/STRATEGY -- These pages all use the Service template.', 'thrive' ); ?></li>
		<li><?php _e( 'EXPERIENCE -- The image gallery has two images for each company. The first image is the hover state and the second image is the default state. There is a special image called blank. This image is used on boxes that do not have any companies in them. Companies can be moved higher on the gallery that is displayed by dragging the hover and default image states up the image list. After making changes click the "Save all changes" button. Then close out the dialog box.', 'thrive' ); ?></li>
	</ol>
	<p><strong><?php _e( 'Table of the pages with the options that each page utilizes.', 'thrive' ); ?></strong></p>
	<table width="100%">
		<tr>
			<th style="text-align:left;"><?php _e( 'Page Option', 'thrive' ); ?></th>
			<th><?php _e( 'ABOUT', 'thrive' ); ?></th>
			<th><?php _e( 'CASE STUDIES', 'thrive' ); ?></th>
			<th><?php _e( 'CONTACT', 'thrive' ); ?></th>
			<th><?php _e( 'DESIGN', 'thrive' ); ?></th>
		</tr>
		<tr>
			<td><?php _e( 'Template', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Title', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Subtitle', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Editor', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Featured Image', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Image Gallery', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<table width="100%">
		<tr>
			<th style="text-align:left;"><?php _e( 'Page Option', 'thrive' ); ?></th>
			<th><?php _e( 'HOME', 'thrive' ); ?></th>
			<th><?php _e( 'EXPERIENCE', 'thrive' ); ?></th>
			<th><?php _e( 'INSIGHT', 'thrive' ); ?></th>
			<th><?php _e( 'STRATEGY', 'thrive' ); ?></th>
		</tr>
		<tr>
			<td><?php _e( 'Template', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Title', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Subtitle', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Editor', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Featured Image', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Image Gallery', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'Y', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
			<td style="text-align:center;"><?php _e( 'N', 'thrive' ); ?></td>
		</tr>
	</table>
	<?php
}
function thrive_help_casestudies() {
	//the content of our custom widget
	?>
	<p><strong><?php _e( 'How to add a new Case Study', 'thrive' ); ?></strong></p>
	<ol>
		<li><?php _e( 'Click the Add new link under Case Studies on the menu to the left.', 'thrive' ); ?></li>
		<li><?php _e( 'Add a title -- Titles show up underneath the thumbnail image.', 'thrive' ); ?></li>
		<li><?php _e( 'Add a Company Name -- The company name shows up under the title on a case study.', 'thrive' ); ?></li>
		<li><?php _e( 'Attach a Case Study Attachment -- This is the what the case study is "linked to" when the user clicks on the case study.', 'thrive' ); ?>
			<ol>
				<li><?php _e( 'Click on the "Upload/Insert" link. The Media dialog box will appear.', 'thrive' ); ?></li>
				<li><?php _e( 'Click on the button "Select Files" to upload your document. Documents can be any kind of file including image.', 'thrive' ); ?></li>
				<li><?php _e( 'Once uploaded you may change any properties and then click "Save all changes" button.', 'thrive' ); ?></li>
				<li><?php _e( 'Click the "Update" button or "Save Draft" if you have not published the Case Study yet. This will put the document in your gallery.', 'thrive' ); ?></li>
				<li><?php _e( 'If your document does not show up in the Case Study Attachment box then click on the "Upload/Insert" button again.', 'thrive' ); ?></li>
				<li><?php _e( 'This time click on the "Gallery" tab.', 'thrive' ); ?></li>
				<li><?php _e( 'If your document is not the highest Media then drag it so that it is above the rest of the media in the Gallery. Then click "Save all changes" to save.', 'thrive' ); ?></li>
				<li><?php _e( 'Click the "Update" button or "Save Draft" if you have not published the Case Study yet. Now your document should be displayed in the Case Study Attchment box.', 'thrive' ); ?></li>
			</ol>
		</li>
		<li><?php _e( 'Add an Excerpt -- This is the blurb text of the case study. Any HTML will be striped out of this section.', 'thrive' ); ?></li>
		<li><?php _e( 'Select a Category -- This tells the site what service page the case study will also show up on. If no category is selected then the case study will only show up on the Case Studies page.', 'thrive' ); ?></li>
		<li><?php _e( 'Add a Featured Image -- Clicking on the "Set featured image" link to bring up the Media Library. Either upload a file by clicking on "Select files" or choose an image from the Media Library by clicking the "Library" tab. Once the file is uploaded or once the properties of the media are displayed then click on the link "Use as featured image".', 'thrive' ); ?></li>
		<li><?php _e( 'Click "Publish" or "Update" to publish out the changes made.', 'thrive' ); ?></li>
	</ol>
	<p><strong><?php _e( 'How to move a Case Study', 'thrive' ); ?></strong></p>
	<p><?php _e( 'Case Studies are organized by creation date. So newer case studies are displayed before older ones. To force a case study higher then another one:', 'thrive' ); ?></p>
	<ol>
		<li><?php _e( 'Click the Case Studies link under Case Studies on the menu to the left.', 'thrive' ); ?></li>
		<li><?php _e( 'Hover over the distination case study you want the target case study to be above. Then click on "Quick Edit" to display the post&#39;s settings. Note the date and time it says.', 'thrive' ); ?></li>
		<li><?php _e( 'Do the same with the target case study but this time change the date so that it is newer then the desination case study. Click "Update" to save the changes.', 'thrive' ); ?></li>
		<li><?php _e( 'Refresh the page to see the case study move.', 'thrive' ); ?></li>
	</ol>
	<p><strong><?php _e( 'Where does my Case Study show up?', 'thrive' ); ?></strong></p>
	<p><?php _e( 'The case study will show up on the service page (INSIGHT, DESIGN, STRATEGY) it was categorized as and on the CASE STUDIES page.', 'thrive' ); ?></p>
	
	<?php
}
function thrive_help_media() {
	//the content of our custom widget
	?>
	<p><?php _e( 'The Media Library should not be used directly but through the Pages, Case Studies and HP Features functionality of the site. There is a nice feature to allow searching of the Media Library and change the properties but uploading directly into the Media Library should not be done.', 'thrive' ); ?></p>
	<?php
}
function thrive_help_footer() {
	//the content of our custom widget
	?>
	<p><strong><?php _e( 'How to add a new Footer Link', 'thrive' ); ?></strong></p>
	<ol>
		<li><?php _e( 'Click the Add new link under Footer on the menu to the left.', 'thrive' ); ?></li>
		<li><?php _e( 'Add a name -- This will be the text of your link.', 'thrive' ); ?></li>
		<li><?php _e( 'Add a web address -- This is location that the link will take the viewer', 'thrive' ); ?></li>
		<li><?php _e( 'Select a category -- Each categoy refers to a link bin found at the footer of the site. Multiple categories may be selected if a link needs to be duplicated in two different bins.', 'thrive' ); ?></li>
		<li><?php _e( 'Select a rating -- An optional rating may be selected to be able to move a link up or down the link bin list.', 'thrive' ); ?></li>
	</ol>
	<p><strong><?php _e( 'How to change the name of the link bin header', 'thrive' ); ?></strong></p>
	<ol>
		<li><?php _e( 'Click the Footer Categories link under Footer on the menu to the left.', 'thrive' ); ?></li>
		<li><?php _e( 'Hover over the category to change can click the Edit link.', 'thrive' ); ?></li>
		<li><?php _e( 'Make the changes needed and click Update Category.', 'thrive' ); ?></li>
	</ol>
	<p><strong><?php _e( 'How to add a new link bin', 'thrive' ); ?></strong></p>
	<ol>
		<li><?php _e( 'Click the Footer Categories link under Footer on the menu to the left.', 'thrive' ); ?></li>
		<li><?php _e( 'Add a Link Category name -- The name of that will be displayed as the footer link bin title.', 'thrive' ); ?></li>
		<li><?php _e( 'Add a Link Category slug -- This is optional since it is auto generated for you when you save the category.', 'thrive' ); ?></li>
		<li><?php _e( 'Click Add Category to create the link bin.', 'thrive' ); ?></li>
		<li><?php _e( 'Add the links to the new bin.', 'thrive' ); ?></li>
	</ol>
	<?php
}
function thrive_help_appearances() {
	//the content of our custom widget
	?>
	<p><strong><?php _e( 'How to arrange footer link bins and business information', 'thrive' ); ?></strong></p>
	<ol>
		<li><?php _e( 'Click the Widgets link under Appearance on the menu to the left.', 'thrive' ); ?></li>
		<li><?php _e( 'On the right side of the page there will be a Footer Widget Area that contains 5 widgets. Drag and drop the widget where you want it in the list.', 'thrive' ); ?></li>
		<li><?php _e( 'Moving it higher in the list will put the information further to the left on the footer of the site.', 'thrive' ); ?></li>
		<li><?php _e( 'Widget options can be changed by clicking on the down arrow of the widget.', 'thrive' ); ?></li>
		<li><?php _e( 'Other widgets may be dropped into the Footer Widget Area from the Available Widgets area, however, the widgets will wrap if more then five are dropped into the Footer Widgets Area.', 'thrive' ); ?></li>
	</ol>
	<p><strong><?php _e( 'How to add a new header image', 'thrive' ); ?></strong></p>
	<ol>
		<li><?php _e( 'Click the Header link under Appearance on the menu to the left.', 'thrive' ); ?></li>
		<li><?php _e( 'Select the Browse button to find an image to upload for the header. Then click Upload.', 'thrive' ); ?></li>
		<li><?php _e( 'The image should be 335x162. If the image is too large, then an option to crop the image will be displayed.', 'thrive' ); ?></li>
		<li><?php _e( 'The header can be removed including the original header using the Remove Header button and the header can be reset back to its original one using the Restore Original Header Image button.', 'thrive' ); ?></li>
	</ol>
	<?php
}
function thrive_help_settings() {
	//the content of our custom widget
	?>
	<p><strong><?php _e( 'Overall' ); ?></strong></p>
	<p><?php _e( 'The business information and copyright information can be found in the footer and on the contact page can be changed by clicking on the Business Info link under the Settings menu item on the left. The address given in the Business Info will be used for the address on the google maps on the Contact page and the contact Email where the contact form sends inquiries on the Contact page.', 'thrive' ); ?></p>
	<?php
}
/* @end DASHBOARD HELP */
?>