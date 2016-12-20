<?php get_header(); ?>

<div class="sub-header">
	<div class="bread">
	</div>
</div>

<div class="container">
<div class="contents">
	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

	<h1><?php the_title(); ?></h1>

	<div class="kiji-tax">
	<?php the_terms( $post->ID, 'keyword', '<ul><li>', '</li><li>', '</li></ul>' ); ?>
	</div>

	<div class="kiji-date">
	<i class="fa fa-pencil"></i>
	<time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
		<?php echo get_the_date( __('M j, Y', 'SagasWhat') ); ?>
	</time>

	<?php if( get_the_modified_date( 'Ymd' ) > get_the_date( 'Ymd' ) ): ?>
	 ｜
	 <i class="fa fa-edit fa-fw"></i>
	<time datetime="<?php echo get_the_modified_date( 'Y-m-d' ); ?>">
		<?php echo get_the_modified_date( __('M j, Y', 'SagasWhat') ); ?>
	</time>
	<?php endif; ?>
	</div>

	<?php the_content(); ?>

	<?php wp_link_pages( array(
		'before' => '<div class="pagination"><ul><li>',
		'separator' => '</li><li>',
		'after' => '</li></ul></div>',
		'pagelink' => '<span>%</span>'
	) ); ?>

	<?php get_template_part( 'nearby', 'events' ); //Nearby Events list function ?>
	<?php get_template_part( 'nearby', 'tic' ); //Nearby TIC list function ?>

	</article>
	<?php endwhile; endif; ?>
</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
