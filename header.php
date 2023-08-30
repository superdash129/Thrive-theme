<?php
	wp_enqueue_script( 'thrive-global-first', get_bloginfo( 'template_url' ) . '/js/global_first.js', array( 'jquery' ), '0', false );
	wp_enqueue_script( 'thrive-global-last', get_bloginfo( 'template_url' ) . '/js/global_last.js', array( 'jquery' ), '43', true );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" profile="http://www.w3.org/2005/10/profile">
	<head>
		<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico?20110207" />
		<title><?php 
			$title = get_bloginfo( 'name' );
			$title = ( is_page( 'insight' ) ) ? __( 'THRIVE - Design Research', 'thrive' ) : $title;
			$title = ( is_page( 'strategy' ) ) ? __( 'THRIVE - Innovation Strategy', 'thrive' ) : $title;
			$title = ( is_page( 'design' ) ) ? __( 'THRIVE - New Product Development', 'thrive' ) : $title;
			$title = ( is_page( 'casestudies' ) ) ? __( 'THRIVE - Our Work', 'thrive' ) : $title;
			$title = ( is_page( 'experience' ) ) ? __( 'THRIVE - Our Experience', 'thrive' ) : $title;
			$title = ( is_page( 'about' ) ) ? __( 'THRIVE - How We Work', 'thrive' ) : $title;
			$title = ( is_page( 'contact' ) ) ? __( 'THRIVE - Let\'s Talk', 'thrive' ) : $title;
			echo $title;
		?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=960" />
		
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
		<noscript>
			<style type="text/css">
				.thrive_no_js { display:none !important; }
			</style>
		</noscript>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class( 'bg_border_top' ); ?>>
		<div id="header" class="container_8">
			<div class="grid_3"><a href="<?php bloginfo( 'url' ); ?>"><img class="thrive_util_left" src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="Thrive TM" title="Home" /></a></div>
			<div class="grid_5 thrive_tpad62 thrive_menu_zfix">
				<div id="access" role="navigation" class="thrive_menu_zfix">
					<div class="skip-link screen-reader-text">
						<a href="#content" title="<?php esc_attr_e( 'Skip to content', 'thrive' ); ?>"><?php _e( 'Skip to content', 'thrive' ); ?></a>
					</div>
					<!-- menu -->
					<?php $menu_pages = thrive_have_page( array( 'insight', 'strategy', 'design', 'casestudies', 'experience', 'about', 'contact'  ) ); ?>
					<div class="menu thrive_primary_menu thrive_menu_zfix">
						<ul class="thrive_menu_zfix">
							<li class="<?php echo is_front_page() ? 'thrive_menu_selected' : ''; ?>">
								<div class="thrive_corners">
									<div class="thrive_corners_top_left"></div>
									<div class="thrive_corners_bottom_left"></div>
								</div>
								<a href="<?php echo home_url(); ?>" >HOME</a>
								<div class="thrive_corners">
									<div class="thrive_corners_top_right"></div>
									<div class="thrive_corners_bottom_right"></div>
								</div>
								<div class="thrive_clear"></div>
								<span class="thrive_menu_hover"></span>
							</li>
							
							<?php if( $menu_pages[ 'insight' ] || $menu_pages[ 'design' ] || $menu_pages[ 'strategy' ] ): ?>
							<li class="thrive_menu_zfix <?php echo is_page( array( 'insight', 'design', 'strategy' ) ) ? 'thrive_menu_selected' : ''; ?>">
								<div class="thrive_corners">
									<div class="thrive_corners_top_left"></div>
									<div class="thrive_corners_multimenu_bottom_left"></div>
								</div>
								<a href="#" id="current" class="no_link">SERVICES</a>
								<div class="thrive_corners">
									<div class="thrive_corners_top_right"></div>
									<div class="thrive_corners_multimenu_bottom_right"></div>
								</div>
								<div class="thrive_clear"></div>
								<div class="thrive_multimenu_hover"></div>
								<ul class="thrive_sub_menu">
									<li><div class="thrive_sub_menu_top"></div></li>
									<?php if ( $menu_pages[ 'insight' ] ): ?><li><a href="<?php echo home_url( 'insight' ); ?>">Insight</a></li><?php endif; ?>
									<?php if ( $menu_pages[ 'strategy' ] ): ?><li><a href="<?php echo home_url( 'strategy' ); ?>">Strategy</a></li><?php endif; ?>
									<?php if ( $menu_pages[ 'design' ] ): ?><li><a href="<?php echo home_url( 'design' ); ?>">Design</a></li><?php endif; ?>
									<li><a href="#" class="thrive_sub_menu_bottom"></a></li>
								</ul>
							</li>
							<?php endif; ?>
							
							<?php if ( $menu_pages[ 'casestudies' ] ): ?>
							<li <?php echo is_page('casestudies') ? 'class="thrive_menu_selected"' : ''; ?>>
								<div class="thrive_corners">
									<div class="thrive_corners_top_left"></div>
									<div class="thrive_corners_bottom_left"></div>
								</div>
								<a href="<?php echo home_url( 'casestudies' ); ?>">CASE STUDIES</a>
								<div class="thrive_corners">
									<div class="thrive_corners_top_right"></div>
									<div class="thrive_corners_bottom_right"></div>
								</div>
								<div class="thrive_clear"></div>
								<div class="thrive_menu_hover"></div>
							</li>
							<?php endif; ?>
							
							<?php if ( $menu_pages[ 'experience' ] ): ?>
							<li <?php echo is_page('experience') ? 'class="thrive_menu_selected"' : ''; ?>>
								<div class="thrive_corners">
									<div class="thrive_corners_top_left"></div>
									<div class="thrive_corners_bottom_left"></div>
								</div>
								<a href="<?php echo home_url( 'experience' ); ?>">EXPERIENCE</a>
								<div class="thrive_corners">
									<div class="thrive_corners_top_right"></div>
									<div class="thrive_corners_bottom_right"></div>
								</div>
								<div class="thrive_clear"></div>
								<div class="thrive_menu_hover"></div>
							</li>
							<?php endif; ?>
							
							<?php if ( $menu_pages[ 'about' ] ): ?>
							<li <?php echo is_page_template('template_iii.php') ? 'class="thrive_menu_selected"' : ''; ?>>
								<div class="thrive_corners">
									<div class="thrive_corners_top_left"></div>
									<div class="thrive_corners_bottom_left"></div>
								</div>
								<a href="<?php echo home_url( 'about' ); ?>">ABOUT</a>
								<div class="thrive_corners">
									<div class="thrive_corners_top_right"></div>
									<div class="thrive_corners_bottom_right"></div>
								</div>
								<div class="thrive_clear"></div>
								<div class="thrive_menu_hover"></div>
							</li>
							<?php endif; ?>
							
							<?php if ( $menu_pages[ 'contact' ] ): ?>
							<li <?php echo is_page('contact') ? 'class="thrive_menu_selected"' : ''; ?>>
								<div class="thrive_corners">
									<div class="thrive_corners_top_left"></div>
									<div class="thrive_corners_bottom_left"></div>
								</div>
								<a href="<?php echo home_url( 'contact' ); ?>">CONTACT</a>
								<div class="thrive_corners">
									<div class="thrive_corners_top_right"></div>
									<div class="thrive_corners_bottom_right"></div>
								</div>
								<div class="thrive_clear"></div>
								<div class="thrive_menu_hover"></div>
							</li>
							<?php endif; ?>
						</ul>
					</div>
					<!-- /menu -->
				</div><!-- #access -->
			</div>
			<div class="clear"></div>
		</div>