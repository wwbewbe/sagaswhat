<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">
	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

	<div class="kiji-tag">
	<?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
	</div>

	<h1><?php the_title(); ?></h1>

	<div class="kiji-date">
	<i class="fa fa-pencil"></i>

	<time
	datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
	<?php echo esc_html(__('Posted:', 'SagasWhat')); echo get_the_date(); ?>
	</time>

	<?php if( get_the_modified_date( 'Ymd' ) > get_the_date( 'Ymd' ) ): ?>
	｜
	<time
	datetime="<?php echo get_the_modified_date( 'Y-m-d' ); ?>">
	<?php echo esc_html(__('Updated:', 'SagasWhat')); echo get_the_modified_date(); ?>
	</time>
	<?php endif; ?>
	</div>

	<?php if( has_post_thumbnail() && $page==1 ): ?>
	<div class="catch">
	<?php the_post_thumbnail( 'large' ); ?>
	<p class="wp-caption-text">
	<?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?>
	</p>
	</div>
	<?php endif; ?>

	<div class="kiji-body">
	<?php the_content(); ?>
	</div>

	<?php wp_link_pages( array(
		'before' => '<div class="pagination"><ul><li>',
		'separator' => '</li><li>',
		'after' => '</li></ul></div>',
		'pagelink' => '<span>%</span>'
	) ); ?>


	</article>
	<?php endwhile; endif; ?>

	<aside class="mymenu-adsense">
	<?php echo (get_adsense()); ?>
	</aside>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
<!--	<aside class="mymenu mymenu-page">
	<h2>CONTENTS</h2>
	<?php wp_nav_menu( array( 'theme_location' => 'pagenav' ) ); ?>
	</aside>
表組みリストを削除-->
</div>
</div>

<?php get_footer(); ?>
