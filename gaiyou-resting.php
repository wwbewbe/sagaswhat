<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<div class="kiji-date">
	<i class="fa fa-calendar fa-fw"></i>
	<?php
		$bizhours = get_post_meta($post->ID, 'bizhours', true);
		if (($bizhours != '24 Hours') && ($bizhours != '常時開園')) {
			echo esc_html__('Check open and close dates/hours in this.', 'SagasWhat');
		} else {
			echo get_post_meta($post->ID, 'bizhours', true);
		}
	?>
	</div>

	<div class="kiji-tax">
	<?php
	$terms = get_the_terms( $post->ID, 'kind' );

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

	<?php echo esc_html__('Nearest Station :', 'SagasWhat'); ?>
	<?php echo get_post_meta($post->ID, 'venue', true); ?>
</div>

</a>
</article>
