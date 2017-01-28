<?php // Nearby TIC list on each Post
$location_name = 'nearbyticnav';
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
