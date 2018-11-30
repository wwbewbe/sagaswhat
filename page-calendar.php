<?php get_header(); ?>

<div class="container">
<div class="contents">
	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<script type="text/javascript">
	jQuery(document).ready(function($){
	    $('.datepicker').datepicker({
			 dateFormat: 'yy/mm/dd',
			 minDate: '0y',
			 firstDay: '1',
			 onSelect: function(dateText, inst) {
				 $("#date_val").val(dateText);
//				 document.forms['send_date'].elements['input_date'].value=dateText;//選択した日付をPOST送信
//				 var div_element = document.createElement("div");//選択した日付を表示
//				 document.getElementById("show_date").innerHTML = '<h3><font color="#e90000">['+dateText+']</font></h3>';
			 }
		 });
	});
	</script>

	<div class="kiji-body">
	<div class="calendar">
	<form name="send_date" method="post" action="">
	<input type="hidden" name="input_date" id="date_val" value="">
	<div class="datepicker"></div>
	<input type="submit" id="search_date" value=<?php echo ('&#xf002;&nbsp;'.esc_html__('Search', 'SagasWhat')); ?>>
	</form>
	</div>
	<?php the_content(); ?>
	</div>

	<?php
	$searchdate = (isset($_POST['input_date'])) ? esc_html($_POST['input_date']) : '';
	if (!$searchdate) {
		$searchdate = date_i18n("Y/m/d");
	}
	?>
	<div id="show_date">
		<h3><i class="fa fa-calendar fa-fw"></i><?php echo (date_i18n(__('F j, Y', 'SagasWhat'), strtotime($searchdate))); ?></h3>
	</div>

	<?php
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	$args=array(
			'post_type'		=> 'post',
			'posts_per_page'=> '-1',				// 全件を表示
			'category__not_in' => array(1, $infocat->cat_ID), // カテゴリが未分類と観光案内所の記事は非表示
			'orderby'		=> array('meta_recommend'=>'DESC', 'meta_close'=>'ASC', 'title'=>'ASC'),//おすすめ度の高い順で表示
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
						'value'		=> $searchdate,		//イベント終了月を比較
						'compare'	=> '>=',			//対象月以降なら表示
						'type'		=> 'date',			//タイプに日付を指定
					),
				),
				'meta_open'=>array(
					'key'		=> 'eventopen',		//カスタムフィールドのイベント開催日欄
					'value'		=> $searchdate,		//イベント開催月を比較
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

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); ?>

	<?php get_template_part( 'gaiyou', 'medium' ); ?>

	<?php endwhile; endif; ?>

	<?php wp_reset_postdata(); ?>

	<?php endwhile; endif; ?>

	<aside class="mymenu-adsense">
		<?php echo get_adsense('infeed'); ?>
	</aside>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
