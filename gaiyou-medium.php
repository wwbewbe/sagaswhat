<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<?php the_excerpt(); ?>
</div>
</a>
</article>
