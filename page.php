<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

	<?php
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '10',
			'category_name'	=> esc_attr($post->post_name),  // 'カテゴリースラッグ' => 'ページスラッグ'
			'meta_key'		=> 'recommend',
			'orderby'		=> array('meta_value_num'=>'DESC'),
			'meta_query'	=> get_meta_query_recargs(),
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

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
