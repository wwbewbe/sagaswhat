<?php

require_once locate_template('inc/custom-edit.php', true);    // カスタム投稿タイプ・タクソノミー・カスタムフィールド作成編集

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
//        global $wp_embed;
//        $wp_embed->delete_oembed_caches($post_id);
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
add_editor_style( '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
wp_enqueue_style( 'font-awesone', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

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
            case '1':    //This Month
                if($attachment->post_title == $thismonth) {
                    $url = wp_get_attachment_url($attachment->ID);
                }
                break;
            case '2':    //Next Month
                if($attachment->post_title == $nextmonth) {
                    $url = wp_get_attachment_url($attachment->ID);
                }
                break;
            case '3':    //2 Month Later
                if($attachment->post_title == $twomonth) {
                    $url = wp_get_attachment_url($attachment->ID);
                }
                break;
            case '4':    //3 Month Later
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
register_nav_menu( 'sitenav', 'Site Navigation' );        //ヘッダーに表示するメニュー
register_nav_menu( 'pickupnav', 'Pickup Posts' );        //注目のイベント
register_nav_menu( 'pagenav', 'Page Navigation' );        //TOPページのメニュー
register_nav_menu( 'categorynav', 'Category Menu' );    //TOPにあるカテゴリーメニューから表示するメニュー
register_nav_menu( 'upcomingnav', 'Upcoming Menu' );    //予定(Upcoming)のイベント表示用メニュー
register_nav_menu( 'nearbynav', 'Nearby Menu' );        //周辺イベント検索用メニュー
register_nav_menu( 'calendarnav', 'Calendar Menu' );    //カレンダーから探す用メニュー
register_nav_menu( 'nearbyticnav', 'Nearby TIC Menu' );    //周辺TICのリスト表示用メニュー
register_nav_menu( 'ticnav', 'TIC Menu' );                //TICのリスト表示用メニュー
register_nav_menu( 'floatingmenu', 'Floating Menu' );    //フローティングメニュー
register_nav_menu( 'topicsmenu', 'Topics Menu' );            //TOPページに表示する特集メニュー

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
    // お得イベントのTicker表示用
//    wp_enqueue_script( 'carouFredSel-script', get_template_directory_uri() .'/js/jquery.carouFredSel-6.2.1-packed.js', array( 'jquery' ) );
//    wp_enqueue_script( 'ticker-script', get_template_directory_uri() .'/js/ticker.js', array( 'jquery' ) );
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
/*  $address = str_replace(" ", "+", $address);
  $region = "Japan";

  $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
  $json = json_decode($json);

  $strLat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
  $strLng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
*/
    $strRes = file_get_contents(
         'https://maps.google.com/maps/api/geocode/json'
        . '?address=' . urlencode( mb_convert_encoding( $strAddr, 'UTF-8' ) )
        . '&sensor=false'
        . '&key='
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
            $thname = esc_html_x('Open Hours', 'Resting Spots', 'SagasWhat');
            $info = $info.'<tr><th>'.$thname.'</th><td>'.$bizhours.'</td></tr>';
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
        // 注記
        if( $note = get_post_meta($post->ID, 'note', true) ) {
            $thname = esc_html(__('Note', 'SagasWhat'));
            if ( $noteurl = esc_url(get_post_meta($post->ID, 'noteurl', true)) ) {
                $note = $note.'<div><a href="'.$noteurl.'" target="_blank">'.esc_html__('Click here', 'SagasWhat').'</div>';
            }
            $info = $info.'<tr><th>'.$thname.'</th><td>'.$note.'</td></tr>';
        }
        // 広さ
        if( $howbig = get_post_meta($post->ID, 'howbig', true) ) {
            $thname = esc_html__('Size of the place', 'SagasWhat');
            if ($howbig > '0') {
                switch ($howbig) {
                    case '1': // 1 Star
                        $stars = '<div class="stars"><i class="fa fa-star"></i></div>';
                        break;
                    case '2': // 2 Stars
                        $stars = '<div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i></div>';
                        break;
                    case '3': // 3 Stars
                        $stars = '<div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>';
                        break;
                    default: // as 3 Stars
                        $stars = '<div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>';
                        break;
                }
            }
            $info = $info.'<tr><th>'.$thname.'</th><td>'.$stars.'</td></tr>';
        }
        $table = '<table class="event-info"><tbody>' . $info . '</tbody></table>';
        $parkcomment = '<div class="park-comment">'. get_option('park-comment') .'</div>';
        return $content.$table.$parkcomment;
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

//            return $table.$content.$ticinfo.$adsense;
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
            if($eventopen && $eventclose) {    //開催日も終了日も入力されている場合
                if($eventopen == $eventclose) {
                    $dates = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventclose));
                    if ($closeseason) $dates = $closeseason;    //シーズンが設定されている場合はシーズンを表示
                    $info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
                } else {
                    $eventopen = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventopen));
                    $eventclose = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventclose));
                    if ($openseason) $eventopen = $openseason;        //シーズンが設定されている場合はシーズンを表示
                    if ($closeseason) $eventclose = $closeseason;    //シーズンが設定されている場合はシーズンを表示
                    $dates = $eventopen . ' - ' . $eventclose;
                    $info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
                }
            } elseif($eventopen || $eventclose) {
                if ($eventopen) {    //開催日のみ入力されている場合（終了日未定の場合）
                    $eventopen = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventopen));
                    if ($openseason) $eventopen = $openseason;    //シーズンが設定されている場合はシーズンを表示
                    $dates = $eventopen . ' - ' . '&gt;&gt;&gt;';
                    $info = $info . '<tr><th>'.$thname.'</th><td>' . $dates . '</td></tr>';
                }
                if ($eventclose) {
                    $eventclose = date_i18n(__('F j, Y', 'SagasWhat'), strtotime($eventclose));
                    if ($closeseason) $eventclose = $closeseason;    //シーズンが設定されている場合はシーズンを表示
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

//            return $content.$adsense.$table;
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
                        'file'        =>    'taglist',    //表示に使用するPHPファイル
                        'tagname'    =>    0,            //表示するタグ名
                        'catname'    =>    0,            //表示するカテゴリー名
                        'list'        =>    5,            //表示するリスト数
                        'sort'        =>    false,        //近くのイベント順に並び替えるボタンの表示(true)/非表示(false)
                        'posttype'    =>    'post',        //表示する投稿タイプ(post, page, sw_trend, sw_rest)
                        'tax'        =>    'keyword',    //表示するカスタムタクソノミー(keyword, etc.)＊カスタム投稿のみ使用
                        'terms'        =>    0,            //表示するカスタムタクソノミーの項目名(matsuri, etc.)＊カスタム投稿のみ使用
                        'city'        =>    false,        //市区町村
                        'slidetitle'    =>    'Slider',//スライドボタンタイトル
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

        $query->set('post_type', array('post', 'sw_trend', 'sw_rest'));        // 投稿記事とカスタム投稿を対象
        $query->set('posts_per_page', '20');        // 一覧表示数
        $query->set('category__not_in', array(1, $infocat->cat_ID));// カテゴリが未分類と観光案内所の記事は非表示
        $query->set('orderby', 'modified');    // 更新日の新しい記事順

    } elseif ( !is_admin() && $query->is_main_query() && ($query->is_category('tourist-info-center')) ) {

        $query->set('post_type', 'post');            // 投稿記事を対象
        $query->set('posts_per_page', '10');        // 一覧表示数
        $query->set('orderby', array('meta_tic'=>'asc'));            //TICリスト番号の昇順で表示
        $query->set('meta_query', array(
                        'meta_tic'=>array(
                            'key'        => 'location',                //TICリスト番号
                            'type'        => 'numeric',                //タイプに数値を指定
                        ),
                    ));

    } elseif ( !is_admin() && $query->is_main_query() && ($query->is_tag() || $query->is_category()) ) {

        $query->set('post_type', array('post', 'sw_trend', 'sw_rest'));        // 投稿記事とカスタム投稿を対象
        $query->set('posts_per_page', '10');        // 一覧表示数
        $query->set('category__not_in', array(1, $infocat->cat_ID));// カテゴリが未分類と観光案内所の記事は非表示
        $query->set('orderby', array('meta_recommend'=>'DESC', 'meta_close'=>'ASC', 'title'=>'ASC'));    // 推奨値の高い順
        $query->set('meta_query', get_meta_query_args('0','0'));            // 終了していないイベントを表示

    }
    return $query;
}
add_action('pre_get_posts','QueryListFilter');

