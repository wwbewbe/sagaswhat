<?php get_header(); ?>

<div class="container">
<div class="contents">
	<div class="not-find">
	<div class="msg">
		<h2><?php _e('This is not the web page you are looking for.', 'SagasWhat'); ?><br /><br /><?php _e('Please use below search form to find the page.', 'SagasWhat'); ?></h2>
		<?php get_search_form(); // 検索フォームを出力 ?>
	</div>
	</div>
</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
