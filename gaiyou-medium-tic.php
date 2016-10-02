<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<div class="tic-name">
		<h1><?php the_title(); ?></h1>
	</div>

	<?php
	$wifi = esc_html( get_post_meta($post->ID, 'wifi', true) );
	$pc = esc_html( get_post_meta($post->ID, 'pc', true) );
	//TICの住所を表示
	$addr = esc_html( get_post_meta($post->ID, 'showaddress', true) );
	?>
	<div class="tic-addr">
		<?php echo $addr; ?>
	</div>
	<div class="kiji-date">
		<i class="fa fa-wifi fa-fw"></i>
		<?php echo $wifi.' / '; ?>
		<i class="fa fa-desktop fa-fw"></i>
		<?php echo $pc; ?>
	</div>

</div>
</a>
</article>
