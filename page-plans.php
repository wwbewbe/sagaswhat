<?php get_header(); ?>

<div class="sub-header">
	<div class="bread">
	<ol>
		<li><a href="<?php echo home_url(); ?>">
		<i class="fa fa-home"></i><span>TOP</span>
		</a></li>

		<li>
		<?php if( has_category() ): ?>
		<?php $postcat=get_the_category(); ?>
		<?php echo get_category_parents( $postcat[0], true, '</li><li>' ); ?>
		<?php endif; ?>
		<a><?php the_title(); ?></a>
		</li>

	</ol>
	</div>
</div>

<div class="container">
<div class="contents">

	<?php
		//  URLのパラメター(リストする対象の月)を取得
		// 1:This Month, 2:Next Month, 3:2 Month Later, 4:3 Month Later
		$target = (isset($_GET['target'])) ? esc_html($_GET['target']) : '';
		if($target == 1) {
			$opendates=date_i18n( "Y/m/t" );
			$closedates=date_i18n( "Y/m/01" );
			$showmonth=date_i18n("[F, Y]");
		} elseif($target == 2) {
			$opendates=date_i18n( "Y/m/t", strtotime("+1 month") );
			$closedates=date_i18n( "Y/m/01", strtotime("+1 month") );
			$showmonth=date_i18n("[F, Y]", strtotime("+1 month"));
		} elseif($target == 3) {
			$opendates=date_i18n( "Y/m/t", strtotime("+2 month") );
			$closedates=date_i18n( "Y/m/01", strtotime("+2 month") );
			$showmonth=date_i18n("[F, Y]", strtotime("+2 month"));
		} elseif($target == 4) {
			$opendates=date_i18n( "Y/m/t", strtotime("+3 month") );
			$closedates=date_i18n( "Y/m/01", strtotime("+3 month") );
			$showmonth=date_i18n("[F, Y]", strtotime("+3 month"));
		}
	?>
	<?php
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '10',
			'cat'           => '-1',		// 未分類を除外
			'meta_key'		=> 'recommend',
			'orderby'		=> 'meta_value_num',
			'meta_query'	=> array(
				'relation'		=> 'AND',
				array(
					'key'		=> 'eventclose',	//カスタムフィールドのイベント終了日欄
					'value'		=> $closedates,		//イベント終了月を比較
					'compare'	=> '>=',			//対象月以降なら表示
				),
				array(
					'key'		=> 'eventopen',		//カスタムフィールドのイベント開催日欄
					'value'		=> $opendates,		//イベント開催月を比較
					'compare'	=> '<=',			//対象月以前なら表示
				),
			),
			'paged'=>$paged
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
