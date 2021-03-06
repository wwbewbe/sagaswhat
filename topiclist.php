<div class="taglist">

<?php
//  URLのパラメター(緯度と経度の位置情報)を取得
$lat = (isset($_GET['lat'])) ? esc_html($_GET['lat']) : '';
$lng = (isset($_GET['lng'])) ? esc_html($_GET['lng']) : '';
?>

<?php if($sort) : ?>
	<?php if(($lat) && ($lng)) : ?>
		<p><span class="sort-after"><?php echo esc_html(__('* Events have been sorted in close order.', 'SagasWhat')); ?></span></p>
	<?php else : ?>
		<button type="button" id="nearnav" onclick="this.style.color='#dddddd', this.style.backgroundColor='#dddddd', this.style.textShadow='1px 1px 1px rgba(255,255,255,0.5),-1px -1px 1px rgba(0,0,0,0.5)'">
			<i class="fa fa-bars fa-fw"></i><?php echo esc_html(__('Sort by Distance', 'SagasWhat')); ?>
		</button>
		<p><span class="sort-before"><?php echo esc_html(__('* Use this to see nearby events.', 'SagasWhat')); ?></span></p>
	<?php endif; ?>
<?php endif; ?>

<?php
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
if (($lat) && ($lng)) {

	set_event_distance($lat, $lng, 'event', 'post');//イベント会場までの距離をカスタムフィールドに保存

	$args = array(
		'post_type'		=> 'post',
		'posts_per_page' => $list,		// リスト数を指定
		'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
		'tag'           => $tagname,    // タグを指定
		'orderby'		=> array('meta_distance'=>'asc', 'meta_recommend'=>'desc', 'meta_close'=>'asc'),//距離の近い順＆おすすめ度の高い順＆終了日が近い順に表示
		'meta_query'	=> get_meta_query_args('0', 'exists'),//終了していない＆距離情報があるイベント
		'paged'			=> $paged,
	);
} else {
	$args=array(
    'post_type'		=> 'post',
    'posts_per_page'=> $list,       // リスト数を指定
		'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
    'tag'           => $tagname,    // タグを指定
		'orderby'		=> array('meta_recommend'=>'DESC', 'meta_close'=>'ASC', 'title'=>'ASC'),//おすすめ度の高い順＆終了日が近い順に表示
    'meta_query'	=> get_meta_query_args('0','0'),//終了していないイベント
		'paged'			=> $paged,
	);
}
?>

<?php $adcount = 0; // アドセンスの挿入位置を決めるための記事数カウント?>

<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

	<?php if ($the_query->post_count > 4 && $adcount == 4): ?>
		<div class="mymenu-adsense">
			<?php echo get_adsense('infeed'); ?>
		</div>
	<?php endif; ?>

  <?php get_template_part( 'gaiyou', 'medium' ); ?>

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
