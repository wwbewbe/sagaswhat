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
register_nav_menu( 'sitenav', 'Site Navigation' );		//ヘッダーに表示するメニュー
register_nav_menu( 'pickupnav', 'Pickup Posts' );		//注目のイベント
register_nav_menu( 'pagenav', 'Page Navigation' );		//TOPページのメニュー
register_nav_menu( 'categorynav', 'Category Menu' );	//TOPにあるカテゴリーメニューから表示するメニュー
register_nav_menu( 'upcomingnav', 'Upcoming Menu' );	//予定(Upcoming)のイベント表示用メニュー
register_nav_menu( 'nearbynav', 'Nearby Menu' );		//周辺イベント検索用メニュー
register_nav_menu( 'calendarnav', 'Calendar Menu' );	//カレンダーから探す用メニュー
register_nav_menu( 'nearbyticnav', 'Nearby TIC Menu' );	//周辺TICのリスト表示用メニュー
register_nav_menu( 'ticnav', 'TIC Menu' );				//TICのリスト表示用メニュー
register_nav_menu( 'floatingmenu', 'Floating Menu' );	//フローティングメニュー
register_nav_menu( 'topicsmenu', 'Topics Menu' );			//TOPページに表示する特集メニュー

// JavaScript各機能enqueue
function theme_enqueue_scripts() {
	// トグルボタン
	wp_enqueue_script( 'navbtn-script', get_template_directory_uri() .'/js/navbtn.js', array( 'jquery' ) );
	// Geolocationを使用
	wp_enqueue_script( 'geoloc-script', get_template_directory_uri() .'/js/geoloc.js', array( 'jquery' ) );
	// フローティングメニュー
	wp_enqueue_script( 'floating-script', get_template_directory_uri() .'/js/floating-menu.js', array( 'jquery' ) );
	// jCarouselを使用
	wp_enqueue_script( 'carousel-script', get_template_directory_uri() .'/js/jquery.jcarousellite.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );

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

	// トレンド記事の場合
	if ( !is_admin() && is_main_query() && is_singular('sw_trend') ) {
		return $content;
	}
	// 休憩スポットの場合
	if ( !is_admin() && is_main_query() && is_singular('sw_rest') ) {
		// 休憩スポット名
		if( $eventname = get_post_meta($post->ID, 'eventname', true) ) {
			$thname = esc_html__('Spot Name', 'SagasWhat');
			if( $url = esc_url(get_post_meta($post->ID, 'url', true)) ) {
				$info = $info . '<tr><th>'.$thname.'</th><td><a href="'.$url.'" target="_blank">' . $eventname . '</a></td></tr>';
			} else {
				$info = $info . '<tr><th>'.$thname.'</th><td>' . $eventname . '</td></tr>';
			}
		}
		// 最寄り駅
		if( $venue = get_post_meta($post->ID, 'venue', true) ) {
			$thname = esc_html__('Nearest Station', 'SagasWhat');
			$info = $info . '<tr><th>'.$thname.'</th><td>' . $venue . '</td></tr>';
		}
		// 開園時間
		if( $bizhours = get_post_meta($post->ID, 'bizhours', true) ) {
			$thname = esc_html__('Open Hours', 'SagasWhat');
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$bizhours.'</td></tr>';
		}
		// 注記
		if( $note = get_post_meta($post->ID, 'note', true) ) {
			$thname = esc_html(__('Note', 'SagasWhat'));
			if ( $noteurl = esc_url(get_post_meta($post->ID, 'noteurl', true)) ) {
				$note = $note.'<div><a href="'.$noteurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
			}
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$note.'</td></tr>';
		}
		// 住所
		if( $showaddress = get_post_meta($post->ID, 'showaddress', true) ) {
			$thname = esc_html__('Address', 'SagasWhat');
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$showaddress.'</td></tr>';
		}
		// 施設
		if( $showaddress = get_post_meta($post->ID, 'facility', false) ) {
			$thname = esc_html__('Facility', 'SagasWhat');
			foreach ($showaddress as $add) {
				switch ($add) {
					case '1':
						$data .= '<i class="fa fa-wifi fa-fw"></i>'.'<span style="margin-right:10px;">'.esc_html__('Wi-Fi', 'SagasWhat').'</span>';
						break;
					case '2':
						$data .= '<i class="fa fa-coffee fa-fw"></i>'.'<span style="margin-right:10px;">'.esc_html__('Shop/Cafe/Vending Machine', 'SagasWhat').'</span>';
						break;
					case '3':
						$data .= '<i class="fa fa-child fa-fw"></i>'.'<span style="margin-right:10px;">'.esc_html__('Grass Field', 'SagasWhat').'</span>';
						break;
					default:
				}
			}
			$info = $info.'<tr><th>'.$thname.'</th><td>'.$data.'</td></tr>';
		}
		$table = '<table class="event-info"><tbody>' . $info . '</tbody></table>';
		return $content.$table;
	}

	if ( !is_admin() && is_main_query() && is_single() ) {

		if (in_category('tourist-info-center')) {//TIC記事の場合表示する項目
			// TIC名
			if( $eventname = get_post_meta($post->ID, 'eventname', true) ) {
				$thname = esc_html__('Center Name', 'SagasWhat');
				if( $url = esc_url(get_post_meta($post->ID, 'url', true)) ) {
					$info = $info . '<tr><th>'.$thname.'</th><td><a href="'.$url.'" target="_blank">' . $eventname . '</a></td></tr>';
				} else {
					$info = $info . '<tr><th>'.$thname.'</th><td>' . $eventname . '</td></tr>';
				}
			}
			// TIC名(日本語名)
			if( $ticjpname = get_post_meta($post->ID, 'ticjpname', true) ) {
				$thname = esc_html__('JP Name', 'SagasWhat');
				$info = $info . '<tr><th>'.$thname.'</th><td>' . $ticjpname . '</td></tr>';
			}
			// 注記(Category:x)
			if( $note = get_post_meta($post->ID, 'note', true) ) {
				$thname = esc_html(__('TIC Category', 'SagasWhat'));
				if ( $noteurl = esc_url(get_post_meta($post->ID, 'noteurl', true)) ) {
					$note = $note.'<div><a href="'.$noteurl.'" target="_blank">'.esc_html__('Category description (Japan National Tourism Organization)', 'SagasWhat').'</div>';
				}
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$note.'</td></tr>';
			}
			// 営業時間
			if( $bizhours = get_post_meta($post->ID, 'bizhours', true) ) {
				$thname = esc_html__('Open Hours', 'SagasWhat');
				if ( $bizhoursurl = esc_url(get_post_meta($post->ID, 'bizhoursurl', true)) ) {
					$bizhours = $bizhours.'<div><a href="'.$bizhoursurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
				}
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$bizhours.'</td></tr>';
			}
			// Wi-Fi
			if( $wifi = get_post_meta($post->ID, 'wifi', true) ) {
				$thname = esc_html__('Wi-Fi', 'SagasWhat');
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$wifi.'</td></tr>';
			}
			// PC
			if( $pc = get_post_meta($post->ID, 'pc', true) ) {
				$thname = esc_html__('PC', 'SagasWhat');
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$pc.'</td></tr>';
			}
			// 住所
			if( $showaddress = get_post_meta($post->ID, 'showaddress', true) ) {
				$thname = esc_html__('Address', 'SagasWhat');
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$showaddress.'</td></tr>';
			}
			// 問い合わせ
			if( $telephone = get_post_meta($post->ID, 'telephone', true) ) {
				$thname = esc_html__('Contact', 'SagasWhat');
				if ( $telephoneurl = esc_url(get_post_meta($post->ID, 'telephoneurl', true)) ) {
					$telephone = $telephone.'<div><a href="'.$telephoneurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
				}
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$telephone.'</td></tr>';
			}
			$table = '<table class="event-info"><tbody>' . $info . '</tbody></table>';
			$ticinfo = '<div class="tic-comment-end">'. get_option('tic-comment-end') .'</div>';
			$adsense = '<aside class="mymenu-adsense">' . get_adsense(true) . '</aside>';

//			return $table.$content.$ticinfo.$adsense;
			return $table.$content.$ticinfo;

		} else {
			// イベント名
			if( $eventname = get_post_meta($post->ID, 'eventname', true) ) {
				$thname = esc_html__('Event Name', 'SagasWhat');
				if( $url = esc_url(get_post_meta($post->ID, 'url', true)) ) {
					$info = $info . '<tr><th>'.$thname.'</th><td><a href="'.$url.'" target="_blank">' . $eventname . '</a></td></tr>';
				} else {
					$info = $info . '<tr><th>'.$thname.'</th><td>' . $eventname . '</td></tr>';
				}
			}
			// 会場・場所
			if( $venue = get_post_meta($post->ID, 'venue', true) ) {
				$thname = esc_html__('Venue', 'SagasWhat');
				if ( $venueurl = esc_url(get_post_meta($post->ID, 'venueurl', true)) ) {
					$info = $info . '<tr><th>'.$thname.'</th><td><a href="'.$venueurl.'" target="_blank">' . $venue . '</a></td></tr>';
				} else {
					$info = $info . '<tr><th>'.$thname.'</th><td>' . $venue . '</td></tr>';
				}
			}
			// 開催期間
			$eventopen = esc_html(get_post_meta($post->ID, 'eventopen', true));
			$eventclose = esc_html(get_post_meta($post->ID, 'eventclose', true));
			$openseason = get_post_meta($post->ID, 'openseason', true);
			$closeseason = get_post_meta($post->ID, 'closeseason', true);
			$thname = esc_html__('Dates', 'SagasWhat');
			if($eventopen && $eventclose) {	//開催日も終了日も入力されている場合
				if($eventopen == $eventclose) {
					$dates = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventclose));
					if ($closeseason) $dates = $closeseason;	//シーズンが設定されている場合はシーズンを表示
					$info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
				} else {
					$eventopen = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventopen));
					$eventclose = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventclose));
					if ($openseason) $eventopen = $openseason;		//シーズンが設定されている場合はシーズンを表示
					if ($closeseason) $eventclose = $closeseason;	//シーズンが設定されている場合はシーズンを表示
					$dates = $eventopen . ' - ' . $eventclose;
					$info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
				}
			} elseif($eventopen || $eventclose) {
				if ($eventopen) {	//開催日のみ入力されている場合（終了日未定の場合）
					$eventopen = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventopen));
					if ($openseason) $eventopen = $openseason;	//シーズンが設定されている場合はシーズンを表示
					$dates = $eventopen . ' - ' . '&gt;&gt;&gt;';
					$info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
				}
				if ($eventclose) {
					$eventclose = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventclose));
					if ($closeseason) $eventclose = $closeseason;	//シーズンが設定されている場合はシーズンを表示
					$dates = '&gt;&gt;&gt;' . ' - ' . $eventclose;
					$info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
				}
			}
			// 注記
			if( $note = get_post_meta($post->ID, 'note', true) ) {
				$thname = esc_html(__('Note', 'SagasWhat'));
				if ( $noteurl = esc_url(get_post_meta($post->ID, 'noteurl', true)) ) {
					$note = $note.'<div><a href="'.$noteurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
				}
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$note.'</td></tr>';
			}
			// 営業時間
			if( $bizhours = get_post_meta($post->ID, 'bizhours', true) ) {
				$thname = esc_html__('Open Hours', 'SagasWhat');
				if ( $bizhoursurl = esc_url(get_post_meta($post->ID, 'bizhoursurl', true)) ) {
					$bizhours = $bizhours.'<div><a href="'.$bizhoursurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
				}
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$bizhours.'</td></tr>';
			}
			// 入場料
			if( $price = get_post_meta($post->ID, 'price', true) ) {
				$thname = esc_html__('Admission', 'SagasWhat');
				if ( $priceurl = esc_url(get_post_meta($post->ID, 'priceurl', true)) ) {
					$price = $price.'<div><a href="'.$priceurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
				}
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$price.'</td></tr>';
			}
			// Free Wi-Fi
			if( $freewifi = get_post_meta($post->ID, 'freewifi', true) ) {
				$thname = esc_html__('Free Wi-Fi', 'SagasWhat').'<i class="fa fa-wifi fa-fw"></i>';
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$freewifi.'</td></tr>';
			}
			// 住所
			if( $showaddress = get_post_meta($post->ID, 'showaddress', true) ) {
				$thname = esc_html__('Address', 'SagasWhat');
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$showaddress.'</td></tr>';
			}
			// 問い合わせ
			if( $telephone = get_post_meta($post->ID, 'telephone', true) ) {
				$thname = esc_html__('Contact', 'SagasWhat');
				if ( $telephoneurl = esc_url(get_post_meta($post->ID, 'telephoneurl', true)) ) {
					$telephone = $telephone.'<div><a href="'.$telephoneurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
				}
				$info = $info.'<tr><th>'.$thname.'</th><td>'.$telephone.'</td></tr>';
			}
			$table = '<table class="event-info"><tbody>' . $info . '</tbody></table>';
			$adsense = '<aside class="mymenu-adsense">' . get_adsense(true) . '</aside>';

