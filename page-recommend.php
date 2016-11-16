<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

<article <?php post_class( 'kiji' ); ?>>
	<h1><?php the_title(); ?></h1>
	<?php
		//  URLのパラメター(緯度と経度の位置情報)を取得
		$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
		$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
	?>
	<?php if(($lat) && ($lng)) : ?>
		<p><span class="sort-after"><?php echo esc_html(__('* Events have been sorted in close order.', 'SagasWhat')); ?></span></p>
	<?php else : ?>
		<button type="button" id="nearnav" onclick="this.style.color='#dddddd', this.style.backgroundColor='#dddddd', this.style.textShadow='1px 1px 1px rgba(255,255,255,0.5),-1px -1px 1px rgba(0,0,0,0.5)'">
			<i class="fa fa-bars fa-fw"></i><?php echo esc_html(__('Sort by Distance', 'SagasWhat')); ?>
		</button>
		<p><span class="sort-before"><?php echo esc_html(__('* Use this to see nearby events.', 'SagasWhat')); ?></span></p>
	<?php endif; ?>
</article>

<?php
	//並び替え
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	if (($lat) && ($lng)) {

		set_event_distance($lat, $lng);//イベント会場までの距離をカスタムフィールドに保存

		$meta_query_args = get_meta_query_args('4', 'exists');
		$args = array(
		    'post_type'		=> 'post',		// 投稿
		    'posts_per_page' => '10',		// 10件表示
			'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
			'orderby'		=> array('meta_distance'=>'asc', 'meta_recommend'=>'desc', 'meta_close'=>'asc'),//距離の近い順＆おすすめ度の高い順＆終了日が近い順に表示
			'paged'			=> $paged,
			'meta_query'	=> $meta_query_args,//終了していない＆おすすめ度が4以上＆距離情報があるイベント
		);
	} else {
		$meta_query_args = get_meta_query_args('4');
		$args = array(
		    'post_type'		=> 'post',		// 投稿
		    'posts_per_page' => '10',		// 10件表示
			'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
			'orderby'		=> array('meta_recommend'=>'desc', 'meta_close'=>'asc'),//おすすめ度の高い順＆終了日が近い順に表示
			'paged'			=> $paged,
			'meta_query'	=> $meta_query_args,//終了していない＆おすすめ度が4以上のイベント
		);
	}
?>
	<?php $the_query = new WP_Query($args); ?>

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); //  並び替えたイベント情報を出力?>

		<?php get_template_part( 'gaiyou', 'medium' ); ?>

	<?php endwhile; endif; ?>

	<div class="pagination pagination-index">
	<?php echo paginate_links( array( 'type' => 'list',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
							'total'		=> $the_query->max_num_pages
							 ) ); ?>
	</div>

	<?php wp_reset_postdata(); ?>

	<aside class="mymenu-adsense">
	<?php echo get_adsense(); ?>
	</aside>

	<?php if (function_exists('wpfp_list_favorite_posts')) {
		get_template_part( 'favorite', 'events' );
	} //Favorite Events list function?>

</div><!-- end contents -->
<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div><!-- end container -->

<?php get_footer(); ?>
