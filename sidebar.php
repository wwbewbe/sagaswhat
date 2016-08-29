<?php dynamic_sidebar( 'ad' ); ?>

<?php
$location_name = 'pickupnav';
$locations = get_nav_menu_locations();
$myposts = wp_get_nav_menu_items( $locations[ $location_name ] );
if( $myposts ): ?>
<aside class="mymenu mymenu-large">
<h2><?php echo esc_html(__('Featured Events', 'SagasWhat')); ?></h2>
<ul>

	<?php $closed_imgid = get_closed_img();//イベント終了画像IDをメディアライブラリから取得 ?>
	<?php foreach($myposts as $post):
	if( $post->object == 'post' ):
	$post = get_post( $post->object_id );
	setup_postdata($post); ?>

	<?php $closedate = get_post_meta($post->ID, 'eventclose', true);
	$today = date_i18n("Y/m/d");
	if ($closedate) { //すでにイベントが終了しているときはclosed imageにアイキャッチ画像変更
		if ((strtotime($closedate) < strtotime($today)) && (get_post_thumbnail_id($post->ID) != $closed_imgid)) {
			update_post_meta( $post->ID, $meta_key = '_thumbnail_id', $meta_value = $closed_imgid );
		}
	}
	?>

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

<?php if (is_single()) {
	dynamic_sidebar( 'submenu-post' );
} else {
	dynamic_sidebar( 'submenu-page' );
} ?>
