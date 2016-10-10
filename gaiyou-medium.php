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
		$opendate = date_i18n('Y-m-d', strtotime($eventopen));
		if($eventopen == $eventclose) {
			if ( get_bloginfo('language') == 'ja' ) {
				$dates = date_i18n('Y年n月j日(D)', strtotime($eventclose));
			} else {
				$dates = date_i18n('M j, Y', strtotime($eventclose));
			}
		} else {
			if ( get_bloginfo('language') == 'ja' ) {
				$eventopen = date_i18n('Y年n月j日(D)', strtotime($eventopen));
				$eventclose = date_i18n('Y年n月j日(D)', strtotime($eventclose));
			} else {
				$eventopen = date_i18n('M j, Y', strtotime($eventopen));
				$eventclose = date_i18n('M j, Y', strtotime($eventclose));
			}
			$dates = $eventopen . ' - ' . $eventclose;
		}
	} elseif ($eventopen) {
		$opendate = date_i18n('Y-m-d', strtotime($eventopen));
		if ( get_bloginfo('language') == 'ja' ) {
			$eventopen = date_i18n('Y年n月j日(D)', strtotime($eventopen));
		} elseif (!$eventclose) {
			$eventopen = date_i18n('M j, Y', strtotime($eventopen));
		} else {
			$eventopen = date_i18n('M j, Y', strtotime($eventopen));
		}
		$dates = $eventopen . ' - ' . esc_html__('&gt;&gt;&gt;', 'SagasWhat');
	} elseif ($eventclose) {
		if ( get_bloginfo('language') == 'ja' ) {
			$dates = date_i18n('Y年n月j日(D)', strtotime($eventclose));
		} else {
			$dates = date_i18n('M j, Y', strtotime($eventclose));
		}
	}

	$today = date_i18n('Y-m-d');
	if ($opendate<=$today) {
		$stat = '<div class="openstat"><i class="fa fa-check-circle fa-fw"></i>'.esc_html__('Now Open', 'SagasWhat').'</div>';
	} else {
		$stat = '<div class="soonstat"><i class="fa fa-minus-circle fa-fw"></i>'.esc_html__('Coming Soon...', 'SagasWhat').'</div>';
	}
	//Favorite Events total number for each event
/*	$favorite = esc_html( get_post_meta($post->ID, 'wpfp_favorites', true) );
	if (($favorite > '0') && (function_exists('wpfp_list_favorite_posts'))) {
		switch ($favorite) {
			case '1': // 1 Star
				$stars = '<div class="stars"><i class="fa fa-star"></i></div>';
				break;
			case '2': // 2 Stars
				$stars = '<div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i></div>';
				break;
			case '3': // 3 Stars
				$stars = '<div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>';
				break;
			case '4': // 4 Stars
				$stars = '<div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>';
				break;
			default: // more than 5 Stars
				$stars = '<div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>';
				break;
		}
	}
*/
	?>

	<div class="kiji-date">
	<i class="fa fa-calendar"></i>
	<time
	datetime="<?php echo $opendate; ?>">
	<?php echo $dates; ?>
	<?php echo $stat; ?>
	</time>
	</div>

	<?php the_excerpt(); ?>
</div>
</a>
</article>
