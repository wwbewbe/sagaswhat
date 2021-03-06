<?php get_header(); ?>

<div class="container">

<?php if( get_header_image() ): ?>
<div class="hero">
	<div class="hero-img" style="background-image: url(<?php header_image(); ?>)"></div>
	<div class="hero-text">
	<?php bloginfo( 'description' ); ?>
	</div>
</div>
<?php endif; ?>

<div class="contents contents-top">

	<?php get_template_part( 'topics', 'menu' ); //Topics Menu ?>

	<div class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
	</div>

	<?php // Page Menu on Top Page
	$location_name = 'pagenav';
	$locations = get_nav_menu_locations();
	$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
	if( $myposts ): ?>
	<aside class="mymenu mymenu-yoko">
	<ul>

		<?php foreach($myposts as $post):
		if( $post->object == 'page' ):
		$post = get_post( $post->object_id );
		setup_postdata($post); ?>
		<li><a href="<?php the_permalink(); ?>">
		<div class="thumb" style="background-image: url(<?php echo mythumb( 'full' ); ?>)"></div>
		<div class="text">
		<h2><?php the_title(); ?></h2>
		<?php the_excerpt(); ?>
		</div>
		</a></li>
		<?php endif;
		endforeach; ?>

	</ul>
	</aside>
	<?php wp_reset_postdata();
	endif; ?>

	<?php // Calendar Menu on Top Page
	$location_name = 'calendarnav';
	$locations = get_nav_menu_locations();
	$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
	if( $myposts ): ?>
	<aside class="mymenu mymenu-large-sep mymenu-large">
	<ul>

		<?php foreach($myposts as $post):
		if( $post->object == 'page' ):
		$post = get_post( $post->object_id );
		setup_postdata($post); ?>
		<li><a href="<?php the_permalink(); ?>">
		<div class="thumb" style="background-image: url(<?php echo mythumb( 'full' ); ?>)"></div>
		<div class="text">
		<?php the_excerpt(); ?>
		</div>
		</a></li>
		<?php endif;
		endforeach; ?>

	</ul>
	</aside>
	<?php wp_reset_postdata();
	endif; ?>

	<?php get_template_part( 'nearby', 'events' ); //Nearby Events list function ?>

	<?php get_template_part( 'nearby', 'tic' ); //Nearby TIC list function ?>

	<aside class="mymenu-adsense">
	<?php echo get_adsense('infeed'); ?>
	</aside>

	<?php // Upcoming Menu on Top Page
	$location_name = 'upcomingnav';
	$locations = get_nav_menu_locations();
	$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
	if( $myposts ): ?>
	<aside class="mymenu mymenu-large">
	<h2><?php echo esc_html(__('Upcoming Events', 'SagasWhat')); ?></h2>
	<ul>

		<?php foreach($myposts as $post):
		if( $post->object == 'page' ):
		$post = get_post( $post->object_id );
		setup_postdata($post); $count++;?>
		<li><a href="<?php the_permalink(); ?>?target=<?php echo $count; ?>">
		<div class="thumb" style="background-image: url(<?php echo get_upcoming_image($count); ?>)"></div>
		<div class="text">
		<?php //the_title(); ?>
		</div>
		</a></li>
		<?php endif;
		endforeach; ?>

	</ul>
	</aside>
	<?php wp_reset_postdata();
	endif; ?>

</div><!-- end contents -->

<div class="sub sub-top">
	<?php get_sidebar(); ?>
</div>

</div><!-- end container -->

<?php get_footer(); ?>
