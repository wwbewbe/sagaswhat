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

	<div class="kiji-body">
	<?php the_content(); ?>
	</div>

	<?php wp_link_pages( array(
		'before' => '<div class="pagination"><ul><li>',
		'separator' => '</li><li>',
		'after' => '</li></ul></div>',
		'pagelink' => '<span>%</span>'
	) ); ?>

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
</div>

<div class="sub">
	<aside class="mymenu mymenu-page">
	<h2>CONTENTS</h2>
	<?php wp_nav_menu( array( 'theme_location' => 'pagenav' ) ); ?>
	</aside>
</div>
</div>

<?php get_footer(); ?>
