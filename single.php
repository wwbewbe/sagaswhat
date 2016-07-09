<?php get_header(); ?>

<div class="sub-header">
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
</div>

<div class="container">
<div class="contents">
	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

	<div class="kiji-tag">
	<?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
	</div>

	<h1><?php the_title(); ?></h1>

	<div class="kiji-body">
	<?php the_content(); ?>
	</div>

	<?php wp_link_pages( array(
		'before' => '<div class="pagination"><ul><li>',
		'separator' => '</li><li>',
		'after' => '</li></ul></div>',
		'pagelink' => '<span>%</span>'
	) ); ?>

	<?php if( has_category() ) {
		$cats = get_the_category();
		$catkwds = array();
		foreach($cats as $cat) {
			$catkwds[] = $cat->term_id;
		}
	} ?>
	<?php
	if ( $catkwds ) {
		$myposts = get_posts( array(
			'post_type' => 'post',
			'posts_per_page' => '4',
			'post__not_in' => array( $post->ID),
			'category__in' => $catkwds,
			'orderby' => 'rand',
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
		) );
	} else { $myposts = null; }
	if( $myposts ): ?>
	<aside class="mymenu mymenu-thumb mymenu-related">
	<h2><?php _e('Related events', 'SagasWhat'); ?></h2>
	<ul>
		<?php foreach($myposts as $post):
		setup_postdata($post); ?>
		<li><a href="<?php the_permalink(); ?>">
		<div class="thumb" style="background-image: url(<?php echo mythumb( 'medium' ); ?>)">
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

	</article>
	<?php endwhile; endif; ?>
</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
<?php // アクセス数の記録
/* Popular Postsを使うためコメント化
if( !is_bot() && !is_user_logged_in() ) {
	$count_key = 'postviews';
	$count = get_post_meta($post->ID, $count_key, true);
	$count++;
	update_post_meta($post->ID, $count_key, $count);
}
*/
?>
