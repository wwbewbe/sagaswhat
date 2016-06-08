<?php get_header(); ?>

<div class="container">
<div class="contents">
	<div class="not-find">
	<div class="msg">
		<h2>This is not the web page you are looking for.<br /><br />Please use below search form to find the page.</h2>
		<?php get_search_form(); // 検索フォームを出力 ?>
	</div>
	</div>
</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>
