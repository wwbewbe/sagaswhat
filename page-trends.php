<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

		<h1><?php the_title(); ?></h1>
		<?php the_excerpt(); ?>

		<div class="kiji-tax">
		<ul>
		<?php
		$args = array( 'hide_empty=0' );

		$terms = get_terms( 'keyword', $args );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		    foreach ($terms as $term ) {
				$term_list = '<li>';
		    	$term_list .= '<a href="' . get_term_link( $term ) . '">';
		    	$term_list .= $term->name . '</a>';
		        $term_list .= '</li>';
				echo $term_list;
		    }
		}
		?>
		</ul>
		</div>

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
