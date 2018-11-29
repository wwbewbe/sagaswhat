<article <?php post_class( 'gaiyou' ); ?>>
<a href="<?php the_permalink(); ?>">

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
	/**
	 * イベント開催状況/料金/Wi−Fiの使用有無を表示
	 */
	$today = date_i18n('Y/m/d');
	if ((strtotime($eventopen)<=strtotime($today)) && (strtotime($eventclose)>=strtotime($today)) || (empty($eventclose))) {
		$stat = '<div class="openstat"><i class="fa fa-check-circle fa-fw"></i>'.esc_html__('Now Open', 'SagasWhat');
	} elseif (strtotime($eventopen)>strtotime($today)) {
		$stat = '<div class="soonstat"><i class="fa fa-minus-circle fa-fw"></i>'.esc_html__('Coming Soon...', 'SagasWhat');
	} else {
		$stat = '<div class="closestat"><i class="fa fa-times-circle fa-fw"></i>'.esc_html__('Closed', 'SagasWhat');
	}
	$posttags = get_the_tags();
	foreach ( (array)$posttags as $tag ) {
		if ($tag->slug == 'free') {
			$stat = $stat.'&nbsp;&nbsp;<span class="price"><i class="fa fa-jpy fa-fw"></i>'.esc_html__('Free', 'SagasWhat').'</span>';
		}
	}
	if (!empty(get_post_meta($post->ID, 'freewifi', true))) {
		$stat = $stat.'&nbsp;&nbsp;<span class="freewifi"><i class="fa fa-wifi fa-fw"></i>';
	}
	$stat = $stat.'</div>';

	?>

	<div class="kiji-date">
	<i class="fa fa-calendar fa-fw"></i>
	<time
	datetime="<?php echo $datetime; ?>">
	<?php echo $dates; ?>
	</time>
	</div>
	<?php echo $stat; ?>

	<?php the_excerpt(); ?>
</div>
</a>
</article>
