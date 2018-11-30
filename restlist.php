<div class="taglist">

<?php
//  URLのパラメター(緯度と経度の位置情報)を取得
$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
?>

<?php if($sort) : ?>
	<?php if(($lat) && ($lng)) : ?>
		<p><span class="sort-after"><?php echo esc_html(__('* Resting Spots have been sorted in close order.', 'SagasWhat')); ?></span></p>
	<?php else : ?>
		<button type="button" id="nearnav" onclick="this.style.color='#dddddd', this.style.backgroundColor='#dddddd', this.style.textShadow='1px 1px 1px rgba(255,255,255,0.5),-1px -1px 1px rgba(0,0,0,0.5)'">
			<i class="fa fa-bars fa-fw"></i><?php echo esc_html(__('Sort by Distance', 'SagasWhat')); ?>
		</button>
		<p><span class="sort-before"><?php echo esc_html(__('* Use this to see resting spots.', 'SagasWhat')); ?></span></p>
	<?php endif; ?>
<?php endif; ?>

<?php
if (($lat) && ($lng)) {

	set_event_distance($lat, $lng, 'event', 'sw_rest');//イベント会場までの距離をカスタムフィールドに保存

	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
		'post_type'		=> 'sw_rest',
		'posts_per_page' => $list,		// リスト数を指定
		'orderby'		=> array('meta_distance'=>'asc', 'meta_value_num'=>'asc'),//距離の近い順＆市区町村別に表示
		'meta_key'		=> 'city',		// 市区町村
		'paged'			=> $paged,
		'meta_query'	=> array('meta_distance'=>array(
					'key'		=> 'distance',		//カスタムフィールドの距離データ
					'compare'	=> 'exists',		//距離データのあるイベントをすべて表示
				)),
	);
} else {
	if ($city) {
		$args=array(
		            'post_type'		=> 'sw_rest',
		            'posts_per_page'=> $list,       // リスト数を指定
					'meta_key'		=> 'city',		// 市区町村
					'meta_value'	=> $city,
		);
	} else {
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$args=array(
		            'post_type'		=> 'sw_rest',
		            'posts_per_page'=> $list,       // リスト数を指定
					'orderby'		=> 'meta_value_num',//市区町村の名前順に表示
					'order'			=> 'ASC',
					'meta_key'		=> 'city',		// 市区町村
					'paged'			=> $paged,
		);
	}
}
?>

<?php if ($city): ?>
<script type="text/javascript">
jQuery(function(){
	jQuery("#rest-slide").slideToggle(false);
	jQuery(".restbtn").on("click",function(){
		jQuery("#rest-slide").slideToggle();
		jQuery(".restbtn").toggle();
	});
});
</script>
<div class="restbtn"><span><i class="fa fa-angle-double-right fa-fw"></i></span><?php echo $slidetitle; ?></div>
<div class="restbtn" style="display:none;"><span><i class="fa fa-angle-double-down fa-fw"></i></span><?php echo $slidetitle; ?></div>
<?php endif; ?>
<div id="rest-slide">

<?php $adcount = 0; // アドセンスの挿入位置を決めるための記事数カウント?>

<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

	<?php if ($the_query->post_count > 4 && $adcount == 4): ?>
		<div class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
		</div>
	<?php endif; ?>

	<?php get_template_part( 'gaiyou', 'resting' ); ?>

	<?php $adcount++; ?>

<?php endwhile; endif; ?>

<div class="pagination pagination-index">
<?php echo paginate_links( array( 'type' => 'list',
						'prev_text' => '&laquo;',
						'next_text' => '&raquo;',
						'total'		=> $the_query->max_num_pages
						 ) ); ?>
</div>

	<?php wp_reset_postdata(); ?>

</div>
</div>
