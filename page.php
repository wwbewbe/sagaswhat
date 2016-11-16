<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

<?php if(preg_match( '/topics-(\w+)/s', $post->post_name)): ?>

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

		<h1><?php the_title(); ?></h1>

		<div class="kiji-body">
		<?php the_content(); ?>
		</div>

		<?php wp_link_pages( array(
			'before' => '<div class="pagination"><ul><li>',
			'separator' => '</li><li>',
			'after' => '</li></ul></div>',
			'pagelink' => '<span>%</span>'
		) ); ?>

		<aside class="mymenu-adsense">
		<?php echo get_adsense(); ?>
		</aside>

		<?php if (function_exists('wpfp_list_favorite_posts')) {
			get_template_part( 'favorite', 'events' );
		} //Favorite Events list function?>

	</article>
	<?php endwhile; endif; ?>

<?php else : ?>

	<?php
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '10',
			'category_name'	=> esc_attr($post->post_name),  // 'カテゴリースラッグ' => 'ページスラッグ'
			'orderby'		=> array('meta_recommend'=>'desc', 'meta_close'=>'asc'),//おすすめ度の高い順＆終了日が近い順に表示
			'meta_query'	=> get_meta_query_args(),
			'paged'			=> $paged,
		); ?>
	<?php $the_query = new WP_Query($args); ?>

	<h1><?php the_title(); ?></h1>

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); ?>

	<?php get_template_part( 'gaiyou', 'medium' ); ?>

	<?php endwhile; endif; ?>

	<div class="pagination pagination-index">
	<?php echo paginate_links( array( 'type' => 'list',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
							'total'		=> $the_query->max_num_pages
							 ) ); ?>
	</div>
	<?php wp_reset_postdata(); ?>

	<aside class="mymenu-adsense">
	<?php echo get_adsense(); ?>
	</aside>

	<?php if (function_exists('wpfp_list_favorite_posts')) {
		get_template_part( 'favorite', 'events' );
	} //Favorite Events list function?>

<?php endif; ?>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
