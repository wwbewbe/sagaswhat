<div class="taglist">
<?php
if (($posttype == 'post') || ($posttype == 'page')) {
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	$args=array(
	            'post_type'		=> $posttype,	//表示する投稿タイプ(post, page, trend)
	            'posts_per_page'=> $list,       //リスト数を指定
				'category__not_in' => array(1, $infocat->cat_ID), //カテゴリが未分類と観光案内所の記事は非表示
	            'tag'           => $tagname,    //タグを指定(slug)
				'category_name'	=> $catname,	//カテゴリーを指定(slug)
				'orderby'		=> array('meta_recommend'=>'desc', 'meta_close'=>'asc'),//おすすめ度の高い順＆終了日が近い順に表示
	            'meta_query'	=> get_meta_query_args(),
	        );
} else { //カスタム投稿タイプのときのクエリー
	if (isset($terms)) {
		$terms = explode(',', $terms);	//カスタムタクソノミーの項目を配列にする
	}
	$args=array(
	            'post_type'		=> $posttype,	//表示するカスタム投稿タイプ(trend, etc.)
	            'posts_per_page'=> $list,       //リスト数を指定
				'tax_query'		=> array(
					array(
						'taxonomy'	=> $tax,	//表示するカスタムタクソノミー(keyword, etc.)＊カスタム投稿のみ使用
						'field'		=> 'slug',
						'terms'		=> $terms,	//表示するカスタムタクソノミーの項目名(matsuri, etc.)＊カスタム投稿のみ使用
					),
				),
	        );
}
?>
<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

  <?php if (($posttype == 'post') || ($posttype == 'page')) {
	  get_template_part( 'gaiyou', 'medium' );
  } else {
	  get_template_part( 'gaiyou', 'custom' );
  } ?>

<?php endwhile; endif; ?>

  <?php wp_reset_postdata(); ?>
</div>
