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

	<?php if(have_posts()): while(have_posts()):
	the_post(); ?>
	<article <?php post_class( 'kiji' ); ?>>

	<div class="kiji-tag">
	<?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
	</div>

	<h1><?php the_title(); ?></h1>

	<div class="kiji-date">
	<i class="fa fa-pencil"></i>

	<time
	datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
	投稿：<?php echo get_the_date(); ?>
	</time>

	<?php if( get_the_modified_date( 'Ymd' ) > get_the_date( 'Ymd' ) ): ?>
	｜
	<time
	datetime="<?php echo get_the_modified_date( 'Y-m-d' ); ?>">
	更新：<?php echo get_the_modified_date(); ?>
	</time>
	<?php endif; ?>
	</div>

	<?php if( has_post_thumbnail() && $page==1 ): ?>
	<div class="catch">
	<?php the_post_thumbnail( 'large' ); ?>
	<p class="wp-caption-text">
	<?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?>
	</p>
	</div>
	<?php endif; ?>

	<div class="ranking">
	<?php the_content(); ?>
	</div>

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

	</article>
	<?php endwhile; endif; ?>
</div><!-- end contents -->

<div class="sub">
	<?php get_sidebar(); ?>
</div>

</div><!-- end container -->

<?php get_footer(); ?>
