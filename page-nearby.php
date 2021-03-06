<?php get_header(); ?>

<?php
	//  URLのパラメター(緯度と経度の位置情報)を取得
	$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
	$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
?>

<?php if((!$lat) || (!$lng)) : ?>
	<?php get_template_part( 'geolocation', 'js' );//現在地を取得 ?>
<?php endif; ?>

<div class="container">
<div class="contents">

<article <?php post_class( 'kiji' ); ?>>
	<h1><?php the_title(); ?></h1>
<?php if((!$lat) || (!$lng)) : ?>
	<p><?php echo esc_html(__('Checking for fun stuff near you...', 'SagasWhat')); ?></p>
<?php endif; ?>
</article>

<?php if(($lat) && ($lng)) : ?>

	<?php set_event_distance($lat, $lng, 'event', 'post');//イベント会場までの距離をカスタムフィールドに保存 ?>
	<?php if (empty($eventdistance = get_option('event-distance'))) $eventdistance = '0.06'; ?>
	<?php
	//並び替え
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	$meta_query_args = get_meta_query_args('0', $eventdistance);//開催中&約3駅範囲のイベント抽出
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
	    'post_type'		=> 'post',		// カスタム投稿タイプチェックイン
	    'posts_per_page' => '10',		// 10件表示
		'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
		'orderby'		=> array('meta_distance'=>'asc', 'meta_recommend'=>'desc', 'meta_close'=>'asc'),//距離の近い順＆おすすめ度の高い順＆終了日が近い順に表示
		'paged'			=> $paged,
		'meta_query'	=> $meta_query_args,//終了していない&約3Km範囲のイベント抽出
	);
	?>

	<?php $the_query = new WP_Query($args); ?>

	<?php if(!$the_query->found_posts) : ?>
		<p><?php echo esc_html(__('No nearby events found. Please try again later at a different location.', 'SagasWhat')); ?></p>
	<?php else : ?>
		<p><?php echo esc_html(__('Found &quot;', 'SagasWhat')); ?>
		<?php echo ($the_query->found_posts); ?>
		<?php echo esc_html(__('&quot; Nearby Events', 'SagasWhat')); ?>
		<?php if($the_query->found_posts >= 2) echo '<br />'.esc_html__('* Events have been sorted in close order.', 'SagasWhat'); ?>
		</p>
	<?php endif; ?>

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
<?php endif; ?>

<?php get_template_part( 'nearby', 'tic' ); //Nearby TIC list function ?>

<aside class="mymenu-adsense">
	<?php echo get_adsense('infeed'); ?>
</aside>

</div><!-- end contents -->
<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div><!-- end container -->

<?php get_footer(); ?>
