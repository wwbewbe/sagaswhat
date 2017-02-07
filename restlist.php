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

	set_event_distance($lat, $lng, 0, 'sw_rest');//イベント会場までの距離をカスタムフィールドに保存

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
	$args=array(
	            'post_type'		=> 'sw_rest',
	            'posts_per_page'=> $list,       // リスト数を指定
				'orderby'		=> 'meta_value_num',//市区町村の名前順に表示
				'meta_key'		=> 'city',		// 市区町村
				'paged'			=> $paged,
	);
}
?>

<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

  <?php get_template_part( 'gaiyou', 'resting' ); ?>

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
