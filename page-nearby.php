<?php get_header(); ?>

<?php
	//  URLのパラメター(緯度と経度の位置情報)を取得
	$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
	$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
?>
<?php if((!$lat) && (!$lng)) : ?>
<script type="text/javascript">
jQuery(document).ready(function(){
    if (navigator.geolocation) {
        //Geolocationが使える場合、現在の位置情報を取得
        navigator.geolocation.getCurrentPosition(
            //位置情報取得に成功
            function (position) {
                    lat= position.coords.latitude;
                    lng= position.coords.longitude;
					// 緯度・経度をURLパラメータに追加
					location.search = "?lat=" + lat + "&lng=" + lng;
            },
            //位置情報の取得に失敗
            function (error) {
                window.alert("The service could not get your location.");
            } // function (error)
        );
    } else {
        // Geolocationが使えない場合
        window.alert("You can not use this function, because this browser could not get your location.");
    }
});
</script>
<?php endif; ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

<article <?php post_class( 'kiji' ); ?>>
	<h1><?php _e('Nearby Events', 'SagasWhat'); ?></h1>
<?php if((!$lat) && (!$lng)) : ?>
	<p><?php _e('Please wait until get the location information & list Nearby Events...', 'SagasWhat'); ?></p>
<?php endif; ?>
</article>

<?php
if (($lat) && ($lng)) {
	//  距離チェック
	$meta_query_args = get_meta_query_args();
	$args = array(
	    'post_type'		=> 'post',		// カスタム投稿タイプチェックイン
	    'posts_per_page' => '-1',		// 全件表示
		'cat'			=> '-1',		// カテゴリが未分類の記事は非表示
		'meta_key'		=> 'recommend',
		'orderby'		=> 'meta_value_num',
		'paged'			=> $paged,
		'meta_query'	=> $meta_query_args,//開催中のイベント抽出
	); ?>
	<?php $the_query = new WP_Query($args); ?>
	<?php
	if($the_query->have_posts()) {
		while($the_query->have_posts()) {
			$the_query->the_post();
			//現在地からイベント場所の距離を算出してデータに追加
			$address = esc_html( get_post_meta($post->ID, 'address', true) );
			$LatLng = strAddrToLatLng($address);
			$spotLat = $LatLng['Lat'];
			$spotLng = $LatLng['Lng'];
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

<?php if(($lat) && ($lng)) : ?>
<?php
	//並び替え
	$meta_query_args = get_meta_query_args();
	$args = array(
	    'post_type'		=> 'post',		// カスタム投稿タイプチェックイン
	    'posts_per_page' => '10',		// 10件表示
		'cat'			=> '-1',		// カテゴリが未分類の記事は非表示
		'meta_key'		=> 'distance',
		'meta_value'	=> '0.035',
		'meta_compare'	=> '<=',
		'orderby'		=> array('distance'=>'ASC', 'recommend'=>'DESC'),
		'paged'			=> $paged,
		'meta_query'	=> $meta_query_args,//開催中のイベント抽出
	);
?>

	<?php $the_query = new WP_Query($args); ?>

	<?php if(!$the_query->found_posts) : ?>
		<p><?php _e('Nearby Events being held were not found...', 'SagasWhat'); ?></p>
	<?php else : ?>
		<p><?php _e('Nearby Events being held found &quot;'.$the_query->found_posts.'&quot;', 'SagasWhat'); ?></p>
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

</div><!-- end contents -->
<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div><!-- end container -->

<?php get_footer(); ?>
