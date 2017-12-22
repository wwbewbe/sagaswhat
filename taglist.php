<div class="taglist">
<?php
if (($posttype == 'post') || ($posttype == 'page')) {
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	$args=array(
	            'post_type'		=> $posttype,	//表示する投稿タイプ(post, page, sw_trend)
	            'posts_per_page'=> $list,       //リスト数を指定
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
		        );
	}
}
?>
<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

  <?php if ($posttype == 'sw_trend') {
	  get_template_part( 'gaiyou', 'trends' );
  } else {
	  get_template_part( 'gaiyou', 'medium' );
  } ?>

<?php endwhile; endif; ?>

  <?php wp_reset_postdata(); ?>
</div>
