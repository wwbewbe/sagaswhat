<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

<article <?php post_class( 'kiji' ); ?>>
	<h1><?php _e('Recommended Events Select 10', 'SagasWhat'); ?></h1>
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
	//  距離チェック
	$myposts = get_posts( array(
	    'post_type'		=> 'post',		// カスタム投稿タイプチェックイン
	    'posts_per_page' => '10',		// 10件表示
		'cat'			=> '-1',		// カテゴリが未分類の記事は非表示
		'meta_key'		=> 'recommend',
		'orderby'		=> 'meta_value_num',
		'meta_query'	=> array(
			'relation'		=> 'OR',
			array(
				'relation'		=> 'AND',
				array(
					'key'		=> 'eventclose',
					'compare'	=> 'NOT EXISTS',
				),
				array(
					'key'		=> 'eventopen',			//カスタムフィールドのイベント開催日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント開催日を今日と比較
					'compare'	=> '<=',				//今日以前なら表示
				),
			),
			array(
				'relation'		=> 'AND',
				array(
					'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
					'compare'	=> '>=',				// 今日以降なら表示
				),
				array(
					'key'		=> 'eventopen',
					'compare'	=> 'NOT EXISTS',
				),
			),
			array(
				'reration'		=> 'AND',
				array(
					'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
					'compare'	=> '>=',				// 今日以降なら表示
				),
				array(
					'key'		=> 'eventopen',			//カスタムフィールドのイベント開催日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント開催日を今日と比較
					'compare'	=> '<=',				//今日以前なら表示
				),
			),
		),
	) );
	//      現在地からイベント場所の距離を算出してデータに追加
	foreach ($myposts as $key => $spot_item) {
		$address = esc_html( get_post_meta($spot_item->ID, 'address', true) );
		$LatLng = strAddrToLatLng($address);
		$spotLat = $LatLng['Lat'];
		$spotLng = $LatLng['Lng'];
	    if (($spotLat) && ($spotLng) && ($lat) && ($lng)) {
	        $distanceLat = $spotLat - $lat;
	        $distanceLng = $spotLng - $lng;
	        // 距離の算出　pow = 乗算 / sqrt = 平方根
	        $distance = sqrt(pow( $distanceLat ,2) + pow( $distanceLng ,2));
	        // 並び替え用の数値として距離「distance」を追加
//			update_post_meta($spot_item->ID, 'distance', $distance);
	        $myposts[$key]->distance = $distance;
	    } else {
			$myposts[$key]->distance = 0;
		}
	}
	//      距離で並び替えるという比較関数を定義
	function itemsort_by_distance( $a , $b){
		//距離を比較
		$myposts = strcmp( $a->distance , $b->distance );
		return $myposts;
	}
	//      比較関数にそって並び替え
	usort( $myposts , "itemsort_by_distance" );

	//  並び替えたイベント情報を出力
?>
	<?php foreach ($myposts as $post) : setup_postdata($post); ?>
		<?php get_template_part( 'gaiyou', 'medium' ); ?>
	<?php endforeach; ?>

	<?php wp_reset_postdata(); ?>

</div><!-- end contents -->
<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div><!-- end container -->

<?php get_footer(); ?>
