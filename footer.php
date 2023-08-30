<?php $settings = get_option( 'thrive_global_settings' ); ?>
		
		<div id="footer" class="container_8 thrive_footer">
			<div class="thrive_footer_border grid_8"></div>
			<div class="grid_1 thrive_footer_logo"></div>
			<div class="grid_2 thrive_tpad25 thrive_footer_text">
				<?php 
					$settings = get_option( 'thrive_global_settings' );
					
					if ( ! empty( $settings[ 'copyright' ] ) ) {
						echo $settings[ 'copyright' ];
					}
				?>
			</div>
			 <?php if ( !dynamic_sidebar( 'footer-widget-area' ) ) : ?>
				<!-- FOOTER WIDGETS NOT SET UP YET -->
			 <?php endif; ?>

			<div class="clear"></div>
			<div class="thrive_tpad56"></div>
		</div>
		<?php wp_footer(); ?>
		<script type="text/javascript" language="javascript">llactid=18815</script>
		<script type="text/javascript" language="javascript" src="http://t2.trackalyzer.com/trackalyze.js"></script> 
	</body>
	
</html>