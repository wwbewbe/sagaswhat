<?php get_header(); ?>

<div class="container">

<div class="contents">

	<?php
	$args=array(
				'post_type'		=> 'post',
				'posts_per_page'=> '-1',	// 全件チェック
				'cat' 			=> '-1',	// カテゴリが未分類の記事は非表示
				'meta_query'	=> array(
					array(
						array(
							'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
							'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
							'compare'	=> '<', 				//昨日以前ならアイキャッチ画像を変更
						),
					),
				),
			); ?>
	<?php $the_query = new WP_Query($args); ?>

		<?php if($the_query->have_posts()): while($the_query->have_posts()):
		$the_query->the_post(); ?>

			<?php
			update_post_meta( $the_query->post->ID, $meta_key = '_thumbnail_id', $meta_value = null );
			?>

		<?php endwhile; endif; ?>

		<?php wp_reset_postdata(); ?>

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

	<div class="kiji-tag">
	<?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
	</div>

	<h1><?php the_title(); ?></h1>

	<div class="ranking">

	<?php the_content(); ?>
	</div>

	</article>
	<?php endwhile; endif; ?>
</div><!-- end contents -->

<div class="sub">
	<?php get_sidebar(); ?>
</div>

</div><!-- end container -->

<?php get_footer(); ?>
