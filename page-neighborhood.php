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
$myposts = get_posts( array(
    'post_type' => 'post',
    'posts_per_page' => '6',
    'meta_key' => 'postviews',
    'orderby' => 'meta_value_num'
) );
if( $myposts ): ?>
<article <?php post_class( 'gaiyou' ); ?>>
<ul>
    <?php foreach($myposts as $post):
    setup_postdata($post); ?>
    <li><a href="<?php the_permalink(); ?>">
    <img src="<?php echo mythumb( 'medium' ); ?>" alt="">
    <div class="text">
        <h1><?php the_title(); ?></h1>
        <div class="kiji-date">
        <i class="fa fa-pencil"></i>
        <time
        datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
        投稿：<?php echo get_the_date(); ?>
        </time>
        </div>
        <?php the_excerpt(); ?>
    </div>
    </a></li>
    <?php endforeach; ?>
</ul>
<div class="pagination pagination-index">
<?php echo paginate_links( array( 'type' => 'list',
						'prev_text' => '&laquo;',
						'next_text' => '&raquo;',
						'total'		=> $the_query->max_num_pages
						 ) ); ?>
</div>
<?php wp_reset_postdata();
endif; ?>
</article>

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
</div>

</div><!-- end contents -->

<div class="sub">
	<?php get_sidebar(); ?>
</div>

</div><!-- end container -->

<?php get_footer(); ?>
