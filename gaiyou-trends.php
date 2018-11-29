<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<!--<div class="kiji-date">
	<i class="fa fa-pencil fa-fw"></i>
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
