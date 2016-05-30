<?php

//概要（抜粋）の文字数
function my_length($length) {
	return 50;
}
add_filter('excerpt_mblength', 'my_length');

//概要（抜粋）の省略記号
function my_more($more) {
	return '…';
}
add_filter('excerpt_more', 'my_more');

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
		array( 'title' => ' 補足情報 ',
			'block' => 'div',
			'classes' => 'point',
			'wrapper' => true ),
		array( 'title' => ' 注意書き ',
			'block' => 'div',
			'classes' => 'attention',
			'wrapper' => true ),
		array( 'title' => 'ハイライト',
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

// カスタムメニュー
register_nav_menu( 'sitenav', 'サイトナビゲーション' );
register_nav_menu( 'pickupnav', 'おすすめ記事' );
register_nav_menu( 'pagenav', 'ページナビゲーション' );
register_nav_menu( 'categorynav', 'カテゴリメニュー' );
register_nav_menu( 'newsnav', 'お知らせ' );

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
	'name' => ' サブメニュー',
	'description' => 'サイドバーに表示するウィジェットを指定。',
	'before_widget' => '<aside id="%1$s" class="mymenu widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>'
) );

register_sidebar( array(
	'id' => 'ad',
	'name' => '広告',
	'description' => 'サイドバーに表示する広告を指定。',
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
add_filter( 'the_content', 'event_info_to_the_content', 1 );

function event_info_to_the_content( $content ) {
	global $post;

	if( $eventname = esc_html( get_post_meta($post->ID, 'eventname', true) ) ) {
		$info = $info . '<tr><th>イベント名</th><td>' . $eventname . '</td></tr>';
	}
	if( $address = esc_html( get_post_meta($post->ID, 'address', true) ) ) {
		$info = $info . '<tr><th>住所</th><td>' . $address . '</td></tr>';
	}
	if( $telephone = esc_html( get_post_meta($post->ID, 'telephone', true) ) ) {
		$info = $info . '<tr><th>電話</th><td>' . $telephone . '</td></tr>';
	}
	if( $eventopen = esc_html( get_post_meta($post->ID, 'eventopen', true) ) ) {
		$info = $info . '<tr><th>開催日</th><td>' . $eventopen . '</td></tr>';
	}
	if( $eventclose = esc_html( get_post_meta($post->ID, 'eventclose', true) ) ) {
		$info = $info . '<tr><th>終了日</th><td>' . $eventclose . '</td></tr>';
	}
	if( $bizhours = esc_html( get_post_meta($post->ID, 'bizhours', true) ) ) {
		$info = $info . '<tr><th>営業時間</th><td>' . $bizhours . '</td></tr>';
	}
	if( $comment = esc_html( get_post_meta($post->ID, 'comment', true) ) ) {
		$info = $info . '<tr><th>コメント</th><td>' . $comment . '</td></tr>';
	}

	$table = '<table class="event-info"><tbody>' . $info . '</tbody></table>';

	return $content . $table;
}

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
    /**
	* $opendate, $closedateの値をチェック
    * 正しい年月日ならUnixのタイムスタンプに
    */
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
