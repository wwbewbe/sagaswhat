<?php get_header(); ?>

<div class="container">
<div class="contents">
	<div class="not-find">
		<h1>お探しのページは見つかりませんでした。</h1>
		<h2>お探しのページは見つかりませんでした。よろしければ検索してください。</h2>
		<?php get_search_form(); // 検索フォームを出力 ?>
	</div>
</div>

<div class="sub">
	<?php get_sidebar(); ?>
</div>
</div>

<?php get_footer(); ?>

<?php // アクセス数の記録
if( !is_bot() && !is_user_logged_in() ) {
	$count_key = 'postviews';
	$count = get_post_meta($post->ID, $count_key, true);
	$count++;
	update_post_meta($post->ID, $count_key, $count);
}
?>
