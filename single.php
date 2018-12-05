<?php get_header(); ?>

<div class="container">
<div class="contents">
	<div class="bread">
	<?php if( has_category() ): ?>
	<?php $postcats=get_the_category(); ?>
	<?php foreach( $postcats as $postcat ): ?>
	<ol>
		<li><a href="<?php echo home_url(); ?>">
		<i class="fa fa-home"></i><span>TOP</span>
		</a></li>

		<li>
		<?php echo get_category_parents( $postcat, true, '</li><li>' ); ?>
		<a><?php the_title(); ?></a>
		</li>
	</ol>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>

<?php $closed_imgid = get_closed_img();//イベント終了画像IDをメディアライブラリから取得 ?>

<?php if(have_posts()): while(have_posts()):
the_post(); ?>

<?php $post_id = get_the_ID();
$closedate = get_post_meta($post_id, 'eventclose', true);
$today = date_i18n("Y/m/d");
if (!empty($closedate)) { //すでにイベントが終了しているときはclosed imageにアイキャッチ画像変更
	if ((strtotime($closedate) < strtotime($today)) && (get_post_thumbnail_id($post_id) != $closed_imgid)) {
		update_post_meta( $post_id, $meta_key = '_thumbnail_id', $meta_value = $closed_imgid );
	}
} ?>
<?php if ((!empty($closedate)) && (strtotime($closedate) < strtotime($today)) && (!get_post_meta($post_id, 'stop-closealert', true))): ?>
	<span class="closealert">
	<i class="fa fa-close"></i><?php echo esc_html__('This event has closed.', 'SagasWhat'); ?>
	</span>
<?php endif; ?>

<article <?php post_class( 'kiji' ); ?>>

	<div class="kiji-tag">
	<?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
	</div>

	<h1><?php the_title(); ?></h1>

	<?php if (in_category('tourist-info-center')) : //TIC記事の場合アイキャッチ画像を表示 ?>
		<?php if (has_post_thumbnail()) : ?>
			<div class="catch wp-caption">
			<?php the_post_thumbnail();
			echo '<p class="wp-caption-text">' . get_post( get_post_thumbnail_id() )->post_excerpt . '</p>'; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="mymenu-adsense">
		<?php echo get_adsense('inpost'); ?>
	</div>

	<div class="kiji-body">
	<?php the_content(); ?>
	</div>

	<?php wp_link_pages( array(
		'before' => '<div class="pagination"><ul><li>',
		'separator' => '</li><li>',
		'after' => '</li></ul></div>',
		'pagelink' => '<span>%</span>'
	) ); ?>

	<?php // Related Events menu on each Post
	if( has_category() ) {
		$cats = get_the_category();
		$catkwds = array();
		foreach($cats as $cat) {
			$catkwds[] = $cat->term_id;
		}
	} ?>
	<?php
	if ( $catkwds ) {
		$myposts = get_posts( array(
			'post_type'		=> 'post',
			'posts_per_page'=> '8',
			'post__not_in'	=> array( $post->ID),
			'category__in'	=> $catkwds,
			'orderby'		=> 'rand',
			'meta_query'	=> get_meta_query_args('0','0'),
		) );
	} else { $myposts = null; }
	if( $myposts ): ?>
	<aside class="mymenu mymenu-thumb mymenu-related">
	<h2><?php echo esc_html(__('Related Events', 'SagasWhat')); ?></h2>
	<ul>
		<?php foreach($myposts as $post):
		setup_postdata($post); ?>
		<li><a href="<?php the_permalink(); ?>">
		<div class="thumb" style="background-image: url(<?php echo mythumb( 'full' ); ?>)">
		</div>
		<div class="text">
		<?php the_title(); ?>
		</div>
		</a></li>
		<?php endforeach; ?>
	</ul>
	</aside>
	<?php wp_reset_postdata();
	endif; ?>

	<?php get_template_part( 'nearby', 'events' ); //Nearby Events list function ?>
	<?php get_template_part( 'nearby', 'tic' ); //Nearby TIC list function ?>

</article>
<?php endwhile; endif; ?>

</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
