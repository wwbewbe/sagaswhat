<?php get_header(); ?>

<div class="sub-header">
</div>

<div class="container">
<div class="contents">

	<?php
		//  URLのパラメター(リストする対象の月)を取得
		$target = (isset($_GET['target'])) ? esc_html($_GET['target']) : '';
		switch($target) {
		case '1':	//This Month
			$opendates=date_i18n( "Y/m/t" );
			$closedates=date_i18n( "Y/m/d" );
			$showmonth=date_i18n(__('[F, Y]', 'SagasWhat'));
			break;
		case '2':	//Next Month
			$opendates=date_i18n( "Y/m/t", strtotime(date_i18n('Y/m/1') . '+1 month') );
			$closedates=date_i18n( "Y/m/01", strtotime(date_i18n('Y/m/1') . '+1 month') );
			$showmonth=date_i18n(__('[F, Y]', 'SagasWhat'), strtotime(date_i18n('Y/m/1') . '+1 month'));
			break;
		case '3':	//2 Month Later
			$opendates=date_i18n( "Y/m/t", strtotime(date_i18n('Y/m/1') . '+2 month') );
			$closedates=date_i18n( "Y/m/01", strtotime(date_i18n('Y/m/1') . '+2 month') );
			$showmonth=date_i18n(__('[F, Y]', 'SagasWhat'), strtotime(date_i18n('Y/m/1') . '+2 month'));
			break;
		case '4':	//3 Month Later
			$opendates=date_i18n( "Y/m/t", strtotime(date_i18n('Y/m/1') . '+3 month') );
			$closedates=date_i18n( "Y/m/01", strtotime(date_i18n('Y/m/1') . '+3 month') );
			$showmonth=date_i18n(__('[F, Y]', 'SagasWhat'), strtotime(date_i18n('Y/m/1') . '+3 month'));
			break;
		}
	?>
	<?php
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '10',
			'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
			'orderby'		=> array('meta_recommend'=>'desc', 'meta_open'=>'asc'),//おすすめ度の高い順で表示
			'paged'			=> $paged,
			'meta_query'	=> array(
				'relation'		=> 'AND',
				array(
					'relation'		=> 'OR',
					array(
						'key'		=> 'eventclose',	//カスタムフィールドのイベント終了日欄
						'compare'	=> 'NOT EXISTS',	//カスタムフィールドがない場合も表示
					),
					'meta_close'=>array(
						'key'		=> 'eventclose',	//カスタムフィールドのイベント終了日欄
						'value'		=> $closedates,		//イベント終了月を比較
						'compare'	=> '>=',			//対象月以降なら表示
						'type'		=> 'date',			//タイプに日付を指定
					),
				),
				'meta_open'=>array(
					'key'		=> 'eventopen',		//カスタムフィールドのイベント開催日欄
					'value'		=> $opendates,		//イベント開催月を比較
					'compare'	=> '<=',			//対象月以前なら表示
					'type'		=> 'date',			//タイプに日付を指定
				),
				'meta_recommend'=>array(
					'key'		=> 'recommend',		//カスタムフィールドのおすすめ度
					'value'		=> '0',				//
					'compare'	=> '>=',			//おすすめ度0以上を表示
					'type'		=> 'numeric',		//タイプに数値を指定
				),
			),
		); ?>
	<?php $the_query = new WP_Query($args); ?>

	<h1><?php the_title(); ?></h1>
	<h2><?php echo $showmonth; ?></h2>

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); ?>

	<?php get_template_part( 'gaiyou', 'medium' ); ?>

	<?php endwhile; endif; ?>

	<div class="pagination pagination-index">
	<?php echo paginate_links( array( 'type' => 'list',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
							'total'		=> $the_query->max_num_pages
							 ) ); ?>
	</div>
	<?php wp_reset_postdata(); ?>

	<aside class="mymenu-adsense">
	<?php echo get_adsense(); ?>
	</aside>

	<?php if (function_exists('wpfp_list_favorite_posts')) {
		get_template_part( 'favorite', 'events' );
	} //Favorite Events list function?>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
