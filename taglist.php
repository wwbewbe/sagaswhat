<div class="taglist">
<?php
$args=array(
            'post_type'		=> 'post',
            'posts_per_page'=> $list,       // リスト数を指定
            'cat'           => '-1',        // 未分類を除外
            'tag'           => $tagname,    // タグを指定
			'orderby'		=> array('meta_recommend'=>'desc'),//おすすめ度の高い順で表示
            'meta_query'	=> get_meta_query_args(),
        ); ?>
<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

  <?php get_template_part( 'gaiyou', 'medium' ); ?>

<?php endwhile; endif; ?>

  <?php wp_reset_postdata(); ?>
</div>
