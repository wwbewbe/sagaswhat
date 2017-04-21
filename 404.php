<?php get_header(); ?>

<div class="container">
	<div class="contents">
		<div class="not-find">
			<h1>404</h1>
			<div class="msg">
				<h2>
					<?php echo esc_html(__('Sorry, the page you are looking for cannot be found.', 'SagasWhat')); ?>
				</h2>
				<?php echo esc_html(__('Please visit homepage or try searching.', 'SagasWhat')); ?>
				<?php get_search_form(); // 検索フォームを出力 ?>
			</div>
		</div>
	</div>

	<div class="sub">
	</div>
</div>

<?php get_footer(); ?>