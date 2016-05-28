<?php dynamic_sidebar( 'ad' ); ?>

<?php
$location_name = 'pickupnav';
$locations = get_nav_menu_locations();
$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
if( $myposts ): ?>
<aside class="mymenu mymenu-large">
<h2>注目のイベント</h2>
<ul>

	<?php foreach($myposts as $post):
	if( $post->object == 'post' ):
	$post = get_post( $post->object_id );
	setup_postdata($post); ?>
	<li><a href="<?php the_permalink(); ?>">
	<div class="thumb" style="background-image: url(<?php echo mythumb( 'medium' ); ?>)"></div>
	<div class="text">
	<?php the_title(); ?>
	</div>
	</a></li>
	<?php endif;
	endforeach; ?>

</ul>
</aside>
<?php wp_reset_postdata();
endif; ?>

<?php dynamic_sidebar( 'submenu' ); ?>
