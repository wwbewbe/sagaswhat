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

<?php
$args=array(
			'post_type'=>'post',/*投稿タイプ*/
			'posts_per_page'=>'5',/*投稿表示数*/
			'category_name'=>esc_attr($post->post_name),  // 'カテゴリースラッグ' => 'ページスラッグ'
			'paged'=>$paged
			);?>
<?php query_posts($args); ?>

	<?php if(have_posts()): while(have_posts()): 
	the_post(); ?>

	<?php get_template_part( 'gaiyou', 'medium' ); ?>

	<?php endwhile; endif; ?>

	<div class="pagination pagination-index">
	<?php echo paginate_links( array( 'type' => 'list',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;'
							 ) ); ?>
	</div>
</div>

<div class="sub">
	<?php get_sidebar(); ?>
<!--
	<aside class="mymenu mymenu-page">
	<h2>CONTENTS</h2>
	<?php wp_nav_menu( array( 'theme_location' => 'pagenav' ) ); ?>
	</aside>
-->
</div>
</div>

<?php get_footer(); ?>
