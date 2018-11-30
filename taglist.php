<div class="taglist">
<?php
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
if (($posttype == 'post') || ($posttype == 'page')) {
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	$args=array(
        'post_type'	=> $posttype,	//表示する投稿タイプ(post, page, sw_trend)
        'posts_per_page'=> $list,       //リスト数を指定
				'paged'			=> $paged,
				'category__not_in' => array(1, $infocat->cat_ID), //カテゴリが未分類と観光案内所の記事は非表示
        'tag'           => $tagname,    //タグを指定(slug)
				'category_name'	=> $catname,	//カテゴリーを指定(slug)
				'orderby'		=> array('meta_recommend'=>'DESC', 'meta_close'=>'ASC', 'title'=>'ASC'),//おすすめ度の高い順＆終了日が近い順に表示
        'meta_query'	=> get_meta_query_args('0','0'),
	        );
} else { //カスタム投稿タイプのときのクエリー
	if (!empty($terms)) {
		if ($terms == '-1') {
			$field = 'term_id';
		} else {
			$terms = explode(',', $terms);	//カスタムタクソノミーの項目を配列にする
			$field = 'slug';
		}
		$args=array(
          'post_type'		=> $posttype,	//表示するカスタム投稿タイプ(sw_trend, etc.)
          'posts_per_page'=> $list,       //リスト数を指定
					'paged'			=> $paged,
					'tax_query'		=> array(
							array(
								'taxonomy'	=> $tax,	//表示するカスタムタクソノミー(keyword, etc.)＊カスタム投稿のみ使用
								'field'		=> $field,
								'terms'		=> $terms,	//表示するカスタムタクソノミーの項目名(matsuri, etc.)＊カスタム投稿のみ使用
							),
						),
	        );
	} else {
		$args=array(
          'post_type'		=> $posttype,	//表示するカスタム投稿タイプ(sw_trend, etc.)
          'posts_per_page'=> $list,       //リスト数を指定
					'paged'			=> $paged,
	        );
	}
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

  <?php if ($posttype == 'sw_trend') {
	  get_template_part( 'gaiyou', 'trends' );
  } else {
	  get_template_part( 'gaiyou', 'medium' );
  } ?>

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
