<?php
/*
	Template Name: Service
*/
?>
<?php 
	global $post;
	
	wp_enqueue_script( 'thrive-service', get_bloginfo( 'template_url' ) . '/js/service.js', array( 'jquery', 'json2', 'thrive-global-first' ), '57s', true );
?>
<?php get_header(); ?>
		<div id="content" class="container_8">
			<div class="grid_8 thrive_separator"></div>
			<div class="grid_6">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php
						// set service type by "title"
						switch ( strtoupper( get_the_title() ) ) {
							case 'INSIGHT':
								$banner_style = 'thrive_banner_orange';
								$related_category = 'insight';
							break;
							case 'DESIGN':
								$banner_style = 'thrive_banner_yellow';
								$related_category = 'design';
							break;
							case 'STRATEGY':
								$banner_style = 'thrive_banner_green';
								$related_category = 'strategy';
							break;
						}
					?>
					<div class="grid_6 alpha omega">
						<h2 class="thrive_title thrive_title_text thrive_tpad29"><?php the_title(); ?></h2>
					</div>
					<div class="grid_6 alpha omega thrive_services_lgimg thrive_tpad30">
						<?php
							if( has_post_thumbnail() ) {
								the_post_thumbnail( 'thrive-service-lg' );
								echo '<div class="thrive_subtitle_banner ' . $banner_style . '">';
								echo '<h3 class="thrive_subtitle">' . get_post_meta( get_the_ID(), '_thrive_subtitle_value', true ) . '</h3>';
								echo '</div>';
							}
						?>
					</div>
					<div class="grid_6 alpha omega">
						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="entry-content">
								<?php the_content(); ?>
							</div><!-- .entry-content -->
						</div><!-- #post-## -->
					</div>
				<?php endwhile; // End the loop. Whew. ?>
			</div>
			<div class="grid_2 omega thrive_feature_bx thrive_tpad10">
				<h4>RELATED CASE STUDIES</h4>
				<div class="thrive_tpad27">
					<?php
						$related_posts = get_posts( 'numberposts=3&orderby=rand&category_name=' . $related_category . '&post_type=thrive_case_study' );
						$exclude_ids = array();
						
						foreach( $related_posts as $post ) { setup_postdata( $post );
							$exclude_ids[] = $post->ID ;
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
							<div class="thrive_tpad13">
								<div class="thrive_swapbx thrive_cs_swapbx<?php echo $no_link; ?>">
									<div class="thrive_cs_quote thrive_tpad20<?php echo empty( $category ) ? '' : ' thrive_cs_service_' . $category; ?>">
										<div class="thrive_cs_md_break"></div>
										<p class="thrive_cs_service"><?php echo strtoupper( $category ); ?></p>
										<div class="thrive_cs_blurb">
											<?php the_excerpt(); ?>
										</div>
										<div class="thrive_cs_sm_break"></div>
										<div class="thrive_tpad7"></div>
										<p class="thrive_cs_link"><a href="<?php echo $cslink; ?>" class="<?php echo $no_link; ?>">view the case study</a></p>
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
						<?php
						}
						wp_reset_query();
					?>
				</div>
			</div>
			<div class="grid_8 thrive_tpad53"></div>
			<?php
				global $wp_query;
				
				$cs_query = get_posts( array( 'name' => 'casestudies', 'posts_per_page' => 1, 'post_type' => 'page' ) );
				$has_cs_page = ( 1 == count( $cs_query ) );
				$cs_permalink = ( $has_cs_page ) ? get_permalink( $cs_query[ 0 ]->ID ) : '';
				wp_reset_query();
				
				$page_size = 4;
				$columns = 4;
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				query_posts( array( 'post_type' => 'thrive_case_study', 'posts_per_page' => $page_size, 'paged' => $paged, 'post__not_in' => $exclude_ids ) );
				$max_pages = $wp_query->max_num_pages;
				$i = 1;
			?>
			<div class="grid_8 thrive_gallery_bx thrive_separator">
				<div class="grid_6 alpha"><h4>OTHER CASE STUDIES</h4></div>
				<div class="grid_2 omega">
					<div class="thrive_pagination thrive_pagination_full thrive_tpad25">
						<div id="thrive_gallery_wait_indicator" class="thrive_gallery_load"></div>
						<?php if ( $has_cs_page ) { ?>
							<a class="thrive_pagination_permalink" href="<?php echo $cs_permalink; ?>"></a>
						<?php } ?>
						<div class="thrive_no_js thrive_pagination_arrows">
							<a id="thrive_prev" href="#prev-page"><div class="thrive_pagination_prev_disabled"></div></a>
							<?php if ( 1 == $max_pages ) { ?>
								<a id="thrive_next" href="#next-page"><div class="thrive_pagination_next_disabled"></div></a>
							<?php } else { ?>
								<a id="thrive_next" href="#next-page"><div class="thrive_pagination_next"></div></a>
							<?php } ?>
						</div>
						<div class="thrive_clear"></div>
					</div>
				</div>
				<div class="thrive_clear"></div>
				<div id="thrive_gallery_viewport" class="thrive_gallery_viewport">
					<div id="thrive_gallery_slides" class="thrive_gallery_slides">
						<div id="thrive_gallery_content_1" class="thrive_tpad30 thrive_gallery_slide">
							<?php						
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
								?>
									<div class="grid_2<?php echo $grid_mod; ?>">
										<div class="thrive_swapbx thrive_cs_swapbx<?php echo $no_link; ?>">
											<div class="thrive_cs_quote thrive_tpad20<?php echo empty( $category ) ? '' : ' thrive_cs_service_' . $category; ?>">
												<div class="thrive_cs_md_break"></div>
												<p class="thrive_cs_service"><?php echo strtoupper( $category ); ?></p>
												<div class="thrive_cs_blurb">
													<?php the_excerpt(); ?>
												</div>
												<div class="thrive_cs_sm_break"></div>
												<div class="thrive_tpad7"></div>
												<p class="thrive_cs_link"><a href="<?php echo $cslink; ?>" class="<?php echo $no_link; ?>">view the case study</a></p>
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
								<?php
									$i++;
								endwhile;
							?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="thrive_tpad56"></div>
		<?php
			// Add the Thrive js object here since we need it to be after the exlude list is generated and the max pages for the gallery area.
			wp_localize_script( 'thrive-service', 'Thrive', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'gallery_exclude' => implode( ',', $exclude_ids ), 'gallery_page' => 1, 'max_pages' => $max_pages ) );
		?>
<?php get_footer() ?>