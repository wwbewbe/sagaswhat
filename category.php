<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

	<h1><?php single_term_title(); ?></h1>

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>

	<?php get_template_part( 'gaiyou', 'medium' ); ?>

	<?php endwhile; endif; ?>


	<div class="pagination pagination-index">
	<?php echo paginate_links( array(
		'type' => 'list',
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;'
	) ); ?>
	</div>

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
