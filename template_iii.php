<?php
/*
	Template Name: About
*/
?>
<?php 
	// fonts needed
	wp_enqueue_script( 'thrive-about-fonts', get_bloginfo( 'template_url' ) . '/js/about_fonts.js', array( 'thrive-global-first' ), '43', true );
?>
<?php get_header(); ?>
		<div id="content" class="container_8">
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="grid_8">
					<h2 class="thrive_title thrive_title_text thrive_separator thrive_tpad29"><?php the_title(); ?></h2> 
					<h3 class="thrive_subtitle"><?php echo get_post_meta( get_the_ID(), '_thrive_subtitle_value', true ); ?></h3>
				</div>
				<div class="grid_3">
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'thrive' ) ); ?>
						</div><!-- .entry-content -->
					</div><!-- #post-## -->
				</div>
				<div class="grid_5 thrive_tpad16"><?php echo do_shortcode('[gallery columns="1" size="full" link="none" icontag="span" itemtag="div"]'); ?></div>
			<?php endwhile; // End the loop. Whew. ?>
			<div class="clear"></div>
		</div>
		<div class="thrive_tpad56"></div>
<?php get_footer() ?>
