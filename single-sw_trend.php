<?php get_header(); ?>

<div class="container">
<div class="contents">
	<div class="bread">
	<ol>
		<li><a href="<?php echo home_url(); ?>">
		<i class="fa fa-home"></i><span>TOP</span>
		</a></li>

		<li>
		<a href="<?php echo esc_url( get_permalink( get_page_by_title( esc_html__('Topics', 'SagasWhat') ) ) ); ?>"><?php echo esc_html__('Topics', 'SagasWhat'); ?></a>
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
				<?php the_terms( $post->ID, 'keyword', '', '</li><li>', '' ); ?>
			</li>
		</ul>
	</div>

	<!--<div class="kiji-date">
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
	</div>-->

	<aside class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
	</aside>

	<?php if (has_post_thumbnail()) : ?>
		<div class="wp-caption">
		<?php the_post_thumbnail('full');
		echo '<p class="wp-caption-text">' . get_post( get_post_thumbnail_id() )->post_excerpt . '</p>'; ?>
		</div>
	<?php endif; ?>

	<?php the_content(); ?>

	<?php wp_link_pages( array(
		'before' => '<div class="pagination"><ul><li>',
		'separator' => '</li><li>',
		'after' => '</li></ul></div>',
		'pagelink' => '<span>%</span>'
	) ); ?>

	<aside class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
	</aside>

	<?php
	$myposts = get_posts( array(
		'post_type'		=> 'sw_trend',
		'posts_per_page'=> '8',
	) );
	if( $myposts ): ?>
	<aside class="mymenu mymenu-thumb mymenu-related">
	<h2><?php echo esc_html(__('Other Topics', 'SagasWhat')); ?></h2>
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
