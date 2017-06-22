<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
  <meta charset="<?php bloginfo('charset'); ?>">
  <title>
  <?php wp_title( '|', true, 'right'); ?>
  <?php bloginfo( 'name' ); ?>
  </title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Averia+Serif+Libre:300,400,700|Oleo+Script:400,700|Open+Sans+Condensed:300,700|Open+Sans:300,400,700,800|Roboto+Condensed:300,400,700|Roboto+Slab:300,400,700|Roboto:300,400,700,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/earlyaccess/mplus1p.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/earlyaccess/roundedmplus1c.css" rel="stylesheet" />

  <link rel="stylesheet"
   href="<?php echo get_stylesheet_uri(); ?>?ver=<?php echo date('U'); ?>">

  <?php if( is_home() || is_front_page() ): // トップページ用のメタデータ ?>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php bloginfo( 'name' ); ?>">
    <meta property="og:url" content="<?php echo esc_url(home_url( '/' )); ?>">
    <meta property="og:description" content="<?php echo esc_attr( wp_trim_words( $post->post_excerpt, 100, '…' ) ); ?>">
    <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/sagaswhat-top.png">
  <?php //endif; // トップページ用のメタデータ【ここまで】 ?>

  <?php elseif( is_single() || is_page() ): //記事の個別ページ用のメタデータ ?>
    <meta name="description" content="<?php echo wp_trim_words( $post->post_content, 100, '…' ); ?>">

    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php the_title(); ?>">
    <meta property="og:url" content="<?php the_permalink(); ?>">
    <meta property="og:description" content="<?php echo esc_attr( wp_trim_words( $post->post_excerpt, 100, '…' ) ); ?>">
    <meta property="og:image" content="<?php echo mythumb( 'large' ); ?>">
  <?php endif; //記事の個別ページ用のメタデータ【ここまで】?>

  <?php if( is_category() || is_tag() ): // カテゴリー・タグページ用のメタデータ ?>
    <?php if( is_category() ) {
        $termid = $cat;
        $taxname = 'category';
    } elseif( is_tag() ) {
        $termid = $tag_id;
        $taxname = 'post_tag';
    } ?>
    <meta name="description" content="<?php single_term_title(); ?><?php echo esc_attr(__(' Events List', 'SagasWhat')); ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php single_term_title(); ?> | <?php bloginfo( 'name' ); ?>">
    <meta property="og:url" content="<?php echo get_term_link( $termid, $taxname ); ?>">
    <meta property="og:description" content="<?php single_term_title(); ?><?php echo esc_attr(__(' Events List', 'SagasWhat')); ?>">
    <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/sagaswhat-top.png">
  <?php endif; // カテゴリ・タグページ用のメタデータ【ここまで】 ?>

  <meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>">
  <meta property="og:locale" content="ja_JP">
  <meta property="og:locale:alternate" content="en_US">
  <meta property="og:locale:alternate" content="en_GB">
  <meta property="og:locale:alternate" content="zh_TW">

  <meta property="fb:app_id" content="1229488633736438">

  <meta name="twitter:site" content="@sagaswhat">
  <meta name="twitter:card" content="summary_large_image">

  <?php wp_head(); ?>
  <script>
  	jQuery(function(){
  		jQuery('img').attr('onmousedown', 'return false');
  		jQuery('img').attr('onselectstart', 'return false');
  		jQuery('img').attr('oncontextmenu', 'return false');
  	});
  </script>
</head>
<body <?php body_class(); ?>>

  <header>
    <div class="header-inner">
      <div class="site">
      <h1><a href="<?php echo esc_url(home_url()); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/sagaswhat-site.png"
      alt="<?php bloginfo( 'name' ); ?>" width="100" height="25">
      </a></h1>
      </div>

      <div class="sitenav">
      <button type="button" id="navbtn">
      <i class="fa fa-bars"></i><span>MENU</span>
      </button>
      <?php wp_nav_menu( array(
              'theme_location' => 'sitenav',
              'container' => 'nav',
              'container_class' => 'mainmenu',
              'container_id' => 'mainmenu'
      ) ); ?>
      </div>
    </div>
  </header>
  <?php if ( !is_home() && !is_front_page() && !is_single() ) : ?>
    <div class="sub-header">
      <aside class="mymenu-adsense">
      <?php echo get_adsense(true); ?>
      </aside>
      <?php the_ticker_event(); ?>
    </div>
  <?php endif; ?>
