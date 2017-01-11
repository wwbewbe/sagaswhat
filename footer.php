<footer>
<div class="footer-inner">

	<?php get_template_part('snsbtn'); // add SNS button ?>

	<div class="copyright">
	<p><?php bloginfo( 'description' ); ?></p>
	<p>&copy; 2016-2017 <?php bloginfo( 'name' ); ?></p>
	</div>
</div>
</footer>

<?php wp_footer(); ?>

<p id="floating-menu">

	<?php // Floating Menu
	$location_name = 'floatingmenu';
	$locations = get_nav_menu_locations();
	$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
	if( $myposts ): ?>

		<?php foreach($myposts as $post):
		if( $post->object == 'page' ):
		$post = get_post( $post->object_id );
		setup_postdata($post); ?>

			<?php if ($post->post_name == 'nearby'): ?>
				<a href="<?php the_permalink(); ?>"><i class="fa fa-map-marker fa-fw"></i><?php echo esc_html__('Nearby Events', 'SagasWhat'); ?></a>
			<?php endif; ?>
			<?php if ($post->post_name == 'nearby-tic'): ?>
				<a href="<?php the_permalink(); ?>"><i class="fa fa-info-circle fa-fw"></i><?php echo esc_html__('Nearby TICs', 'SagasWhat'); ?></a>
			<?php endif; ?>
			<?php if ($post->post_name == 'nearby-park'): ?>
				<a href="<?php the_permalink(); ?>"><i class="fa fa-tree fa-fw"></i><?php echo esc_html__('Nearby Parks', 'SagasWhat'); ?></a>
			<?php endif; ?>

		<?php endif;
		endforeach; ?>

	<?php endif; ?>

</p>
</body>
</html>
