<?php

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
        'リスト設定',		// メタボックスのラベル
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
	//textarea for Resting Spot name
	$value = get_post_meta(
		$post->ID,	//post ID
		'eventname',		//Custom Field Key
		true		//true:単一文字列, false:複数配列
	);
	echo '<dl>';
	echo   '<dt>';
	echo     '<label for="Eventname">';
	echo     '休憩スポット名';
	echo     '</label>';
	echo   '</dt>';
	echo   '<dd>';
	echo     '<textarea id="Eventname" name="eventname" style="width:100%">'.$value.'</textarea>';
	echo   '</dd>';
	echo '</dl>';

	//textarea for Nearest Station name
	$value = get_post_meta(
		$post->ID,	//post ID
		'venue',		//Custom Field Key
		true		//true:単一文字列, false:複数配列
	);
	echo '<dl>';
	echo   '<dt>';
	echo     '<label for="Venue">';
	echo     '最寄り駅';
	echo     '</label>';
	echo   '</dt>';
	echo   '<dd>';
	echo     '<textarea id="Venue" name="venue" style="width:100%">'.$value.'</textarea>';
	echo   '</dd>';
	echo '</dl>';

	//textarea for SwhoAddress
	$value = get_post_meta(
		$post->ID,	//post ID
		'showaddress',		//Custom Field Key
		true		//true:単一文字列, false:複数配列
	);
	echo '<dl>';
	echo   '<dt>';
	echo     '<label for="ShowAddress">';
	echo     '表示住所';
	echo     '</label>';
	echo   '</dt>';
	echo   '<dd>';
	echo     '<textarea id="ShowAddress" name="showaddress" style="width:100%">'.$value.'</textarea>';
	echo   '</dd>';
	echo '</dl>';

	//textarea for Address
	$value = get_post_meta(
		$post->ID,	//post ID
		'address',		//Custom Field Key
		true		//true:単一文字列, false:複数配列
	);
	echo '<dl>';
	echo   '<dt>';
	echo     '<label for="Address">';
	echo     '住所';
	echo     '</label>';
	echo   '</dt>';
	echo   '<dd>';
	echo     '<textarea id="Address" name="address" style="width:100%">'.$value.'</textarea>';
	echo   '</dd>';
	echo '</dl>';

	//textarea for Open Hours
	$value = get_post_meta(
		$post->ID,	//post ID
		'bizhours',		//Custom Field Key
		true		//true:単一文字列, false:複数配列
	);
	echo '<dl>';
	echo   '<dt>';
	echo     '<label for="BizHours">';
	echo     '開園時間';
	echo     '</label>';
	echo   '</dt>';
	echo   '<dd>';
	echo     '<textarea id="BizHours" name="bizhours" style="width:100%">'.$value.'</textarea>';
	echo   '</dd>';
	echo '</dl>';

	//textarea for Open Hours
	$value = get_post_meta(
		$post->ID,	//post ID
		'url',		//Custom Field Key
		true		//true:単一文字列, false:複数配列
	);
	echo '<dl>';
	echo   '<dt>';
	echo     '<label for="URL">';
	echo     'URL';
	echo     '</label>';
	echo   '</dt>';
	echo   '<dd>';
	echo     '<textarea id="URL" name="url" style="width:100%">'.$value.'</textarea>';
	echo   '</dd>';
	echo '</dl>';

	//textarea for Open Hours
	$value = get_post_meta(
		$post->ID,	//post ID
		'recommend',		//Custom Field Key
		true		//true:単一文字列, false:複数配列
	);
	echo '<dl>';
	echo   '<dt>';
	echo     '<label for="Recommend">';
	echo     'おすすめ度';
	echo     '</label>';
	echo   '</dt>';
	echo   '<dd>';
	echo     '<textarea id="Recommend" name="recommend" style="width:100%">'.$value.'</textarea>';
	echo   '</dd>';
	echo '</dl>';

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
        1 => '小',
        2 => '中',
        3 => '大',
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
		'eventname' => true,
		'venue' => true,
		'showaddress' => true,
		'address' => true,
		'bizhours' => true,
		'url' => true,
		'recommend' => true,
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
