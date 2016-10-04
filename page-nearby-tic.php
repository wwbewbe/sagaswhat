<?php get_header(); ?>

<?php
	//  URLのパラメター(緯度と経度の位置情報)を取得
	$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
	$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
?>

<?php if((!$lat) || (!$lng)) : ?>
	<?php get_template_part( 'geolocation', 'js' );//現在地を取得 ?>
<?php endif; ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

<article <?php post_class( 'kiji' ); ?>>
	<h1><?php echo esc_html(__('Nearby Centers', 'SagasWhat')); ?></h1>
<?php if((!$lat) || (!$lng)) : ?>
	<p><?php echo esc_html(__('Checking for TIC near you...', 'SagasWhat')); ?></p>
<?php endif; ?>
</article>

<?php if(($lat) && ($lng)) : ?>

	<?php set_event_distance($lat, $lng, 'tic');//TICまでの距離をTIC記事のカスタムフィールドに保存 ?>

	<?php
	//並び替え
	$infocat = get_category_by_slug('tourist-info-center');
	$args = array(
	    'post_type'		=> 'post',		// カスタム投稿タイプチェックイン
	    'posts_per_page' => '10',		// 10件表示
		'cat' => $infocat->cat_ID, 		// カテゴリが観光案内所の記事を表示
		'orderby'		=> array('meta_distance'=>'asc'),//距離の近い順で表示
		'paged'			=> $paged,
		'meta_query'	=> array(
				'meta_distance'=>array(
					'key'		=> 'distance',		//カスタムフィールドの距離データ
					'value'		=> '0.06',			//約3駅範囲の観光案内所を抽出
					'compare'	=> '<=',			//指定距離内のイベントを表示
					'type'		=> 'char',			//タイプに数値を指定
				)),
	);
	?>

	<?php $the_query = new WP_Query($args); ?>

	<?php if(!$the_query->found_posts) : ?>
		<p><?php echo esc_html(__('No nearby TICs found. Please try again later at a different location.', 'SagasWhat')); ?></p>
	<?php else : ?>
		<p><?php echo esc_html(__('Found &quot;', 'SagasWhat')); ?>
		<?php echo ($the_query->found_posts); ?>
		<?php echo esc_html(__('&quot; Nearby TICs', 'SagasWhat')); ?></p>
	<?php endif; ?>

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); //  並び替えたイベント情報を出力?>

		<?php get_template_part( 'gaiyou', 'medium-tic' ); ?>

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

<?php get_template_part( 'list', 'tic' ); //TIC list ?>

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
