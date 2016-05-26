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
	$args = array(
    	'post_type'			=> 'post',
    	'posts_per_page'	=> '5',
		'paged'				=> $paged
	);
	$the_query = new WP_Query($args); ?>

	<?php if($the_query->have_posts()): while($the_query->have_posts()):
	$the_query->the_post(); ?>

		<?php get_template_part( 'gaiyou', 'medium' ); ?>

	<?php endwhile; endif; ?>

	<div class="pagination pagination-index">
	<?php echo paginate_links( array( 'type' => 'list',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
							'total'		=> $the_query->max_num_pages
							 ) ); ?>
	</div>
	<?php wp_reset_postdata(); ?>

	<div class="share">
	<ul>
	<li><a href="https://twitter.com/intent/tweet?text=<?php echo urlencode( get_the_title() . ' - ' . get_bloginfo('name') ); ?>&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;via=sagaswhat"
	onclick="window.open(this.href, 'SNS', 'width=500, height=300, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-tw">
    	<i class="fa fa-twitter"></i>
    	<span>share&nbsp;by</span>&nbsp;Twitter
	</a></li>
	<li><a href="http://www.facebook.com/share.php?u=<?php echo urlencode( get_permalink() ); ?>"
	onclick="window.open(this.href, 'SNS', 'width=500, height=500, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-fb">
    	<i class="fa fa-facebook"></i>
    	<span>share&nbsp;by</span>&nbsp;Facebook
	</a></li>
	<li><a href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink() ); ?>"
	onclick="window.open(this.href, 'SNS', 'width=500, height=500, menubar=no, toolbar=no, scrollbars=yes'); return false;" class="share-gp">
    	<i class="fa fa-google-plus"></i>
    	<span>share&nbsp;by</span>&nbsp;Google+
	</a></li>
	</ul>
	</div><!-- end share -->
</div><!-- end contents -->
<div class="sub">
	<?php get_sidebar(); ?>
</div><!-- end sub -->
</div><!-- end container -->

<?php get_footer(); ?>
