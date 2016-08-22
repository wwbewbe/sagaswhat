<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<?php
	// 開催期間
	$eventopen = esc_html( get_post_meta($post->ID, 'eventopen', true) );
	$eventclose = esc_html( get_post_meta($post->ID, 'eventclose', true) );
	if($eventopen && $eventclose) {
		$datetime = date_i18n('Y-m-d', strtotime($eventopen));
		if($eventopen == $eventclose) {
			if ( get_bloginfo('language') == 'ja' ) {
				$dates = date_i18n('Y年n月j日(D)', strtotime($eventclose));
			} else {
				$dates = date_i18n('F jS, Y', strtotime($eventclose));
			}
		} else {
			if ( get_bloginfo('language') == 'ja' ) {
				$eventopen = date_i18n('Y年n月j日(D)', strtotime($eventopen));
				$eventclose = date_i18n('Y年n月j日(D)', strtotime($eventclose));
			} else {
				$eventopen = date_i18n('F jS', strtotime($eventopen));
				$eventclose = date_i18n('F jS, Y', strtotime($eventclose));
			}
			$dates = $eventopen . ' ~ ' . $eventclose;
		}
	} elseif($eventopen || $eventclose) {
		if ($eventopen) {
			$datetime = date_i18n('Y-m-d', strtotime($eventopen));
			if ( get_bloginfo('language') == 'ja' ) {
				$eventopen = date_i18n('Y年n月j日(D)', strtotime($eventopen));
			} else {
				$eventopen = date_i18n('F jS', strtotime($eventopen));
			}
		}
		if ($eventclose) {
			if ( get_bloginfo('language') == 'ja' ) {
				$eventclose = date_i18n('Y年n月j日(D)', strtotime($eventclose));
			} else {
				$eventclose = date_i18n('F jS, Y', strtotime($eventclose));
			}
		}
		$dates = $eventopen . ' ~ ' . $eventclose;
	}
	?>

	<div class="kiji-date">
	<i class="fa fa-calendar"></i>
	<time
	datetime="<?php echo $datetime; ?>">
	<?php echo $dates; ?>
	</time>
	</div>

	<?php the_excerpt(); ?>
</div>
</a>
</article>
