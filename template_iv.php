<?php
/*
	Template Name: Homepage
*/
?>
<?php 
	// fonts needed
	wp_enqueue_script( 'thrive-homepage-first', get_bloginfo( 'template_url' ) . '/js/homepage-first.js', array( 'thrive-global-first', 'jquery' ), '43', false );
	wp_enqueue_script( 'thrive-homepage', get_bloginfo( 'template_url' ) . '/js/homepage.js', array( 'jquery' ), '43', true );
?>
<?php get_header(); ?>
		<div class="container_8">
			<div class="grid_8 thrive_separator"></div>
			<div class="grid_8 thrive_tpad33">
				<div class="thrive_hpsldr_wrap">
					<div class="simpleSlide-window" rel="1">
						<div class="left-button thrive_hpsldr_left_btn" rel="1"></div>
						<div class="simpleSlide-tray" rel="1">
							<?php
								$t1gallery_query = new WP_Query();
								$t1gallery = $t1gallery_query->query( array(
									'orderby' => 'menu_order date',
									'order' => 'ASC',
									'post_type' => 'thrive_homepage',
									'meta_key' => '_thrive_hp_type_value',
									'meta_value' => 'GAL'
								) );
							?>
							<?php if ( $t1gallery_query->have_posts() ) { $cnt = 0; ?>
								<?php while ( $t1gallery_query->have_posts() ) { ?>
									<?php 
										$t1gallery_query->the_post();
										$cnt++;
										$categories = get_the_category();
										$category = empty( $categories ) ? '' : $categories[ 0 ]->category_nicename;
										switch( $category ) {
											case 'design': $slide_skin = ' thrive_hpgal_yellow'; break;
											case 'insight': $slide_skin = ' thrive_hpgal_orange'; break;
											case 'strategy': $slide_skin = ' thrive_hpgal_green'; break;
											default: $slide_skin='';
										}
										
										$raw_content = strip_tags( apply_filters( 'the_content', get_the_content() ), '<a><li>' );
										if ( 1 == preg_match( '@<li>(.+)</li>@', $raw_content, $r ) ) {
											$read_more = $r[ 1 ];
										}
										
										$read_more_link = '';
										if ( 1 == preg_match( '@<a([^>]+)>[^<]+</a>@', $read_more, $r2 ) ) {
											if ( 1 == preg_match( '@href="([^"]+)"@', $r2[ 1 ], $r3 ) ) {
												$read_more_link = $r3[ 1 ];
											}
										}

										$blurb = strip_tags( str_replace( $read_more, '', $raw_content ) );
									?>
									<div class="simpleSlide-slide" rel="1" alt="<?php echo $cnt; ?>">
										<div class="thrive_hpsldr_sld" rel="1">
											<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'thrive-homepage-gal' ); } ?>
											<div class="thrive_hpgal_box<?php echo $slide_skin; ?>">
												<div class="thrive_tpad52"><div class="thrive_hpgal_md_break"></div></div>
												<div class="thrive_hpgal_title thrive_tpad20"><?php the_title(); ?></div>
												<div class="thrive_hpgal_blurb thrive_tpad11"><?php echo $blurb; ?></div>
												<div class="thrive_tpad41">
													<div class="thrive_hpgal_more"><?php echo $read_more; ?></div>
													<div class="thrive_tpad2">
														<?php if ( ! empty( $read_more_link ) ) { echo '<a href="' . $read_more_link . '">'; } ?>
														<div class="thrive_arrow"></div>
														<?php if ( ! empty( $read_more_link ) ) { echo '</a>'; } ?>
														<div class="clear"></div>
													</div>
													<div class="clear"></div>
												</div>
												<div class="thrive_tpad27"><div class="thrive_hpgal_sm_break"></div></div>
											</div>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
							<?php 
								$max_pages = $cnt;
								wp_reset_query(); 
							?>
						</div>
						<div class="right-button thrive_hpsldr_right_btn" rel="1"></div>
					</div> 
				</div>
				<div class="clear"></div>
				<div class="grid_8 alpha omega thrive_tpad15">
					<div class="thrive_center_block">
						<ul id="thrive_hpsldr_nav" class="thrive_hpsldr_nav">
						<?php for( $i = 1; $i <= $cnt; $i++ ) { $cls = ( 1 == $i ) ? ' thrive_sldr_selector_selected' : ''; ?>
							<li class="jump-to thrive_sldr_selector<?php echo $cls; ?>" rel="1" alt="<?php echo $i; ?>"></li>
						<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div class="thrive_tpad18"></div>
			<div class="grid_8 thrive_separator"></div>
		</div>
		<div id="content" class="container_3 thrive_hp_feature thrive_tpad35">
			<?php 
				$features_query = new WP_Query();
				$features = $features_query->query( array(
					'posts_per_page' => 3,
					'orderby' => 'menu_order date',
					'order' => 'ASC',
					'post_type' => 'thrive_homepage',
					'meta_key' => '_thrive_hp_type_value',
					'meta_value' => 'FEA'
				) ); 
			?>
			<?php if ( $features_query->have_posts() ) { ?>
				<?php while ( $features_query->have_posts() ) { $features_query->the_post(); ?>
					<?php
						$read_more = '';
						
						$raw_content = strip_tags( apply_filters( 'the_content', get_the_content() ), '<a><li>' );
						if ( 1 == preg_match( '@<li>(.+)</li>@', $raw_content, $r ) ) {
							$read_more = $r[ 1 ];
						}
						
						$read_more_link_fea = '';
						if ( 1 == preg_match( '@<a([^>]+)>[^<]+</a>@', $read_more, $r2 ) ) {
							if ( 1 == preg_match( '@href="([^"]+)"@', $r2[ 1 ], $r3 ) ) {
								$read_more_link_fea = $r3[ 1 ];
							}
						}
									
						$blurb = strip_tags( str_replace( $read_more, '', $raw_content ) );
					?>
					<div class="grid_1">
						<?php 
							if ( has_post_thumbnail() ) { 
								if ( ! empty( $read_more_link_fea ) ) { echo '<a href="' . $read_more_link_fea . '">'; }
								the_post_thumbnail( 'thrive-homepage-fea' ); 
								if ( ! empty( $read_more_link_fea ) ) { echo '</a>'; }
							} 
						?>
						<h3 class="thrive_hpfea_title thrive_tpad10"><?php the_title(); ?></h3>
						<div class="thrive_hpfea_blurb thrive_tpad8"><?php echo $blurb; ?></div>
						<div class="thrive_hpfea_more thrive_tpad2">&#8212; <?php echo $read_more; ?></div>
					</div>
				<?php } ?>
			<?php } ?>
			<?php wp_reset_query(); ?>
			
			<div class="clear"></div>
		</div>
	<div class="thrive_tpad56"></div>
	<?php
		wp_localize_script( 'thrive-homepage', 'Thrive', array( 'can_hover' => true, 'max_pages' => $max_pages ) );
	?>
<?php get_footer() ?>
