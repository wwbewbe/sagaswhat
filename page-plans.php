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
			if (get_bloginfo('language') == 'ja') {
				$showmonth=date_i18n("[Y年F]");
			} else {
				$showmonth=date_i18n("[F, Y]");
			}
			break;
		case '2':	//Next Month
			$opendates=date_i18n( "Y/m/t", strtotime("+1 month") );
			$closedates=date_i18n( "Y/m/01", strtotime("+1 month") );
			if (get_bloginfo('language') == 'ja') {
				$showmonth=date_i18n("[Y年F]", strtotime("+1 month"));
			} else {
				$showmonth=date_i18n("[F, Y]", strtotime("+1 month"));
			}
			break;
		case '3':	//2 Month Later
			$opendates=date_i18n( "Y/m/t", strtotime("+2 month") );
			$closedates=date_i18n( "Y/m/01", strtotime("+2 month") );
			if (get_bloginfo('language') == 'ja') {
				$showmonth=date_i18n("[Y年F]", strtotime("+2 month"));
			} else {
				$showmonth=date_i18n("[F, Y]", strtotime("+2 month"));
			}
			break;
		case '4':	//3 Month Later
			$opendates=date_i18n( "Y/m/t", strtotime("+3 month") );
			$closedates=date_i18n( "Y/m/01", strtotime("+3 month") );
			if (get_bloginfo('language') == 'ja') {
				$showmonth=date_i18n("[Y年F]", strtotime("+3 month"));
			} else {
				$showmonth=date_i18n("[F, Y]", strtotime("+3 month"));
			}
			break;
		}
	?>
	<?php
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '10',
			'cat'           => '-1',				// 未分類を除外
			'orderby'		=> array('meta_recommend'=>'desc'),//おすすめ度の高い順で表示
			'paged'			=> $paged,
			'meta_query'	=> array(
				'relation'		=> 'AND',
				array(
					'key'		=> 'eventclose',	//カスタムフィールドのイベント終了日欄
					'value'		=> $closedates,		//イベント終了月を比較
					'compare'	=> '>=',			//対象月以降なら表示
					'type'		=> 'date',			//タイプに日付を指定
				),
				array(
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

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
