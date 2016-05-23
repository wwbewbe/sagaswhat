<?php get_header(); ?>

<div class="sub-header">
	<div class="bread">
	<ol>
		<li><a href="<?php echo home_url(); ?>">
		<i class="fa fa-home"></i><span>TOP</span>
		</a></li>

		<li>
		<a>検索結果</a>
		</li>
	</ol>
	</div>
</div>

<div class="container">
<div class="contents">

	<h1>"<?php echo get_search_query(); ?>"に一致した記事</h1>

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
</div>
</div>

<?php get_footer(); ?>