//			return $content.$adsense.$table;
			return $content.$table;
		}
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'event_info_to_the_content', 1 );

// タグリストを表示するショートコード
function set_taglist($params = array()) {
    extract(shortcode_atts(array(
        				'file'		=>	'taglist',	//表示に使用するPHPファイル
						'tagname'	=>	0,			//表示するタグ名
						'catname'	=>	0,			//表示するカテゴリー名
						'list'		=>	5,			//表示するリスト数
						'sort'		=>	false,		//近くのイベント順に並び替えるボタンの表示(true)/非表示(false)
						'posttype'	=>	'post',		//表示する投稿タイプ(post, page, sw_trend, sw_rest)
						'tax'		=>	'keyword',	//表示するカスタムタクソノミー(keyword, etc.)＊カスタム投稿のみ使用
						'terms'		=>	0,			//表示するカスタムタクソノミーの項目名(matsuri, etc.)＊カスタム投稿のみ使用
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

function get_adsense($kiji = false) {
	$title = '<div class="adsense-title">'.esc_html(__('Sponsored Links', 'SagasWhat')).'</div>';
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
	return $title.'<div class="adsense-code">'.$adsense.'</div>';
}

// カテゴリ・タグ・検索の一覧表示のクエリー設定
function QueryListFilter($query) {
	$infocat = get_category_by_slug('tourist-info-center');//観光案内所をリストから除外
	if (!is_admin() && $query->is_main_query() && $query->is_search()) {

		$query->set('post_type', array('post', 'sw_trend', 'sw_rest'));		// 投稿記事とカスタム投稿を対象
		$query->set('posts_per_page', '20');		// 一覧表示数
		$query->set('category__not_in', array(1, $infocat->cat_ID));// カテゴリが未分類と観光案内所の記事は非表示
		$query->set('orderby', 'modified');	// 更新日の新しい記事順

	} elseif ( !is_admin() && $query->is_main_query() && ($query->is_category('tourist-info-center')) ) {

		$query->set('post_type', 'post');			// 投稿記事を対象
		$query->set('posts_per_page', '10');		// 一覧表示数
		$query->set('orderby', array('meta_tic'=>'asc'));			//TICリスト番号の昇順で表示
		$query->set('meta_query', array(
						'meta_tic'=>array(
							'key'		=> 'location',				//TICリスト番号
							'type'		=> 'numeric',				//タイプに数値を指定
						),
					));

	} elseif ( !is_admin() && $query->is_main_query() && ($query->is_tag() || $query->is_category()) ) {

		$query->set('post_type', array('post', 'sw_trend', 'sw_rest'));		// 投稿記事とカスタム投稿を対象
		$query->set('posts_per_page', '10');		// 一覧表示数
		$query->set('category__not_in', array(1, $infocat->cat_ID));// カテゴリが未分類と観光案内所の記事は非表示
		$query->set('orderby', array('meta_recommend'=>'desc', 'meta_close'=>'asc'));	// 推奨値の高い順
		$query->set('meta_query', get_meta_query_args());			// 終了していないイベントを表示

	}
	return $query;
}
add_action('pre_get_posts','QueryListFilter');

//各イベント会場 or 観光案内所と現在地の距離をカスタムフィールドに保存
function set_event_distance($lat, $lng, $target = 0, $posttype = 'post') {
	global $post;

	if ($posttype == 'post') {
		if ($target == 'tic') {
			//観光案内所と現在地の距離
			$infocat = get_category_by_slug('tourist-info-center');
			$args = array(
				'post_type'		=> 'post',		// イベント記事
				'posts_per_page' => '-1',		// 全件
				'cat' => $infocat->cat_ID, 		// 観光案内所の記事を抽出
			);
		} else {
			//各イベント会場と現在地の距離
			$meta_query_args = get_meta_query_args();
			$args = array(
				'post_type'		=> 'post',		// イベント記事
				'posts_per_page' => '-1',		// 全件
				'meta_query'	=> $meta_query_args,//全ての終了していないイベント抽出
			);
		}
	} else {
		$args = array(
			'post_type'		=> $posttype,	// カスタム投稿
			'posts_per_page' => '-1',		// 全件
		);
	}

	$the_query = new WP_Query($args);

	if($the_query->have_posts()) {
		while($the_query->have_posts()) {
			$the_query->the_post();
			//現在地からイベント場所の距離を算出してデータに追加
			//カスタムフィールドに緯度経度がなければ住所から算出し格納
			$spotLat = esc_html( get_post_meta($post->ID, 'spot_lat', true) );
			$spotLng = esc_html( get_post_meta($post->ID, 'spot_lng', true) );
			if ((!$spotLat) || (!$spotLng)) {
				$address = esc_html( get_post_meta($post->ID, 'address', true) );
				$LatLng = strAddrToLatLng($address);
				$spotLat = $LatLng['Lat'];
				$spotLng = $LatLng['Lng'];
				update_post_meta($post->ID, 'spot_lat', $spotLat);
				update_post_meta($post->ID, 'spot_lng', $spotLng);
			}
			if (($spotLat) && ($spotLng)) {
				$distanceLat = $spotLat - $lat;
				$distanceLng = $spotLng - $lng;
				// 距離の算出　pow = 乗算 / sqrt = 平方根
				$distance = sqrt(pow( $distanceLat ,2) + pow( $distanceLng ,2));
				// 並び替え用の数値として距離「distance」を追加
				update_post_meta($post->ID, 'distance', $distance);
			}
		}
	}
	wp_reset_postdata();
}

//イベント抽出フィルターの条件を設定
function get_meta_query_args( $recommend = 0, $distance ) {
	$args = array(
		'relation'		=> 'AND',
		array(
			'relation'		=> 'OR',
			array(
				'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
				'compare'	=> 'NOT EXISTS',		//カスタムフィールドがない場合も表示
			),
			'meta_close'=>array(
				'key'		=> 'eventclose',		//カスタムフィールドのイベント終了日欄
				'value'		=> date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
				'compare'	=> '>=',				//今日以降なら表示
				'type'		=> 'date',				//タイプに日付を指定
			),
		),
		array(
			'relation'		=> 'OR',
			array(
				'key'		=> 'recommend',			//カスタムフィールドのおすすめ度
				'compare'	=> 'NOT EXISTS',		//カスタムフィールドがない場合も表示
			),
			'meta_recommend'=>array(
				'key'		=> 'recommend',				//カスタムフィールドのおすすめ度
				'value'		=> $recommend,				//
				'compare'	=> '>=',					//指定のおすすめ度以上を表示
				'type'		=> 'numeric',				//タイプに数値を指定
			),
		),
	);
	if ($distance == 'exists') {
		$args += array('meta_distance'=>array(
					'key'		=> 'distance',		//カスタムフィールドの距離データ
					'compare'	=> 'exists',		//距離データのあるイベントをすべて表示
				));
	} elseif (!empty($distance)) {
		$args += array('meta_distance'=>array(
					'key'		=> 'distance',		//カスタムフィールドの距離データ
					'value'		=> $distance,		//
					'compare'	=> '<=',			//指定距離内のイベントを表示
					'type'		=> 'char',			//タイプに数値を指定
				));
	}

	return $args;
}

// カテゴリ一覧ウィジェットから特定のカテゴリを除外
function my_theme_catexcept($cat_args){
	$infocat = get_category_by_slug('tourist-info-center');
    $exclude_id = "1,$infocat->cat_ID";					// 除外するカテゴリID(未分類)
    $cat_args['exclude'] = $exclude_id;	// 除外
    return $cat_args;
}
add_filter('widget_categories_args', 'my_theme_catexcept',10);

//固定ページにも抜粋(excerpt)を使えるようにする
add_post_type_support( 'page', 'excerpt' );

//管理画面の投稿一覧にイベント開催日、終了日、推奨度、会場、住所の列を追加
function add_posts_columns_name($columns) {
	global $post;
	unset($columns['author']);		//管理画面の投稿一覧から作成者列を削除
	unset($columns['comments']);	//管理画面の投稿一覧からコメント列を削除
	if ($post->post_type == 'post') {
	    $columns['eventopen'] = esc_html__('Open date', 'SagasWhat');
		$columns['eventclose'] = esc_html__('Close date', 'SagasWhat');
		$columns['recommend'] = esc_html__('Recommend', 'SagasWhat');
		$columns['venue'] = esc_html__('Venue', 'SagasWhat');
		$columns['address'] = esc_html__('Address', 'SagasWhat');
	}
    return $columns;
}
add_filter( 'manage_posts_columns', 'add_posts_columns_name' );

//管理画面のカスタム投稿(Trends)一覧にキーワードの列を追加
function add_trends_columns_name($columns) {
    $columns['keyword'] = esc_html__('Keyword', 'SagasWhat');
    return $columns;
}
add_filter( 'manage_edit-sw_trend_columns', 'add_trends_columns_name' );

//管理画面のカスタム投稿(Resting Spots)一覧にキーワードの列を追加
function add_rests_columns_name($columns) {
	$columns['address'] = esc_html__('Address', 'SagasWhat');
    $columns['kind'] = esc_html__('Kind', 'SagasWhat');
    return $columns;
}
add_filter( 'manage_edit-sw_rest_columns', 'add_rests_columns_name' );

//管理画面の投稿一覧にイベント開催日と終了日を表示
function add_column($column_name, $post_id) {
	global $post;
	if ($post->post_type == 'post') {
	    if ($column_name == 'eventopen') {
	        $opendate = get_post_meta($post_id, 'eventopen', true);
	    }
		if ($column_name == 'eventclose') {
	        $closedate = get_post_meta($post_id, 'eventclose', true);
	    }
		if ($column_name == 'recommend') {
	        $recommend = get_post_meta($post_id, 'recommend', true);
	    }
		if ($column_name == 'venue') {
	        $venue = get_post_meta($post_id, 'venue', true);
	    }
		if ($column_name == 'address') {
	        $address = get_post_meta($post_id, 'address', true);
	    }
		if (!empty($opendate)) {
	        echo esc_html($opendate);
	    } elseif (!empty($closedate)) {
	        echo esc_html($closedate);
		} elseif (!empty($recommend)) {
	        echo esc_html($recommend);
		} elseif (!empty($venue)) {
	        echo esc_html($venue);
		} elseif (!empty($address)) {
	        echo esc_html($address);
		} else {
			echo esc_html(__('None', 'SagasWhat'));
	    }
	}
	if ($post->post_type == 'sw_trend') {
		if ($column_name == 'keyword') {
			$kwds = get_the_terms($post->ID, 'keyword');
			if ( !empty($kwds) ) {
				$out = array();
				foreach ( $kwds as $kwd ) {
					$out[] = '<a href="edit.php?keyword=' . $kwd->slug . '&post_type=sw_trend' . '">' . esc_html(sanitize_term_field('name', $kwd->name, $kwd->term_id, 'keyword', 'display')) . '</a>';
				}
				echo join( ', ', $out );
			} else {
				echo esc_html(__('None', 'SagasWhat'));
			}
		}
	}
	if ($post->post_type == 'sw_rest') {
		if ($column_name == 'kind') {
			$kwds = get_the_terms($post->ID, 'kind');
			if ( !empty($kwds) ) {
				$out = array();
				foreach ( $kwds as $kwd ) {
					$out[] = '<a href="edit.php?kind=' . $kwd->slug . '&post_type=sw_rest' . '">' . esc_html(sanitize_term_field('name', $kwd->name, $kwd->term_id, 'kind', 'display')) . '</a>';
				}
				echo join( ', ', $out );
			} else {
				echo esc_html(__('None', 'SagasWhat'));
			}
		}
		if ($column_name == 'address') {
	        $address = get_post_meta($post_id, 'address', true);
			if (!empty($address)) {
				echo esc_html($address);
			} else {
				echo esc_html(__('None', 'SagasWhat'));
			}
	    }
	}
}
add_action( 'manage_posts_custom_column', 'add_column', 10, 2 );

//追加した列を並び替えれるようにする
function custom_sortable_columns($sortable_column) {
    $sortable_column['eventopen'] = 'eventopen';
	$sortable_column['eventclose'] = 'eventclose';
	$sortable_column['recommend'] = 'recommend';
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
	if (isset($vars['orderby']) && 'recommend' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'recommend',
            'orderby' => 'meta_value',
			'meta_type' => 'numeric',
        ));
    }
    return $vars;
}
add_filter( 'manage_edit-post_sortable_columns', 'custom_sortable_columns');
add_filter( 'request', 'custom_orderby_columns' );

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

//サイト設定用に管理画面にカスタムメニューページを追加
// admin_menu にフック
add_action('admin_menu', 'register_custom_menu_page');
function register_custom_menu_page() {
    // add_menu_page でカスタムメニューを追加
    add_menu_page('Site settings', 'Site settings', 0, 'site_settings', 'create_custom_menu_page', '');
}
function create_custom_menu_page() {
    // カスタムメニューページを読み込む
    require TEMPLATEPATH.'/admin/site_settings.php';
}

/**
 * 投稿の編集画面にカスタムフィールドを追加する
 */
function add_custom_fields() {

    // 投稿編集画面にメタボックス追加
    add_meta_box(
        'CustomDiv',		// メタボックスのHTML-ID
        'Other Option',		// メタボックスのラベル
        'event_setting',	// HTML出力コールバック
        'post',				// 追加する投稿タイプ名(カスタム投稿も可)
        'normal',			// 配置場所(normal, advanced, side)
        'high'				// 順序(high, core, default, low)
    );

	add_meta_box(
        'restID',			// メタボックスのHTML-ID
        'List Setting',		// メタボックスのラベル
        'rest_setting',		// HTML出力コールバック
        'sw_rest',			// 追加する投稿タイプ名(カスタム投稿も可)
        'normal',			// 配置場所(normal, advanced, side)
        'high'				// 順序(high, core, default, low)
    );
}
add_action( 'add_meta_boxes', 'add_custom_fields' );

/**
 * HTML出力コールバック for イベント記事設定
 */
function event_setting($post) {

	// Add a nonce field so we can check for it later.
//	wp_nonce_field( 'save_event_setting', 'custom_fields_nonce' );
	wp_nonce_field( 'save_custom_fields_data', 'custom_fields_nonce' );
	// HTML出力(checkbox単数)
    $value = get_post_meta(
        $post->ID,	// 投稿ID
        'stop-closealert',	// カスタムフィールドキー
        true		// true:単一文字列, false:複数配列
    );
    echo '<dl>';
    echo   '<dt>';
    echo     'Close Alert Setting:';
    echo   '</dt>';
    echo   '<dd>';
    echo     '<input name="stop-closealert" value="0" type="hidden">';
    echo     '<label>';
    if ($value === '1') {
        echo   '<input name="stop-closealert" value="1" checked="checked" type="checkbox">';
    } else {
        echo   '<input name="stop-closealert" value="1" type="checkbox">';
    }
    echo       'Stop indicate close alert';
    echo     '</label>';
    echo   '</dd>';
    echo '</dl>';
}
/**
 * HTML出力コールバック for 休憩スポット設定
 */
function rest_setting( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'save_custom_fields_data', 'custom_fields_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
    //checkbox for Facility
    $value = get_post_meta(
        $post->ID,	//post ID
        'facility',	//Custom Field Key
        false		//true:単一文字列, false:複数配列
    );
    $list = array(
        1 => 'Wi-Fi',
        2 => '売店・カフェ・自販機',
        3 => '草地広場',
    );
    echo '<dl>';
    echo   '<dt>';
    echo   '設備';
    echo   '</dt>';
    echo   '<dd>';
    echo     '<ul>';
    foreach ($list as $_id => $_name) {
        echo   '<li style="float:left; margin-right:10px">';
        echo     '<label>';
        if (in_array($_id, $value)) {
            echo   '<input name="facility[]" value="'.$_id.'" checked="checked" type="checkbox">';
        } else {
            echo   '<input name="facility[]" value="'.$_id.'" type="checkbox">';
        }
        echo       $_name;
        echo     '</label>';
        echo   '</li>';
    }
    echo     '</ul>';
    echo   '</dd>';
    echo '</dl>';
	echo '<p style="clear:left;"></p>';

    //radio button for How big
    $value = get_post_meta(
        $post->ID,	//post ID
        'howbig',	//Custom Field Key
        true		//true:単一文字列, false:複数配列
    );
    $list = array(
        1 => '大',
        2 => '中',
        3 => '小',
    );
    echo '<dl>';
    echo   '<dt>';
    echo   '広さ';
    echo   '</dt>';
    echo   '<dd>';
    echo     '<input name="howbig" value="0" type="hidden">';
    echo     '<ul>';
    foreach ($list as $_id => $_name) {
        echo   '<li style="float:left; margin-right:10px">';
        echo     '<label>';
        if ($_id == $value) {
            echo   '<input name="howbig" value="'.$_id.'" checked="checked" type="radio">';
        } else {
            echo   '<input name="howbig" value="'.$_id.'" type="radio">';
        }
        echo       $_name;
        echo     '</label>';
        echo   '</li>';
    }
    echo     '</ul>';
    echo   '</dd>';
    echo '</dl>';
	echo '<p style="clear:left;"></p>';

	//radio button for City
    $value = get_post_meta(
        $post->ID,	//post ID
        'city',	//Custom Field Key
        true		//true:単一文字列, false:複数配列
    );
    $list = array(
        1 => '千代田区', 2 => '中央区', 3 => '港区', 4 => '新宿区', 5 => '文京区', 6 => '台東区', 7 => '隅田区', 8 => '江東区', 9 => '品川区', 10 => '目黒区', 11 => '大田区', 12 => '世田谷区', 13 => '渋谷区', 14 => '中野区', 15 => '杉並区', 16 => '豊島区', 17 => '北区', 18 => '荒川区', 19 => '板橋区', 20 => '練馬区', 21 => '足立区', 22 => '葛飾区', 23 => '江戸川区', 24 => '武蔵野市', 25 => '府中市', 26 => '調布市', 27 => '小金井市',
    );
    echo '<dl>';
    echo   '<dt>';
    echo   '市区町村';
    echo   '</dt>';
    echo   '<dd>';
    echo     '<input name="city" value="0" type="hidden">';
    echo     '<ul>';
    foreach ($list as $_id => $_name) {
        echo   '<li style="float:left; margin-right:10px">';
        echo     '<label>';
        if ($_id == $value) {
            echo   '<input name="city" value="'.$_id.'" checked="checked" type="radio">';
        } else {
            echo   '<input name="city" value="'.$_id.'" type="radio">';
        }
        echo       $_name;
        echo     '</label>';
        echo   '</li>';
    }
    echo     '</ul>';
    echo   '</dd>';
    echo '</dl>';
	echo '<p style="clear:left;"></p>';

}
/**
 * カスタムフィールドの保存処理
 */
function save_custom_fields_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['custom_fields_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['custom_fields_nonce'], 'save_custom_fields_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	$post_keys = array( // true:単一文字列, false:複数配列
		'stop-closealert' => true,
		'facility' => false,
		'howbig' => true,
		'city' => true,
	);

	foreach ($post_keys as $post_key => $unique) {
		// single(単一文字列)
		if ($unique && isset($_POST[$post_key])) {
			// Update the meta field in the database.
			update_post_meta(
				$post_id,			// post ID
				$post_key,			// Custom Field Key
				$_POST[$post_key]	// Value
			);
		// multiple(複数配列)
		} elseif (isset($_POST[$post_key])) {
			$input_vals = (array)$_POST[$post_key];
			delete_post_meta(
				$post_id,	// post ID
				$post_key	// Custom Field Key
			);
			foreach ($input_vals as $input_val) {
				add_post_meta(
					$post_id,	// post ID
					$post_key,	// Custom Field Key
					$input_val,	// Value
					false		// true:同じキーがあれば追加しない, false:同じキーがあっても追加する
				);
			}
		}
	}
}
add_action( 'save_post', 'save_custom_fields_data' );

// カスタム投稿タイプ作成
function create_post_type() {
	// トレンド記事用カスタム投稿タイプ
	$labels = array(
		'name'				=> _x( 'Trends', 'post type general name', 'SagasWhat' ),
		'singular_name'		=> _x( 'Trend', 'post type singular name', 'SagasWhat' ),
		'menu_name'			=> _x( 'Trends', 'admin menu', 'SagasWhat' ),
		'name_admin_bar'	=> _x( 'Trend', 'add new on admin bar', 'SagasWhat' ),
		'add_new'			=> _x( 'Add New', 'trend', 'SagasWhat' ),
		'add_new_item'		=> __( 'Add New Trend', 'SagasWhat' ),
		'new_item'			=> __( 'New Trend', 'SagasWhat' ),
		'edit_item'			=> __( 'Edit Trend', 'SagasWhat' ),
		'view_item'			=> __( 'View Trend', 'SagasWhat' ),
		'all_items'			=> __( 'All Trends', 'SagasWhat' ),
		'search_items'		=> __( 'Search Trends', 'SagasWhat' ),
		'parent_item_colon'	=> __( 'Parent Trends:', 'SagasWhat' ),
		'not_found'			=> __( 'No trends found.', 'SagasWhat' ),
		'not_found_in_trash'=> __( 'No trends found in Trash.', 'SagasWhat' )
	);
	$args = array(
		'labels'			=> $labels,
		'public'			=> true,
		'has_archive'		=> true,
		'menu_position'		=> 5,
		'rewrite'			=> array('slug' => 'trend'),
		'supports'			=> array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' )
	);

	register_post_type( 'sw_trend', $args );

	// トレンド記事用カスタム分類(Keyword)
	$labels = array(
		'name'				=> _x( 'Keywords', 'taxonomy general name', 'SagasWhat' ),
		'singular_name'		=> _x( 'Keyword', 'taxonomy singular name', 'SagasWhat' ),
		'search_items'		=> __( 'Search Keywords', 'SagasWhat' ),
		'all_items'			=> __( 'All Keywords', 'SagasWhat' ),
//		'parent_item'		=> __( 'Parent Keyword', 'SagasWhat' ),
//  	'parent_item_colon'	=> __( 'Parent Keyword:', 'SagasWhat' ),
		'edit_item'			=> __( 'Edit Keyword', 'SagasWhat' ),
		'update_item'		=> __( 'Update Keyword', 'SagasWhat' ),
		'add_new_item'		=> __( 'Add New Keyword', 'SagasWhat' ),
		'new_item_name'		=> __( 'New Keyword Name', 'SagasWhat' ),
		'menu_name'			=> __( 'Keyword', 'SagasWhat' ),
	);

	$args = array(
		'hierarchical'		=> false,
		'labels'			=> $labels,
	);

	register_taxonomy( 'keyword', array( 'sw_trend' ), $args );
	register_taxonomy_for_object_type( 'keyword', 'sw_trend' );
	register_taxonomy_for_object_type( 'category', 'sw_trend' );
//	register_taxonomy_for_object_type( 'post_tag', 'sw_trend' );

	// 休憩スポット用カスタム投稿タイプ
	$labels = array(
		'name'				=> _x( 'Resting Spots', 'post type general name', 'SagasWhat' ),
		'singular_name'		=> _x( 'Resting Spot', 'post type singular name', 'SagasWhat' ),
		'menu_name'			=> _x( 'Resting Spots', 'admin menu', 'SagasWhat' ),
		'name_admin_bar'	=> _x( 'Resting Spot', 'add new on admin bar', 'SagasWhat' ),
		'add_new'			=> _x( 'Add New', 'rest', 'SagasWhat' ),
		'add_new_item'		=> __( 'Add New Resting Spot', 'SagasWhat' ),
		'new_item'			=> __( 'New Resting Spot', 'SagasWhat' ),
		'edit_item'			=> __( 'Edit Resting Spot', 'SagasWhat' ),
		'view_item'			=> __( 'View Resting Spot', 'SagasWhat' ),
		'all_items'			=> __( 'Resting Spots', 'SagasWhat' ),
		'search_items'		=> __( 'Search Resting Spots', 'SagasWhat' ),
		'parent_item_colon'	=> __( 'Parent Resting Spots:', 'SagasWhat' ),
		'not_found'			=> __( 'No rests found.', 'SagasWhat' ),
		'not_found_in_trash'=> __( 'No rests found in Trash.', 'SagasWhat' )
	);
	$args = array(
		'labels'			=> $labels,
		'public'			=> true,
		'has_archive'		=> true,
		'menu_position'		=> 5,
		'rewrite'			=> array('slug' => 'rest'),
		'supports'			=> array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' )
	);

	register_post_type( 'sw_rest', $args );

	// 休憩スポット用カスタム分類(Kind)
	$labels = array(
		'name'				=> _x( 'Kinds', 'taxonomy general name', 'SagasWhat' ),
		'singular_name'		=> _x( 'Kind', 'taxonomy singular name', 'SagasWhat' ),
		'search_items'		=> __( 'Search Kinds', 'SagasWhat' ),
		'all_items'			=> __( 'All Kinds', 'SagasWhat' ),
		'edit_item'			=> __( 'Edit Kind', 'SagasWhat' ),
		'update_item'		=> __( 'Update Kind', 'SagasWhat' ),
		'add_new_item'		=> __( 'Add New Kind', 'SagasWhat' ),
		'new_item_name'		=> __( 'New Kind Name', 'SagasWhat' ),
		'menu_name'			=> __( 'Kind', 'SagasWhat' ),
	);

	$args = array(
		'hierarchical'		=> false,
		'labels'			=> $labels,
	);

	register_taxonomy( 'kind', array( 'sw_rest' ), $args );
	register_taxonomy_for_object_type( 'kind', 'sw_rest' );
	register_taxonomy_for_object_type( 'category', 'sw_rest' );

}
add_action( 'init', 'create_post_type' );

/**
 * タグクラウドのカスタマイズ
 */
function my_widget_tag_cloud_args($args) {
	global $post;
	$include_array = array();

	$meta_query_args = get_meta_query_args();
	$eventargs = array(
		'post_type'		=> 'post',		// イベント記事
		'posts_per_page' => '-1',		// 全件
		'meta_query'	=> $meta_query_args,//全ての終了していないイベント抽出
	);
	$the_query = new WP_Query($eventargs);

	if($the_query->have_posts()) {
		while($the_query->have_posts()) {
			$the_query->the_post();
			$posttags = get_the_tags($post->ID);	// 開催中のイベント記事のタグを抽出
			if ( $posttags ) {
			  foreach ( $posttags as $tag ) {
				if (array_search($tag->term_id, $include_array) === false) {
					array_push($include_array, $tag->term_id);	// タグIDが配列に無ければ追加
				}
			  }
			}
		}
	}
	wp_reset_postdata();

	$include = implode(",", $include_array);	// 表示するタグのIDをカンマ区切りで列挙

	$myargs = array(
		'include'	=>	$include,	// 表示するタグのIDをカンマ区切りで列挙
	);
	$args = wp_parse_args($args, $myargs);
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'my_widget_tag_cloud_args');
