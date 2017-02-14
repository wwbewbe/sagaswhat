<div class="taglist">
<?php
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
		'post_type'		=> 'sw_val',
		'posts_per_page'=> $list,
		'paged'			=> $paged,
		'orderby'		=> array('meta_close'=>'asc'),//おすすめ度の高い順＆終了日が近い順に表示
		'meta_query'	=> array(
			'meta_close'=>array(
				'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
				'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
				'compare'	=> '>=',				//今日以降なら表示
				'type'		=> 'date',				//タイプに日付を指定
			),
		),
	);
?>
<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

	<?php get_template_part( 'gaiyou', 'valuable' ); ?>

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
