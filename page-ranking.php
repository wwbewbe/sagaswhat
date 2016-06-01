<?php get_header(); ?>

<div class="container">

<?php if( get_header_image() ): ?>
<div class="hero">
	<div class="hero-img" style="background-image: url(<?php header_image(); ?>)"></div>
	<div class="hero-text">
	<?php bloginfo( 'description' ); ?>
	</div>
</div>
<?php endif; ?>

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
							'compare'	=> '<=', 				//今日以前ならアイキャッチ画像を変更
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

	<div class="kiji-date">
	<i class="fa fa-pencil"></i>

	<time
	datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
	投稿：<?php echo get_the_date(); ?>
	</time>

	<?php if( get_the_modified_date( 'Ymd' ) > get_the_date( 'Ymd' ) ): ?>
	｜
	<time
	datetime="<?php echo get_the_modified_date( 'Y-m-d' ); ?>">
	更新：<?php echo get_the_modified_date(); ?>
	</time>
	<?php endif; ?>
	</div>

	<?php if( has_post_thumbnail() && $page==1 ): ?>
	<div class="catch">
	<?php the_post_thumbnail( 'large' ); ?>
	<p class="wp-caption-text">
	<?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?>
	</p>
	</div>
	<?php endif; ?>

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
