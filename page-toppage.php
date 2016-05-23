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

<div class="contents">
	<?php
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
</div>

<div class="sub">
	<aside class="mymenu mymenu-news">
	<h2>お知らせ</h2>
	<?php wp_nav_menu( array( 'theme_location' => 'newsnav' ) ); ?>
	</aside>
</div>

</div>

<?php get_footer(); ?>
