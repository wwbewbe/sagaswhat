<?php get_header(); ?>

<div class="container">
<div class="contents">
	<div class="bread">
	<ol>
		<li><a href="<?php echo home_url(); ?>">
		<i class="fa fa-home"></i><span>TOP</span>
		</a></li>

		<li>
		<a href="<?php echo esc_url( get_permalink( get_page_by_title( esc_html__('Resting Spots', 'SagasWhat') ) ) ); ?>"><?php echo esc_html__('Resting Spots', 'SagasWhat'); ?></a>
		</li>
		<li>
		<a><span>&gt;</span></a>
		</li>
	</ol>
	</div>

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

	<h1><?php the_title(); ?></h1>

	<div class="kiji-tax">
		<ul>
			<li>
				<?php the_terms( $post->ID, 'category', '', '</li><li>', '' ); ?>
					<?php if( has_category() ): ?>
					</li><li>
					<?php endif; ?>
				<?php the_terms( $post->ID, 'kind', '', '</li><li>', '' ); ?>
			</li>
		</ul>
	</div>

	<?php the_content(); ?>

	<?php wp_link_pages( array(
		'before' => '<div class="pagination"><ul><li>',
		'separator' => '</li><li>',
		'after' => '</li></ul></div>',
		'pagelink' => '<span>%</span>'
	) ); ?>

	<?php
	$myposts = get_posts( array(
		'post_type'		=> 'sw_rest',
		'posts_per_page'=> '8',
	) );
	if( $myposts ): ?>
	<aside class="mymenu mymenu-thumb mymenu-related">
	<h2><?php echo esc_html(__('Other Spots', 'SagasWhat')); ?></h2>
	<ul>
		<?php foreach($myposts as $post):
		setup_postdata($post); ?>
		<li><a href="<?php the_permalink(); ?>">
		<div class="thumb" style="background-image: url(<?php echo mythumb( 'full' ); ?>)">
		</div>
		<div class="text">
		<?php the_title(); ?>
		</div>
		</a></li>
		<?php endforeach; ?>
	</ul>
	</aside>
	<?php wp_reset_postdata();
	endif; ?>

	<aside class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
	</aside>

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
