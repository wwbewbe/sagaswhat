<?php get_header(); ?>

<div class="container">
<div class="contents">

	<h1><?php the_title(); ?></h1>

	<?php
		//  URLのパラメター(緯度と経度の位置情報)を取得
		$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
		$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
	?>
	<?php if(($lat) && ($lng)) : ?>
		<p><span class="sort-after"><?php echo esc_html(__('* TICs have been sorted in close order.', 'SagasWhat')); ?></span></p>
	<?php else : ?>
		<button type="button" id="nearnav" onclick="this.style.color='#dddddd', this.style.backgroundColor='#dddddd', this.style.textShadow='1px 1px 1px rgba(255,255,255,0.5),-1px -1px 1px rgba(0,0,0,0.5)'">
			<i class="fa fa-bars fa-fw"></i><?php echo esc_html(__('Sort by Distance', 'SagasWhat')); ?>
		</button>
		<p><span class="sort-before"><?php echo esc_html(__('* Use this to see nearby TICs.', 'SagasWhat')); ?></span></p>
	<?php endif; ?>

	<?php
	if (($lat) && ($lng)) {

		set_event_distance($lat, $lng, 'tic', 'post');//観光案内所までの距離をカスタムフィールドに保存

		$args = array(
		    'post_type'		=> 'post',		// 投稿
		    'posts_per_page' => '-1',		// 10件表示
			'category_name'	=> 'tourist-info-center',
			'orderby'		=> array('meta_distance'=>'asc'),//距離の近い順で表示
			'meta_query'	=> array(
				'meta_distance'=>array(
					'key'		=> 'distance',				//観光案内所までの距離
					'compare'	=> 'exists',
				),
			),
			'paged'			=> $paged,
		);
	} else {
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '-1',
			'category_name'	=> 'tourist-info-center',
			'orderby'		=> array('meta_tic'=>'asc'),//TICリスト番号の昇順で表示
			'meta_query'	=> array(
				'meta_tic'=>array(
					'key'		=> 'location',				//23区IDのカスタムフィールド
					'type'		=> 'numeric',				//タイプに数値を指定
				),
			),
			'paged'			=> $paged,
		);
	} ?>
	<?php $the_query = new WP_Query($args); ?>

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); ?>

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

	<div class="tic-comment-end"><?php echo get_option('tic-comment-end'); ?></div>

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
