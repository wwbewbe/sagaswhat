<?php get_header(); ?>

<div class="container">
<div class="contents">

	<h1><?php single_term_title(); ?></h1>

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>

	<?php get_template_part( 'gaiyou', 'custom' ); ?>

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

	<?php get_template_part( 'nearby', 'events' ); //Nearby Events list function ?>
	<?php get_template_part( 'nearby', 'tic' ); //Nearby TIC list function ?>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
