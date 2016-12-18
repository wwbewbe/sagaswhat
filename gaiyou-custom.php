<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<div class="kiji-date">
	<i class="fa fa-pencil"></i>

	<time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
		<?php echo esc_html__( 'Posted: ', 'SagasWhat' ); ?>
		<?php echo get_the_date( 'Y/m/d' ); ?>
	</time>

	<?php if( get_the_modified_date( 'Ymd' ) > get_the_date( 'Ymd' ) ): ?>
	 ｜
	<time datetime="<?php echo get_the_modified_date( 'Y-m-d' ); ?>">
		<?php echo esc_html__( 'Updated: ', 'SagasWhat' ); ?>
		<?php echo get_the_modified_date( 'Y/m/d' ); ?>
	</time>
	<?php endif; ?>
	</div>

	<div class="kiji-tax">
	<?php
	$terms = get_the_terms( $post->ID, 'keyword' );

	if ( $terms && ! is_wp_error( $terms ) ) :

		$kwd_links = array();

		foreach ( $terms as $term ) {
			$kwd_links[] = $term->name;
			printf ('<span>');
			echo $term->name;
			printf ('</span>');
		}
	?>
	</div>

	<?php endif; ?>

	<?php the_excerpt(); ?>
</div>

</a>
</article>
