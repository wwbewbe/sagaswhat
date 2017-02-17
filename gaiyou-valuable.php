<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php echo esc_url(get_post_meta($post->ID, 'url', true)); ?>" target="_blank">

<img src="<?php echo mythumb( 'medium' ); ?>" alt="">

<div class="text">
	<h1><?php the_title(); ?></h1>

	<?php
	/**
	 * 開催期間を一覧に表示
	 */
	$eventopen = esc_html( get_post_meta($post->ID, 'eventopen', true) );
	$eventclose = esc_html( get_post_meta($post->ID, 'eventclose', true) );
	$openseason = get_post_meta($post->ID, 'openseason', true);
	$closeseason = get_post_meta($post->ID, 'closeseason', true);
	if( (!empty($eventopen)) && (!empty($eventclose)) ) {	//開催日も終了日も入力されている場合
		$datetime = date_i18n('Y-m-d', strtotime($eventopen));
		if($eventopen == $eventclose) {	//開催日と終了日が同じ場合は終了日のみ表示
			$dates = date_i18n(__('M j, Y', 'SagasWhat'), strtotime($eventclose));
			if ($closeseason) $dates = $closeseason;	//シーズンが設定されている場合はシーズンを表示
		} else {
			$opendate = date_i18n(__('M j, Y', 'SagasWhat'), strtotime($eventopen));
			$closedate = date_i18n(__('M j, Y', 'SagasWhat'), strtotime($eventclose));
			if ($openseason) $opendate = $openseason;		//シーズンが設定されている場合はシーズンを表示
			if ($closeseason) $closedate = $closeseason;	//シーズンが設定されている場合はシーズンを表示
			$dates = $opendate . ' - ' . $closedate;
		}
	} elseif (!empty($eventopen)) {	//開催日のみ入力されている場合（終了日未定の場合）
		$datetime = date_i18n('Y-m-d', strtotime($eventopen));
		$opendate = date_i18n(__('M j, Y', 'SagasWhat'), strtotime($eventopen));
		if ($openseason) $opendate = $openseason;	//シーズンが設定されている場合はシーズンを表示
		$dates = $opendate . ' - ' . esc_html__('&gt;&gt;&gt;', 'SagasWhat');
	} else {
		$dates = date_i18n(__('M j, Y', 'SagasWhat'), strtotime($eventclose));
		if ($closeseason) $dates = $closeseason;
	}
	?>

	<div class="valuable">
	<?php echo get_post_meta($post->ID, 'ticker', true); ?>
	</div>
	<div class="valuable">
	<?php echo get_post_meta($post->ID, 'note', true); ?>
	</div>
	<div class="valuable-date">
	<i class="fa fa-calendar fa-fw"></i>
	<time
	datetime="<?php echo $datetime; ?>">
	<?php echo $dates; ?>
	</time>
	</div>
	<div class="valuable-time">
		<i class="fa fa-clock-o fa-fw"></i>
		<?php echo get_post_meta($post->ID, 'bizhours', true); ?>
	</div>
	<div class="valuable">
		<i class="fa fa-map-marker fa-fw"></i>
	<?php echo get_post_meta($post->ID, 'showaddress', true); ?>
	</div>
	<div class="valuable">
		<i class="fa fa-phone fa-fw"></i>
	<?php echo get_post_meta($post->ID, 'telephone', true); ?>
	</div>

</div>
</a>
</article>
