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
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '10',
			'category_name'	=> esc_attr($post->post_name),  // 'カテゴリースラッグ' => 'ページスラッグ'
			'meta_key'		=> 'recommend',
			'orderby'		=> 'meta_value_num',
			'meta_query'	=> array(
				'relation'		=> 'OR',
				array(
					'relation'		=> 'AND',
					array(
						'key'		=> 'eventclose',
						'compare'	=> 'NOT EXISTS',
					),
					array(
						'key'		=> 'eventopen',			//カスタムフィールドのイベント開催日欄
						'value'		=> date_i18n( "Y/m/d" ),//イベント開催日を今日と比較
						'compare'	=> '<=',				//今日以前なら表示
					),
				),
				array(
					'relation'		=> 'AND',
					array(
						'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
						'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
						'compare'	=> '>=',				//今日以降なら表示
					),
					array(
						'key'		=> 'eventopen',
						'compare'	=> 'NOT EXISTS',
					),
				),
				array(
					'reration'		=> 'AND',
					array(
						'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
						'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
						'compare'	=> '>=',				//今日以降なら表示
					),
					array(
						'key'		=> 'eventopen',			//カスタムフィールドのイベント開催日欄
						'value'		=> date_i18n( "Y/m/d" ),//イベント開催日を今日と比較
						'compare'	=> '<=',				//今日以前なら表示
					),
				),
			),
			'paged'=>$paged
		); ?>
	<?php $the_query = new WP_Query($args); ?>

	<h1><?php the_title(); ?></h1>

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
