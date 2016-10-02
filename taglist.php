<div class="taglist">
<?php
$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
$args=array(
            'post_type'		=> 'post',
            'posts_per_page'=> $list,       // リスト数を指定
			'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
            'tag'           => $tagname,    // タグを指定
			'orderby'		=> array('meta_recommend'=>'desc', 'meta_open'=>'asc'),//おすすめ度の高い順で表示
            'meta_query'	=> get_meta_query_args(),
        ); ?>
<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

  <?php get_template_part( 'gaiyou', 'medium' ); ?>

<?php endwhile; endif; ?>

  <?php wp_reset_postdata(); ?>
</div>
