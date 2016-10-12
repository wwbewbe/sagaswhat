<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<?php
	// 開催期間
	$eventopen = esc_html( get_post_meta($post->ID, 'eventopen', true) );
	$eventclose = esc_html( get_post_meta($post->ID, 'eventclose', true) );
	if( (!empty($eventopen)) && (!empty($eventclose)) ) {
		$datetime = date_i18n('Y-m-d', strtotime($eventopen));
		if($eventopen == $eventclose) {
			if ( get_bloginfo('language') == 'ja' ) {
				$dates = date_i18n('Y年n月j日(D)', strtotime($eventclose));
			} else {
				$dates = date_i18n('M j, Y', strtotime($eventclose));
			}
		} else {
			if ( get_bloginfo('language') == 'ja' ) {
				$opendate = date_i18n('Y年n月j日(D)', strtotime($eventopen));
				$closedate = date_i18n('Y年n月j日(D)', strtotime($eventclose));
			} else {
				$opendate = date_i18n('M j, Y', strtotime($eventopen));
				$closedate = date_i18n('M j, Y', strtotime($eventclose));
			}
			$dates = $opendate . ' - ' . $closedate;
		}
	} elseif (!empty($eventopen)) {
		$datetime = date_i18n('Y-m-d', strtotime($eventopen));
		if ( get_bloginfo('language') == 'ja' ) {
			$opendate = date_i18n('Y年n月j日(D)', strtotime($eventopen));
		} else {
			$opendate = date_i18n('M j, Y', strtotime($eventopen));
		}
		$dates = $opendate . ' - ' . esc_html__('&gt;&gt;&gt;', 'SagasWhat');
	} else {
		if ( get_bloginfo('language') == 'ja' ) {
			$dates = date_i18n('Y年n月j日(D)', strtotime($eventclose));
		} else {
			$dates = date_i18n('M j, Y', strtotime($eventclose));
		}
	}

	$today = date_i18n('Y/m/d');
	if ((strtotime($eventopen)<=strtotime($today)) && (strtotime($eventclose)>=strtotime($today)) || (empty($eventclose))) {
		$stat = '<div class="openstat"><i class="fa fa-check-circle fa-fw"></i>'.esc_html__('Now Open', 'SagasWhat').'</div>';
	} elseif (strtotime($eventopen)>strtotime($today)) {
		$stat = '<div class="soonstat"><i class="fa fa-minus-circle fa-fw"></i>'.esc_html__('Coming Soon...', 'SagasWhat').'</div>';
	} else {
		$stat = '<div class="closestat"><i class="fa fa-times-circle fa-fw"></i>'.esc_html__('Closed', 'SagasWhat').'</div>';
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
	datetime="<?php echo $datetime; ?>">
	<?php echo $dates; ?>
	<?php echo $stat; ?>
	</time>
	</div>

	<?php the_excerpt(); ?>
</div>
</a>
</article>
