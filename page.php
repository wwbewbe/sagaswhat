<?php get_header(); ?>

<div class="container">
<div class="contents">

<?php if((preg_match('/topics-(\w+)/s', $post->post_name)) || (preg_match('/tourism/s', $post->post_name)) || (preg_match('/free-wifi/s', $post->post_name)) || (preg_match('/rests/s', $post->post_name))): ?>

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

		<h1><?php the_title(); ?></h1>

		<?php the_content(); ?>

		<?php wp_link_pages( array(
			'before' => '<div class="pagination"><ul><li>',
			'separator' => '</li><li>',
			'after' => '</li></ul></div>',
			'pagelink' => '<span>%</span>'
		) ); ?>

		<div class="mymenu-adsense">
			<?php echo get_adsense('infeed'); ?>
		</div>

	</article>
	<?php endwhile; endif; ?>

<?php else : ?>

	<?php
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args=array(
			'post_type'		=> array('post', 'sw_trend'),
			'posts_per_page'=> '10',
			'category_name'	=> esc_attr($post->post_name),  // 'カテゴリースラッグ' => 'ページスラッグ'
			'orderby'		=> array('meta_recommend'=>'DESC', 'meta_close'=>'ASC', 'title'=>'ASC'),//おすすめ度の高い順＆終了日が近い順に表示
			'meta_query'	=> get_meta_query_args('0','0'),
			'paged'			=> $paged,
		); ?>
	<?php $the_query = new WP_Query($args); ?>

	<h1><?php the_title(); ?></h1>

	<?php $adcount = 0; // アドセンスの挿入位置を決めるための記事数カウント?>

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); ?>

	<?php if ($the_query->post_count > 4 && $adcount == 4): ?>
		<div class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
		</div>
	<?php endif; ?>

	<?php if (get_post_type() == 'sw_trend') : ?>
		<?php get_template_part( 'gaiyou', 'trends' ); ?>
	<?php else : ?>
		<?php get_template_part( 'gaiyou', 'medium' ); ?>
	<?php endif; ?>

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

	<?php if ($adcount <= 4): ?>
		<aside class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
		</aside>
	<?php endif; ?>

<?php endif; ?>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
