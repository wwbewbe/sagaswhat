<?php

//多言語対応
load_theme_textdomain( 'SagasWhat', get_template_directory() . '/languages' );

//概要（抜粋）の文字数
function my_length($length) {
	return 50;
}
add_filter('excerpt_mblength', 'my_length');

//概要（抜粋）の英語文字(単語)数
function en_length($length) {
	return 20;
}
add_filter('excerpt_length', 'en_length');

//概要（抜粋）の省略記号
function my_more($more) {
	return '…';
}
add_filter('excerpt_more', 'my_more');

//抜粋欄を使用した時の抜粋文の文字制限
function my_the_excerpt($myexcerpt) {
    $myexcerpt = mb_strimwidth($myexcerpt, 0, 160, "…", "UTF-8");
    return $myexcerpt;
}
add_filter('the_excerpt', 'my_the_excerpt');

//コンテンツの最大幅
if ( !isset( $content_width )) {
	$content_width = 747;
}

//YouTubeのビデオ：<div>でマークアップ
function ytwrapper($return, $data, $url) {
		if ($data->provider_name == 'YouTube') {
			return '<div class="ytvideo">'.$return.'</div>';
		} else {
			return $return;
		}
}
add_filter('oembed_dataparse', 'ytwrapper',10,3);

//YouTubeのビデオ：キャッシュをクリア
//function clear_ytwrapper($post_id) {
//		global $wp_embed;
//		$wp_embed->delete_oembed_caches($post_id);
//}
//add_action('pre_post_update', 'clear_ytwrapper');

//アイキャッチ画像
add_theme_support( 'post-thumbnails' );

// 編集画面の設定
function editor_setting($init) {
	$init[ 'block_formats' ] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre';

	$style_formats = array(
		array( 'title' => 'Tips info',
			'block' => 'div',
			'classes' => 'point',
			'wrapper' => true ),
		array( 'title' => 'Attention',
			'block' => 'div',
			'classes' => 'attention',
			'wrapper' => true ),
		array( 'title' => 'Highlight',
			'inline' => 'span',
			'classes' => 'highlight') );
	$init[ 'style_formats' ] = json_encode( $style_formats );

	return $init;
}
add_filter( 'tiny_mce_before_init', 'editor_setting');

//スタイルメニューを有効化
function add_stylemenu( $buttons ) {
			array_splice( $buttons, 1, 0, 'styleselect' );
			return $buttons;
}
add_filter( 'mce_buttons_2', 'add_stylemenu' );

