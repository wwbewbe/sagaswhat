<?php

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
		$url = get_template_directory_uri() . '/sagaswhat.png';
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

// Upcoming記事内に添付した画像からeventclose画像IDを取得
function get_closeimage_id() {
	global $post;
	$count = 0;
	if (preg_match_all( '/wp-image-(\d+)/s', $post->post_content, $thumbid)) {
		while (isset($thumbid[1][$count])) {
			$imgmeta = wp_get_attachment_metadata( $thumbid[1][$count] );
			if ($imgmeta->image_meta->title == 'eventclose') {
				return $thumbid[1][$count];
			}
		}
	}
	return false;
}

// カスタムメニュー
register_nav_menu( 'sitenav', 'Site Navigation' );
register_nav_menu( 'pickupnav', 'Pickup Posts' );
register_nav_menu( 'pagenav', 'Page Navigation' );
register_nav_menu( 'categorynav', 'Category Menu' );
register_nav_menu( 'newsnav', 'News' );
register_nav_menu( 'upcomingnav', 'Upcoming Menu' );

// トグルボタン
function navbtn_scripts() {
	wp_enqueue_script( 'navbtn-script', get_template_directory_uri() .'/js/navbtn.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'navbtn_scripts' );

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
//    return $strLat . ',' . $strLng;
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
	'id' => 'submenu',
	'name' => 'SubMenu',
	'description' => 'setting widget on side bar.',
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
	// イベント名
	$url = esc_html( get_post_meta($post->ID, 'url', true) );
	$eventname = esc_html( get_post_meta($post->ID, 'eventname', true) );
	if( $eventname && $url ) {
		$info = $info . '<tr><th>Event name</th><td><a href="'.$url.'" target="_blank">' . $eventname . '</a></td></tr>';
	} elseif( $eventname ) {
		$info = $info . '<tr><th>Event name</th><td>' . $eventname . '</td></tr>';
	}
	// 会場・場所
	if( $venue = esc_html( get_post_meta($post->ID, 'venue', true) ) ) {
		$info = $info . '<tr><th>Venue/Location</th><td>' . $venue . '</td></tr>';
		$preview = $preview . '<tr><th>Venue/Location</th><td>' . $venue . '</td></tr>'; // preview information
	}
	// 開催期間
	$eventopen = esc_html( get_post_meta($post->ID, 'eventopen', true) );
	$eventclose = esc_html( get_post_meta($post->ID, 'eventclose', true) );
	if($eventopen && $eventclose) {
		if($eventopen == $eventclose) {
			$dates = date('l, j F, Y', strtotime($eventclose));
			$info = $info . '<tr><th>Dates</th><td>' . $dates . '</td></tr>';
			$preview = $preview . '<tr><th>Dates</th><td>' . $dates . '</td></tr>'; // preview information
		} else {
			$eventopen = date('l, j F', strtotime($eventopen));
			$eventclose = date('l, j F, Y', strtotime($eventclose));
			$dates = $eventopen . ' ~ ' . $eventclose;
			$info = $info . '<tr><th>Dates</th><td>' . $dates . '</td></tr>';
			$preview = $preview . '<tr><th>Dates</th><td>' . $dates . '</td></tr>'; // preview information
		}
	} elseif($eventopen || $eventclose) {
		if ($eventopen) {
			$eventopen = date('l, j F', strtotime($eventopen));
		}
		if ($eventclose) {
			$eventclose = date('l, j F, Y', strtotime($eventclose));
		}
		$dates = $eventopen . ' ~ ' . $eventclose;
		$info = $info . '<tr><th>Dates</th><td>' . $dates . '</td></tr>';
		$preview = $preview . '<tr><th>Dates</th><td>' . $dates . '</td></tr>'; // preview information
	}
	// 注記
	if( $note = esc_html( get_post_meta($post->ID, 'note', true) ) ) {
		$info = $info . '<tr><th>Note</th><td>' . $note . '</td></tr>';
		$preview = $preview . '<tr><th>Note</th><td>' . $note . '</td></tr>'; // preview information
	}
	// 営業時間
	if( $bizhours = esc_html( get_post_meta($post->ID, 'bizhours', true) ) ) {
		$info = $info . '<tr><th>Open hours</th><td>' . $bizhours . '</td></tr>';
	}
	// 入場料
	if( $price = esc_html( get_post_meta($post->ID, 'price', true) ) ) {
		$info = $info . '<tr><th>Admission</th><td>' . $price . '</td></tr>';
	}
	// 住所
	if( $showaddress = esc_html( get_post_meta($post->ID, 'showaddress', true) ) ) {
		$info = $info . '<tr><th>Address</th><td>' . $showaddress . '</td></tr>';
	}
	// 問い合わせ
	if( $telephone = esc_html( get_post_meta($post->ID, 'telephone', true) ) ) {
		$info = $info . '<tr><th>Contact</th><td>' . $telephone . '</td></tr>';
	}

	$pretable = '<table class="event-info"><tbody>' . $preview . '</tbody></table>';
	$table = '<table class="event-info"><tbody>' . $info . '</tbody></table>';

	return $pretable . $content . $table;
}
add_filter( 'the_content', 'event_info_to_the_content', 1 );

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
        				'count' => 1,
						'type' => 'rectangle',
    					), $params));

	$adcode = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-shortcode -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="2100370814"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

	$adcoderes = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sagaswhat-responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="3812496019"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

	if (($count == 2) && ($type == 'rectangle')) {
		return $adcode . $adcode;
	} elseif ($type == 'responsive') {
		return $adcoderes;
	} else {
    	return $adcode;
	}
}
add_shortcode('adsense', 'showads');