//各イベント会場 or 観光案内所と現在地の距離をカスタムフィールドに保存
function set_event_distance($lat, $lng, $target, $posttype = 'post') {
    global $post;

    if ($posttype == 'post') {
        if ($target == 'tic') {
            //観光案内所と現在地の距離
            $infocat = get_category_by_slug('tourist-info-center');
            $args = array(
                'post_type'        => 'post',        // イベント記事
                'posts_per_page' => '-1',        // 全件
                'cat' => $infocat->cat_ID,         // 観光案内所の記事を抽出
            );
        } else {
            //各イベント会場と現在地の距離
            $meta_query_args = get_meta_query_args('0','0');
            $args = array(
                'post_type'        => 'post',        // イベント記事
                'posts_per_page' => '-1',        // 全件
                'meta_query'    => $meta_query_args,//全ての終了していないイベント抽出
            );
        }
    } else {
        $args = array(
            'post_type'        => $posttype,    // カスタム投稿
            'posts_per_page' => '-1',        // 全件
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
            $address = esc_html( get_post_meta($post->ID, 'address', true) );
            if ($address && ((empty($spotLat)) || (empty($spotLng)))) {
                $LatLng = strAddrToLatLng($address);
                if (is_array($LatLng) && isset($LatLng['Lat'])) {
                  $spotLat = $LatLng['Lat'];
                }
                if (is_array($LatLng) && isset($LatLng['Lng'])) {
                  $spotLng = $LatLng['Lng'];
                }
//                $spotLat = $LatLng['Lat'];
//                $spotLng = $LatLng['Lng'];
                update_post_meta($post->ID, 'spot_lat', $spotLat);
                update_post_meta($post->ID, 'spot_lng', $spotLng);
            }
            if ($spotLat && $spotLng) {
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
function get_meta_query_args( $recommend = '0', $distance = '0' ) {
    $args = array(
        'relation'        => 'AND',
        array(
            'relation'        => 'OR',
            array(
                'key'        => 'eventclose', //カスタムフィールドのイベント終了日欄
                'compare'    => 'NOT EXISTS', //カスタムフィールドがない場合も表示
            ),
            'meta_close'=>array(
                'key'        => 'eventclose', //カスタムフィールドのイベント終了日欄
                'value'        => date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
                'compare'    => '>=', //今日以降なら表示
                'type'        => 'date', //タイプに日付を指定
            ),
        ),
        array(
            'relation'        => 'OR',
            array(
                'key'        => 'recommend', //カスタムフィールドのおすすめ度
                'compare'    => 'NOT EXISTS', //カスタムフィールドがない場合も表示
            ),
            'meta_recommend'=>array(
                'key'        => 'recommend', //カスタムフィールドのおすすめ度
                'value'        => $recommend, //
                'compare'    => '>=', //指定のおすすめ度以上を表示
                'type'        => 'numeric',  //タイプに数値を指定
            ),
        ),
    );
    if ($distance == 'exists') {
        $args += array('meta_distance'=>array(
                    'key'        => 'distance', //カスタムフィールドの距離データ
                    'compare'    => 'exists', //距離データのあるイベントをすべて表示
                ));
    } elseif (!empty($distance)) {
        $args += array('meta_distance'=>array(
                    'key'        => 'distance', //カスタムフィールドの距離データ
                    'value'        => $distance, //
                    'compare'    => '<=', //指定距離内のイベントを表示
                    'type'        => 'char', //タイプに数値を指定
                ));
    }

    return $args;
}

// カテゴリ一覧ウィジェットから特定のカテゴリを除外
function my_theme_catexcept($cat_args){
    $infocat = get_category_by_slug('tourist-info-center');
    $exclude_id = "1,$infocat->cat_ID"; // 除外するカテゴリID(未分類)
    $cat_args['exclude'] = $exclude_id; // 除外
    return $cat_args;
}
add_filter('widget_categories_args', 'my_theme_catexcept',10);

function the_ticker_event() {
    global $post;

    $args = array(
        'post_type'        => 'sw_val',
        'posts_per_page'=> '-1',
        'meta_query'    => array(
            'relation'        => 'AND',
            'meta_close'=>array(
                'key'        => 'eventclose', //カスタムフィールドのイベント終了日欄
                'value'        => date_i18n( "Y/m/d" ),//イベント終了日を今日と比較
                'compare'    => '>=', //今日以降なら表示
                'type'        => 'date', //タイプに日付を指定
            ),
            'meta_open'=>array(
                'key'        => 'eventopen', //カスタムフィールドのおすすめ度
                'value'        => date_i18n( "Y/m/d", strtotime("+3 day") ),//イベント開催日を今日と比較
                'compare'    => '<=', //指定のおすすめ度以上を表示
                'type'        => 'date', //タイプに日付を指定
            ),
        ),
    );

    $the_query = new WP_Query($args);

    printf('<div id="wrapper">');
    printf('<div class="first">');
    printf('<dl id="ticker">');
    printf('<dt>%1$s</dt><dd><a href="%3$s/topics-valuable">%2$s</a></dd>', esc_html__('Information', 'SagasWhat'), esc_html__('Find the free & discount information for Museum, Zoo, etc.', 'SagasWhat'), get_bloginfo('url'));

    if($the_query->have_posts()) {
        while($the_query->have_posts()) {
            $the_query->the_post();
            if ($openseason = get_post_meta($post->ID, 'openseason', true)) {
                printf('<dd><a href="%4$s/topics-valuable">%1$s : %2$s : %3$s</a></dd>', $openseason, get_the_title(), get_post_meta($post->ID, 'ticker', true), get_bloginfo('url'));
            } else {
                printf('<dd><a href="%4$s/topics-valuable">%1$s : %2$s : %3$s</a></dd>', get_post_meta($post->ID, 'eventopen', true), get_the_title(), get_post_meta($post->ID, 'ticker', true), get_bloginfo('url'));
            }
        }
    } else {
        printf('<dt>%1$s</dt><dd><a href="%3$s/topics-valuable">%2$s</a></dd>', esc_html__('Information', 'SagasWhat'), esc_html__('Find the free & discount information for Museum, Zoo, etc.', 'SagasWhat'), get_bloginfo('url'));
    }
    wp_reset_postdata();

    printf('</dl>');
    printf('</div>');
    printf('</div>');

}
