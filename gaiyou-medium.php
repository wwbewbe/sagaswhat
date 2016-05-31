<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<div class="kiji-date">
<!--
	<i class="fa fa-pencil"></i>
	<time
	datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
	投稿：<?php echo get_the_date(); ?>
	</time>
-->
	</div>

	<?php the_excerpt(); ?>
</div>
</a>
</article>
