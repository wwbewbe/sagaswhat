<?php // Topics Menu
$location_name = 'topicsmenu';
$locations = get_nav_menu_locations();
$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
if( $myposts ): ?>
<aside class="mymenu mymenu-topics">
<ul>

	<?php foreach($myposts as $post):
	if( $post->object == 'page' ):
	$title = $post->title;//ナビゲーションラベル名
	$post = get_post( $post->object_id );
	setup_postdata($post); ?>
	<li><a href="<?php the_permalink(); ?>">
	<div class="thumb" style="background-image: url(<?php echo mythumb( 'full' ); ?>)"></div>
	<div class="text">
	<?php echo $title; //ナビゲーションラベル名を表示 the_title(); ?>
	</div>
	</a></li>
	<?php endif;
	endforeach; ?>

</ul>
</aside>
<?php wp_reset_postdata();
endif; ?>
