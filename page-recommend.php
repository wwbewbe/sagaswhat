<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

<article <?php post_class( 'kiji' ); ?>>
	<h1><?php _e('Recommended Events', 'SagasWhat'); ?></h1>
<?php
	//  URLのパラメター(緯度と経度の位置情報)を取得
	$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
	$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
?>
<?php if(($lat) && ($lng)) : ?>
	<span class="highlight"><strong><?php _e('* It has sorted events in close order.', 'SagasWhat'); ?></strong></span>
<?php else : ?>
	<button type="button" id="nearnav">
		<i class="fa fa-bars"></i><span><?php _e('Sort in close order', 'SagasWhat'); ?></span>
	</button>
	<p><?php _e('* you can sort events in close oder from here', 'SagasWhat'); ?></p>
<?php endif; ?>
</article>

<?php
if (($lat) && ($lng)) {
	//  距離チェック
	$meta_query_args = get_meta_query_args('4');
	$args = array(
	    'post_type'		=> 'post',		// 投稿
	    'posts_per_page' => '-1',		// 全件表示
		'cat'			=> '-1',		// カテゴリが未分類の記事は非表示
		'meta_query'	=> $meta_query_args,//開催中＆おすすめ度が4以上のイベント
	); ?>
	<?php $the_query = new WP_Query($args); ?>
	<?php
	if($the_query->have_posts()) {
		while($the_query->have_posts()) {
			$the_query->the_post();
			//現在地からイベント場所の距離を算出してデータに追加
			//カスタムフィールドに緯度経度がなければ住所から算出し格納
			$spotLat = esc_html( get_post_meta($post->ID, 'spot_lat', true) );
			$spotLng = esc_html( get_post_meta($post->ID, 'spot_lng', true) );
			if ((!$spotLat) || (!$spotLng)) {
				$address = esc_html( get_post_meta($post->ID, 'address', true) );
				$LatLng = strAddrToLatLng($address);
				$spotLat = $LatLng['Lat'];
				$spotLng = $LatLng['Lng'];
				update_post_meta($post->ID, 'spot_lat', $spotLat);
				update_post_meta($post->ID, 'spot_lng', $spotLng);
			}
		    if (($spotLat) && ($spotLng)) {
		        $distanceLat = $spotLat - $lat;
		        $distanceLng = $spotLng - $lng;
		        // 距離の算出　pow = 乗算 / sqrt = 平方根
		        $distance = sqrt(pow( $distanceLat ,2) + pow( $distanceLng ,2));
		        // 並び替え用の数値として距離「distance」を追加
				update_post_meta($post->ID, 'distance', $distance);
		    }
		}
	}
	wp_reset_postdata();
}
?>

<?php
	//並び替え
	if (($lat) && ($lng)) {
		$meta_query_args = get_meta_query_args('4', 'exists');
		$args = array(
		    'post_type'		=> 'post',		// 投稿
		    'posts_per_page' => '10',		// 10件表示
			'cat'			=> '-1',		// カテゴリが未分類の記事は非表示
			'orderby'		=> array('meta_distance'=>'asc', 'meta_recommend'=>'desc'),//距離の近い順＆おすすめ度の高い順で表示
			'paged'			=> $paged,
			'meta_query'	=> $meta_query_args,//開催中＆おすすめ度が4以上のイベント
		);
	} else {
		$meta_query_args = get_meta_query_args('4');
		$args = array(
		    'post_type'		=> 'post',		// 投稿
		    'posts_per_page' => '10',		// 10件表示
			'cat'			=> '-1',		// カテゴリが未分類の記事は非表示
			'orderby'		=> array('meta_recommend'=>'desc'),//おすすめ度の高い順で表示
			'paged'			=> $paged,
			'meta_query'	=> $meta_query_args,//開催中＆おすすめ度が4以上のイベント
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

</div><!-- end contents -->
<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div><!-- end container -->

<?php get_footer(); ?>
