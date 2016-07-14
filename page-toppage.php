<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">

<?php if( get_header_image() ): ?>
<div class="hero">
	<div class="hero-img" style="background-image: url(<?php header_image(); ?>)"></div>
	<div class="hero-text">
	<?php bloginfo( 'description' ); ?>
	</div>
</div>
<?php endif; ?>

<div class="contents">

	<?php // Page Menu on Top Page
	$location_name = 'pagenav';
	$locations = get_nav_menu_locations();
	$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
	if( $myposts ): ?>
	<aside class="mymenu mymenu-top">
	<ul>

		<?php foreach($myposts as $post):
		if( $post->object == 'page' ):
		$post = get_post( $post->object_id );
		setup_postdata($post); ?>
		<li><a href="<?php the_permalink(); ?>">
		<div class="thumb" style="background-image: url(<?php echo mythumb( 'medium' ); ?>)"></div>
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

	<?php // Upcoming Menu on Top Page
	$location_name = 'upcomingnav';
	$locations = get_nav_menu_locations();
	$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
	if( $myposts ): ?>
	<aside class="mymenu mymenu-large">
	<h2><?php _e('Upcoming Events', 'SagasWhat'); ?></h2>
	<ul>

		<?php foreach($myposts as $post):
		if( $post->object == 'page' ):
		$title = esc_html($post->title);
		$post = get_post( $post->object_id );
		setup_postdata($post); $count++;?>
		<li><a href="<?php the_permalink(); ?>?target=<?php echo $count; ?>">
		<div class="thumb" style="background-image: url(<?php echo get_upcoming_image($count); ?>)"></div>
		<div class="text">
		<?php //echo $title; //the_title(); ?>
		</div>
		</a></li>
		<?php endif;
		endforeach; ?>

	</ul>
	</aside>
	<?php wp_reset_postdata();
	endif; ?>

</div><!-- end contents -->

<div class="sub">
	<?php get_sidebar(); ?>
</div>

</div><!-- end container -->

<?php get_footer(); ?>