// エディタスタイルシート
add_editor_style( get_template_directory_uri() . '/editor-style.css?ver=' . date( 'U' ) );
add_editor_style( '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );

// サムネイル画像
function mythumb( $size ) {
	global $post;

	if ( has_post_thumbnail() ) {
		$postthumb = wp_get_attachment_image_src( get_post_thumbnail_id(), $size );
		$url = $postthumb[0];
	} elseif( preg_match( '/wp-image-(\d+)/s', $post->post_content, $thumbid ) ) {
		$postthumb = wp_get_attachment_image_src( $thumbid[1], $size );
		$url = $postthumb[0];
	} else {
		$url = get_template_directory_uri() . '/noimage.png';
	}
	return $url;
}

// Upcoming Listのサムネイルを記事内に添付した画像から取得
function get_image_url($size, $count) {
	global $post;
	$count--;
	if (preg_match_all( '/wp-image-(\d+)/s', $post->post_content, $thumbid)) {
		while (!isset($thumbid[1][$count])) {
			$count--;
			if ($count < 0) break;
		}
		if ($count > -1) {
			$postthumb = wp_get_attachment_image_src( $thumbid[1][$count], $size );
			$url = $postthumb[0];
		}
	}
	return $url;
}

// Upcoming Listのサムネイル画像をメディアライブラリから取得(URLを返却)
function get_upcoming_image($count) {
	$attachments = get_children(array('post_type' => 'attachment', 'post_mime_type' => 'image'));
	if(!empty($attachments)){
		$thismonth=date_i18n('F');//This Month
		$nextmonth=date_i18n('F', strtotime(date_i18n('Y/m/1') . '+1 month'));//Next Month
		$twomonth=date_i18n('F', strtotime(date_i18n('Y/m/1') . '+2 month'));//2 Month Later
		$threemonth=date_i18n('F', strtotime(date_i18n('Y/m/1') . '+3 month'));//3 Month Later
		foreach($attachments as $attachment){
			switch($count) {
			case '1':	//This Month
				if($attachment->post_title == $thismonth) {
					$url = wp_get_attachment_url($attachment->ID);
				}
				break;
			case '2':	//Next Month
				if($attachment->post_title == $nextmonth) {
					$url = wp_get_attachment_url($attachment->ID);
				}
				break;
			case '3':	//2 Month Later
				if($attachment->post_title == $twomonth) {
					$url = wp_get_attachment_url($attachment->ID);
				}
				break;
			case '4':	//3 Month Later
				if($attachment->post_title == $threemonth) {
					$url = wp_get_attachment_url($attachment->ID);
				}
				break;
			}
		}
	}
	return $url;
}

// カスタムメニュー
register_nav_menu( 'sitenav', 'Site Navigation' );
register_nav_menu( 'pickupnav', 'Pickup Posts' );
register_nav_menu( 'pagenav', 'Page Navigation' );
register_nav_menu( 'categorynav', 'Category Menu' );
register_nav_menu( 'newsnav', 'News' );
register_nav_menu( 'upcomingnav', 'Upcoming Menu' );
register_nav_menu( 'nearbynav', 'Nearby Menu' );

// トグルボタン
function navbtn_scripts() {
	wp_enqueue_script( 'navbtn-script', get_template_directory_uri() .'/js/navbtn.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'navbtn_scripts' );

// Calendar(JQuery datepicker使用)
function calendar_scripts(){
	global $wp_scripts;
	$ui = $wp_scripts->query('jquery-ui-core');

	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-css', "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/redmond/jquery-ui.css");
	wp_enqueue_style('jquery-ui-custom-css', get_template_directory_uri() .'/js/jquery-ui-custom.css');
	if (get_bloginfo('language') == 'ja') {
		wp_enqueue_script('jquery-ui-js-ja', '//ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js');
	}
}
add_action( 'wp_enqueue_scripts', 'calendar_scripts' );

// Infinite-scrollを使用
function scroll_scripts() {
	wp_enqueue_script( 'scroll-script', get_template_directory_uri() .'/js/jquery.infinitescroll.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'scroll_scripts' );

// Geolocationを使用
function geoloc_scripts() {
	wp_enqueue_script( 'geoloc-script', get_template_directory_uri() .'/js/geoloc.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'geoloc_scripts' );

// 住所 → 緯度/経度変換
function strAddrToLatLng( $strAddr ) {
    $strRes = file_get_contents(
         'http://maps.google.com/maps/api/geocode/json'
        . '?address=' . urlencode( mb_convert_encoding( $strAddr, 'UTF-8' ) )
        . '&sensor=false'
    );
    $aryGeo = json_decode( $strRes, TRUE );
    if ( !isset( $aryGeo['results'][0] ) )
        return '';

    $strLat = (string)$aryGeo['results'][0]['geometry']['location']['lat'];
    $strLng = (string)$aryGeo['results'][0]['geometry']['location']['lng'];
	$LatLng = array('Lat'=>$strLat, 'Lng'=>$strLng);
	return $LatLng;
}

// 前後の記事に関するメタデータの出力を禁止(for FireFox)
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// クローラー（BOT）からのアクセスを判別
function is_bot() {
	$ua = $_SERVER['HTTP_USER_AGENT'];

	$bots = array(
		"googlebot",
		"msnbot",
		"yahoo"
	);
	foreach( $bots as $bot ) {
		if (stripos( $ua, $bot ) !== false) {
		    return true;
		}
	}
	return false;
}

// ウィジェットエリア
register_sidebar( array(
	'id' => 'submenu-post',
	'name' => 'SubMenu(post)',
	'description' => 'setting widget on side bar.',
	'before_widget' => '<aside id="%1$s" class="mymenu widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>'
) );

register_sidebar( array(
	'id' => 'submenu-page',
	'name' => 'SubMenu(page)',
	'description' => 'setting widget on side bar for page.',
	'before_widget' => '<aside id="%1$s" class="mymenu widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>'
) );

register_sidebar( array(
	'id' => 'ad',
	'name' => 'Advertisement',
	'description' => 'advertising on side bar.',
	'before_widget' => '<aside id="%1$s" class="myad mymenu widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>'
) );

// 検索フォーム
add_theme_support( 'html5', array( 'search-form' ) );

// ヘッダー画像
add_theme_support( 'custom-header', array(
			'width' => 1000,
			'height' => 300,
			'header-text' => false
) );

// カスタムフィールド（イベント情報）
function event_info_to_the_content( $content ) {
	global $post;

	if ( !is_admin() && is_main_query() && is_single() ) {
		$adsense = '<aside class="mymenu-adsense">' . get_adsense(true) . '</aside>';
		// イベント名
		if( $eventname = esc_html( get_post_meta($post->ID, 'eventname', true) ) ) {
			$thname = esc_html__('Event name', 'SagasWhat');
			if( $url = esc_html( get_post_meta($post->ID, 'url', true) ) ) {
				$info = $info . '<tr><th>'.$thname.'</th><td><a href="'.$url.'" target="_blank">' . $eventname . '</a></td></tr>';
			} else {
				$info = $info . '<tr><th>'.$thname.'</th><td>' . $eventname . '</td></tr>';
			}
		}
		// 会場・場所
		if( $venue = esc_html( get_post_meta($post->ID, 'venue', true) ) ) {
			$thname = esc_html__('Venue/Location', 'SagasWhat');
			if ( $venueurl = esc_html( get_post_meta($post->ID, 'venueurl', true) ) ) {
				$info = $info . '<tr><th>'.$thname.'</th><td><a href="'.$venueurl.'" target="_blank">' . $venue . '</a></td></tr>';
			} else {
				$info = $info . '<tr><th>'.$thname.'</th><td>' . $venue . '</td></tr>';
			}
		}
		// 開催期間
		$eventopen = esc_html( get_post_meta($post->ID, 'eventopen', true) );
		$eventclose = esc_html( get_post_meta($post->ID, 'eventclose', true) );
		$thname = esc_html__('Dates', 'SagasWhat');
		if($eventopen && $eventclose) {
			if($eventopen == $eventclose) {
				if ( get_bloginfo('language') == 'ja' ) {
					$dates = date_i18n('Y年n月j日(D)', strtotime($eventclose));
				} else {
					$dates = date_i18n('l, F jS, Y', strtotime($eventclose));
				}
				$info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
			} else {
				if ( get_bloginfo('language') == 'ja' ) {
					$eventopen = date_i18n('Y年n月j日(D)', strtotime($eventopen));
					$eventclose = date_i18n('Y年n月j日(D)', strtotime($eventclose));
				} else {
					$eventopen = date_i18n('l, F jS', strtotime($eventopen));
					$eventclose = date_i18n('l, F jS, Y', strtotime($eventclose));
				}
				$dates = $eventopen . ' ~ ' . $eventclose;
				$info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
			}
		} elseif($eventopen || $eventclose) {
			if ($eventopen) {
				if ( get_bloginfo('language') == 'ja' ) {
					$eventopen = date_i18n('Y年n月j日(D)', strtotime($eventopen));
				} else {
					$eventopen = date_i18n('l, F jS', strtotime($eventopen));
				}
			}
			if ($eventclose) {
				if ( get_bloginfo('language') == 'ja' ) {
					$eventclose = date_i18n('Y年n月j日(D)', strtotime($eventclose));
				} else {
					$eventclose = date_i18n('l, F jS, Y', strtotime($eventclose));
				}
			}
			$dates = $eventopen . ' ~ ' . $eventclose;
			$info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
		}
		// 注記
		$thname = esc_html(__('Note', 'SagasWhat'));
		if( $note = esc_html( get_post_meta($post->ID, 'note', true) ) ) {
			if ( $noteurl = esc_html( get_post_meta($post->ID, 'noteurl', true) ) ) {
				$note = $note.'<div><a href="'.$noteurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
			}
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$note.'</td></tr>';
		}
		// 営業時間
		$thname = esc_html__('Open hours', 'SagasWhat');
		if( $bizhours = esc_html( get_post_meta($post->ID, 'bizhours', true) ) ) {
			if ( $bizhoursurl = esc_html( get_post_meta($post->ID, 'bizhoursurl', true) ) ) {
				$bizhours = $bizhours.'<div><a href="'.$bizhoursurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
			}
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$bizhours.'</td></tr>';
		}
		// 入場料
		$thname = esc_html__('Admission', 'SagasWhat');
		if( $price = esc_html( get_post_meta($post->ID, 'price', true) ) ) {
			if ( $priceurl = esc_html( get_post_meta($post->ID, 'priceurl', true) ) ) {
				$price = $price.'<div><a href="'.$priceurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
			}
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$price.'</td></tr>';
		}
		// 住所
		$thname = esc_html__('Address', 'SagasWhat');
		if( $showaddress = esc_html( get_post_meta($post->ID, 'showaddress', true) ) ) {
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$showaddress.'</td></tr>';
		}
		// 問い合わせ
		$thname = esc_html__('Contact', 'SagasWhat');
		if( $telephone = esc_html( get_post_meta($post->ID, 'telephone', true) ) ) {
			if ( $telephoneurl = esc_html( get_post_meta($post->ID, 'telephoneurl', true) ) ) {
				$telephone = $telephone.'<div><a href="'.$telephoneurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
			}
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$telephone.'</td></tr>';
		}

		$table = '<table class="event-info"><tbody>' . $info . '</tbody></table>';

		return $content . $adsense . $table;
	} else {
		return $content;
	}
}
add_action( 'the_content', 'event_info_to_the_content', 1 );

// 現在イベントが終了しているかどうかをチェック(ループのパラメータで指定できたので未使用)
function is_event_close() {
    global $post;

	// カスタムフィールドから年月日を取得（Y/m/d、y-m-dなど）
    if( $eventopen = esc_html( get_post_meta($post->ID, 'eventopen', true) ) ) {
        $opendate = strtotime($eventopen);
    } else {
		$opendate = null;
	}
    if ($eventclose = esc_html( get_post_meta($post->ID, 'eventclose', true) ) ) {
        $closedate = strtotime($eventclose);
	} else {
		$closedate = null;
	}
	// $opendate, $closedateの値をチェック
    // 正しい年月日ならUnixのタイムスタンプに
    $dates = array( "opendate" => $opendate, "closedate" => $closedate );
    foreach ($dates as $key => $val) {
            // 正しい日付かどうかチェック（違うときはnullで終了）
			$dates_Y = idate('Y', $val);
            $dates_M = idate('m', $val);
            $dates_D = idate('d', $val);
            if (!checkdate($dates_M, $dates_D, $dates_Y )) {
                $dates[$key] = null;
                continue;
            }
            // mktimeでUnixのタイムスタンプに
			$dates[$key] = mktime(0, 0, 0, $dates_M, $dates_D, $dates_Y);
    }
    $nowdate = date_i18n('U'); // 現在の時間を取得しUnixのタイムスタンプに
    if ( ($dates["opendate"] == null) && ($dates["closedate"] == null)) {
        return true;
    } elseif ($dates["closedate"] == null) {
    	if ($nowdate >= $dates["opendate"]) {
        	return true;
    	} else {
			return false;
		}
    } elseif ($dates["opendate"] == null) {
        if ($nowdate <= $dates["closedate"]) {
            return true;
        } else {
			return false;
		}
    } elseif ( ($nowdate >= $dates["opendate"]) && ($nowdate <= $dates["closedate"]) ) {
        return true;
    } else {
		return false;
	}
}

// タグリストを表示するショートコード
function set_taglist($params = array()) {
    extract(shortcode_atts(array(
        				'file' => 'taglist',
						'tagname' => 0,
						'list' => 5,
    					), $params));
    ob_start();
    include(get_theme_root() . '/' . get_template() . "/$file.php");
    return ob_get_clean();
}
add_shortcode('taglist', 'set_taglist');

// Google Adsenseを表示するショートコード
function showads($params = array()) {
	extract(shortcode_atts(array(
        				'line' => 'off',
						'type' => 'rectangle',
    					), $params));
//adsense記事用（英語版1）
	$adcode = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-post -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="6381598810"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
//adsense記事用（英語版2）
	$adcode2 = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-post-2 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="2100370814"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
//adsense記事用（日本語版1）
	$adcodejp = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-post-jp -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="9474666013"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
//adsense記事用（日本語版2）
	$adcodejp2 = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-post-jp-2 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="8678283616"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
//adsenseレスポンシブデザイン（英語版）
	$adres = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-responsive-2 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="6670171218"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
//adsenseレスポンシブデザイン（日本語版）
	$adresjp = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-responsive-jp-2 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="1155016815"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

	if ( get_bloginfo('language') == 'ja' ) {
		if (($line == 'on') && ($type == 'rectangle')) {
			return $adcodejp . $adcodejp2;
		} elseif ($type == 'responsive') {
			return $adresjp;
		} else {
	    	return $adcodejp;
		}
	} else {
		if (($line == 'on') && ($type == 'rectangle')) {
			return $adcode . $adcode2;
		} elseif ($type == 'responsive') {
			return $adres;
		} else {
	    	return $adcode;
		}
	}
}
add_shortcode('adsense', 'showads');

function get_adsense($kiji=false) {
	$title = esc_html(__('Sponsored Links', 'SagasWhat'));
	if ($kiji) {
		$title = '<h4>'.$title.'</h4>';
	} else {
		$title = '<h2>'.$title.'</h2>';
	}
	//レスポンシブ広告の英語版もしくは日本語版の挿入
	if ( get_bloginfo('language') == 'ja' ) {
		if ($kiji) {
			$adsense = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-responsive-jp-2 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="1155016815"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
		} else {
			$adsense = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-responsive-jp-3 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="5585216411"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
		}
	} else {
		if ($kiji) {
			$adsense = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-responsive-2 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="6670171218"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
		} else {
			$adsense = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-responsive-3 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="4108483211"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
		}
	}
	return $title.$adsense;
}

// カテゴリ・タグ・検索の一覧表示のクエリー設定
function QueryListFilter($query) {
	if (!is_admin() && $query->is_main_query() && $query->is_search()) {
		$query->set('post_type', 'post');			// 投稿記事を対象
		$query->set('posts_per_page', '10');		// 一覧表示数
		$query->set('category__not_in', array(1));	// 未分類のカテゴリを非表示
		$query->set('orderby', array('meta_recommend'=>'desc'));	// 推奨値の高い順
		$query->set('meta_query', array(
//						'relation'		=> 'AND',
//						'meta_close'=>array(
//							'relation'		=> 'OR',
//							array(
//								'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
//								'compare'	=> 'NOT EXISTS',		//カスタムフィールドがない場合も表示
//							),
//							array(
//								'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
//								'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
//								'compare'	=> '>=',				//今日以降なら表示
//								'type'		=> 'date',				//タイプに日付を指定
//							),
//						),
						'meta_recommend'=>array(
							'key'		=> 'recommend',				//カスタムフィールドのおすすめ度
							'value'		=> 0,						//
							'compare'	=> '>=',					//指定のおすすめ度以上を表示
							'type'		=> 'numeric',				//タイプに数値を指定
						),
					));
	} elseif ( !is_admin() && $query->is_main_query() && ($query->is_tag() || $query->is_category()) ) {
		$query->set('post_type', 'post');			// 投稿記事を対象
		$query->set('posts_per_page', '10');		// 一覧表示数
		$query->set('category__not_in', array(1));	// 未分類のカテゴリを非表示
		$query->set('orderby', array('meta_recommend'=>'desc', 'meta_open'=>'asc'));	// 推奨値の高い順
		$query->set('meta_query', get_meta_query_args());
	}
	return $query;
}
add_action('pre_get_posts','QueryListFilter');

// カテゴリ一覧ウィジェットから特定のカテゴリを除外
function my_theme_catexcept($cat_args){
    $exclude_id = '1';					// 除外するカテゴリID(未分類)
    $cat_args['exclude'] = $exclude_id;	// 除外
    return $cat_args;
}
add_filter('widget_categories_args', 'my_theme_catexcept',10);

function get_meta_query_args( $recommend, $distance = NULL ) {
	$args = array(
		'relation'		=> 'AND',
		'meta_close'=>array(
			'relation'		=> 'OR',
			array(
				'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
				'compare'	=> 'NOT EXISTS',		//カスタムフィールドがない場合も表示
			),
			array(
				'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
				'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
				'compare'	=> '>=',				//今日以降なら表示
				'type'		=> 'date',				//タイプに日付を指定
			),
		),
		'meta_open'=>array(
/*			'reration'		=> 'OR',
			array(
				'key'		=> 'eventopen',
				'compare'	=> 'NOT EXISTS',
			),
			array(
*/				'key'		=> 'eventopen',			//カスタムフィールドのイベント開催日欄
				'value'		=> date_i18n( "Y/m/d" ),//イベント開催日を今日と比較
				'compare'	=> '<=',				//今日以前なら表示
				'type'		=> 'date',				//タイプに日付を指定
//			),
		),
		'meta_recommend'=>array(
			'key'		=> 'recommend',				//カスタムフィールドのおすすめ度
			'value'		=> $recommend,				//
			'compare'	=> '>=',					//指定のおすすめ度以上を表示
			'type'		=> 'numeric',				//タイプに数値を指定
		),
	);
	if ($distance == 'exists') {
		$args += array('meta_distance'=>array(
					'key'		=> 'distance',		//カスタムフィールドの距離データ
					'compare'	=> 'exists',		//距離データのあるイベントを表示
				));
	} elseif ($distance) {
		$args += array('meta_distance'=>array(
					'key'		=> 'distance',		//カスタムフィールドの距離データ
					'value'		=> $distance,		//
					'compare'	=> '<=',			//指定距離内のイベントを表示
					'type'		=> 'char',			//タイプに数値を指定
				));
	}

	return $args;
}

//固定ページにも抜粋(excerpt)を使えるようにする
add_post_type_support( 'page', 'excerpt' );

//管理画面の投稿一覧から作成者列を削除
function custom_columns($columns) {
        unset($columns['author']);
        return $columns;
}
add_filter( 'manage_posts_columns', 'custom_columns' );

//管理画面の投稿一覧にイベント開催日と終了日の列を追加
function add_posts_columns_name($columns) {
    $columns['eventopen'] = esc_html(__('Open date', 'SagasWhat'));
	$columns['eventclose'] = esc_html(__('Close date', 'SagasWhat'));
    return $columns;
}
add_filter( 'manage_posts_columns', 'add_posts_columns_name' );

//追加した列を並び替えれるようにする
function custom_sortable_columns($sortable_column) {
    $sortable_column['eventopen'] = 'eventopen';
	$sortable_column['eventclose'] = 'eventclose';
    return $sortable_column;
}
function custom_orderby_columns( $vars ) {
    if (isset($vars['orderby']) && 'eventopen' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'eventopen',
            'orderby' => 'meta_value',
			'meta_type' => 'date',
        ));
    }
	if (isset($vars['orderby']) && 'eventclose' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'eventclose',
            'orderby' => 'meta_value',
			'meta_type' => 'date',
        ));
    }
    return $vars;
}
add_filter( 'manage_edit-post_sortable_columns', 'custom_sortable_columns');
add_filter( 'request', 'custom_orderby_columns' );

//管理画面の投稿一覧にイベント開催日と終了日を表示
function add_column($column_name, $post_id) {
    if ($column_name == 'eventopen') {
        $opendate = get_post_meta($post_id, 'eventopen', true);
    }
	if ($column_name == 'eventclose') {
        $closedate = get_post_meta($post_id, 'eventclose', true);
    }
	if (isset($opendate) && $opendate) {
        echo attribute_escape($opendate);
    } elseif (isset($closedate) && $closedate) {
        echo attribute_escape($closedate);
	} else {
		echo esc_html(__('None', 'SagasWhat'));
    }
}
add_action( 'manage_posts_custom_column', 'add_column', 10, 2 );

//イベント終了画像IDをメディアライブラリから取得
function get_closed_img() {
	$attachments = get_children(array('post_type' => 'attachment', 'post_mime_type' => 'image'));
	if(!empty($attachments)){
		foreach($attachments as $attachment){
			if($attachment->post_title == 'eventclosed') {
				return $attachment->ID;
			}
		}
	}
	return NULL;
}
