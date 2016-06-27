<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">
	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>

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


<!--	</article>-->
	<?php endwhile; endif; ?>
</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
