<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

	<h1>"<?php echo get_search_query(); ?>" <?php echo esc_html(__('search results', 'SagasWhat')); ?></h1>

	<?php $closed_imgid = get_closed_img();//イベント終了画像IDをメディアライブラリから取得 ?>

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>

	<?php $post_id = get_the_ID();
	$closedate = get_post_meta($post_id, 'eventclose', true);
	$today = date_i18n("Y/m/d");
	if ($closedate) { //すでにイベントが終了しているときはclosed imageにアイキャッチ画像変更
		if ((strtotime($closedate) < strtotime($today)) && (get_post_thumbnail_id($post_id) != $closed_imgid)) {
			update_post_meta( $post_id, $meta_key = '_thumbnail_id', $meta_value = $closed_imgid );
		}
	} ?>

	<?php if (has_term('', 'keyword', $post_id)) {
		get_template_part( 'gaiyou', 'custom' );
	} else {
		get_template_part( 'gaiyou', 'medium' );

	} ?>

	<?php endwhile; endif; ?>

	<div class="pagination pagination-index">
	<?php echo paginate_links( array( 'type' => 'list',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;'
							 ) ); ?>
	</div>

	<aside class="mymenu-adsense">
	<?php echo get_adsense(); ?>
	</aside>

	<?php if (function_exists('wpfp_list_favorite_posts')) {
		get_template_part( 'favorite', 'events' );
	} //Favorite Events list function?>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