// カテゴリ・タグ・検索の一覧表示のクエリー設定
function QueryListFilter($query) {
	if ( !is_admin() && $query->is_main_query() && ($query->is_tag() || $query->is_search() || $query->is_category()) ) {
		$query->set('post_type', 'post');			// 投稿記事を対象
		$query->set('posts_per_page', '10');		// 一覧表示数
		$query->set('category__not_in', array(1));	// 未分類のカテゴリを非表示
		$query->set('meta_key', 'recommend');		// 推奨値の順に表示
		$query->set('orderby', 'meta_value_num');	// 推奨値の高い順
		$query->set('meta_query', array(
			'relation'		=> 'OR',
			array(
				'relation'		=> 'AND',
				array(
					'key'		=> 'eventclose',
					'compare'	=> 'NOT EXISTS',
				),
				array(
					'key'		=> 'eventopen', 		//カスタムフィールドのイベント開催日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント開催日を今日と比較
					'compare'	=> '<=', 				//今日以前なら表示
				),
			),
			array(
				'relation'		=> 'AND',
				array(
					'key'		=> 'eventclose', 		//カスタムフィールドのイベント終了日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
					'compare'	=> '>=', 				//今日以降なら表示
				),
				array(
					'key'		=> 'eventopen',
					'compare'	=> 'NOT EXISTS',
				),
			),
			array(
				'reration'		=> 'AND',
				array(
					'key'		=> 'eventclose', 		//カスタムフィールドのイベント終了日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
					'compare'	=> '>=', 				//今日以降なら表示
				),
				array(
					'key'		=> 'eventopen', 		//カスタムフィールドのイベント開催日欄
					'value'		=> date_i18n( "Y/m/d" ),//イベント開催日を今日と比較
					'compare'	=> '<=', 				//今日以前なら表示
				),
			),
		));
	}
	return $query;
}
add_filter('pre_get_posts','QueryListFilter');

 // カテゴリ一覧ウィジェットから特定のカテゴリを除外
 function my_theme_catexcept($cat_args){
     $exclude_id = '1';						// 除外するカテゴリID(未分類)
     $cat_args['exclude'] = $exclude_id;	// 除外
     return $cat_args;
 }
add_filter('widget_categories_args', 'my_theme_catexcept',10);
