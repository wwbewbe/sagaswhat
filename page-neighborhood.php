<?php get_header(); ?>

<div class="container">
<?php if( get_header_image() ): ?>
<div class="hero">
	<div class="hero-img" style="background-image: url(<?php header_image(); ?>)"></div>
	<div class="hero-text">
	<?php bloginfo( 'description' ); ?>
	</div>
</div>
<?php endif; ?>

<div class="contents">

<button type="button" id="neighbornav">
	<i class="fa fa-bars"></i><span>NEIGHBORHOOD</span>
</button>
<?php
	/*-------------------------------------------*/
	/*  URLのパラメター(緯度と経度の位置情報)を取得
	/*-------------------------------------------*/
	$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
	$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
	/*-------------------------------------------*/
	/*  距離チェック
	/*-------------------------------------------*/
	/*      現在登録されている城情報を全て取得
	/*-------------------------------------------*/
	$myposts = get_posts( array(
	    'post_type' => 'post',      // カスタム投稿タイプチェックイン
	    'posts_per_page' => -1,     // 全件
		'category' => -1,			// カテゴリが未分類の記事は非表示
	     ) );
	/*      各お城情報について、現在地からの距離を算出してデータに追加
	/*-------------------------------------------*/
	foreach ($myposts as $key => $spot_item) {
		$address = esc_html( get_post_meta($spot_item->ID, 'address', true) );
		$LatLng = strAddrToLatLng($address);
		$spotLat = $LatLng['Lat'];
		$spotLng = $LatLng['Lng'];
	    if (($spotLat) && ($spotLng)) {
	        $distanceLat = $spotLat - $lat;
	        $distanceLng = $spotLng - $lng;
	        // 距離の算出　pow = 乗算 / sqrt = 平方根
	        $distance = sqrt(pow( $distanceLat ,2) + pow( $distanceLng ,2));
	        // 並び替え用の数値として距離「distance」を追加
	        $myposts[$key]->distance = $distance;
	    }
	}
	/*      距離で並び替えるという比較関数を定義
	/*-------------------------------------------*/
	function itemsort_by_distance( $a , $b){
	  //距離を比較
	  $myposts = strcmp( $a->distance , $b->distance );
	  return $myposts;
	}
	/*      比較関数にそって並び替え
	/*-------------------------------------------*/
	usort( $myposts , "itemsort_by_distance" );

	/*-------------------------------------------*/
	/*  並び替えた城情報を出力
	/*-------------------------------------------*/
?>
	<?php foreach ($myposts as $key => $post) : setup_postdata($post); ?>
		<?php get_template_part( 'gaiyou', 'medium' ); ?>
	<?php endforeach; ?>

	<?php wp_reset_postdata(); ?>

	<?php
/*	//  現在地のURLパラメター(緯度と経度の位置情報)を取得
	$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
	$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
		//  距離チェック
		//      現在登録されているイベント情報を全て取得
		$args = array(
	    	'post_type' => 'post',      // カスタム投稿タイプチェックイン
	    	'posts_per_page' => -1,     // 全件
	    	);
		$the_query = new WP_Query($args);
		if($the_query->have_posts()) {
			while($the_query->have_posts()) {
				$the_query->the_post();
			//      イベントのある住所と現在地からの距離を算出してデータに追加
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
	        		$the_query->distance = $distance;
	    		}
			}
		}
		//      距離で並び替えるという比較関数を定義
		function itemsort_by_distance( $a,$b ){
			echo $a->distance.','.$b->distance;
			//距離を比較
 			$the_query = strcmp( $a->distance , $b->distance );
			return $the_query;
		}
		//      比較関数にそって並び替え
		usort( $the_query , "itemsort_by_distance" );

		//  並び替えた記事をリスト表示
		if($the_query->have_posts()) {
			while($the_query->have_posts()) {
				$the_query->the_post();
	    		get_template_part( 'gaiyou', 'medium' );
			}
		} */?>
<!--
		<div class="pagination pagination-index">
			<?php echo paginate_links( array( 'type' => 'list',
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								) ); ?>
		</div>
-->
		<?php wp_reset_postdata(); ?>

	<div class="share">
	<ul>
	<li><a href="https://twitter.com/intent/tweet?text=<?php echo urlencode( get_the_title() . ' - ' . get_bloginfo('name') ); ?>&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;via=sagaswhat"
	onclick="window.open(this.href, 'SNS', 'width=500, height=300, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-tw">
    	<i class="fa fa-twitter"></i>
    	<span>share&nbsp;by</span>&nbsp;Twitter
	</a></li>
	<li><a href="http://www.facebook.com/share.php?u=<?php echo urlencode( get_permalink() ); ?>"
	onclick="window.open(this.href, 'SNS', 'width=500, height=500, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-fb">
    	<i class="fa fa-facebook"></i>
    	<span>share&nbsp;by</span>&nbsp;Facebook
	</a></li>
	<li><a href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink() ); ?>"
	onclick="window.open(this.href, 'SNS', 'width=500, height=500, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-gp">
    	<i class="fa fa-google-plus"></i>
    	<span>share&nbsp;by</span>&nbsp;Google+
	</a></li>
	</ul>
	</div><!-- end share -->
</div><!-- end contents -->
<div class="sub">
	<?php get_sidebar(); ?>
</div><!-- end sub -->
</div><!-- end container -->

<?php get_footer(); ?>
