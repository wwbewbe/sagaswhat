<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

	<?php
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '10',
			'category_name'	=> 'tourist-info-center',
			'orderby'		=> array('meta_tic'=>'asc'),//TICリスト番号の昇順で表示
			'meta_query'	=> array(
				'meta_tic'=>array(
					'key'		=> 'location',				//カスタムフィールドのおすすめ度
					'type'		=> 'numeric',				//タイプに数値を指定
				),
			),
			'paged'			=> $paged,
		); ?>
	<?php $the_query = new WP_Query($args); ?>

	<h1><?php the_title(); ?></h1>

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

	<div class="tic-info"><?php echo esc_html(get_option('tic-info')); ?></div>

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
