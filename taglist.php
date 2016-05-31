<div class="taglist">
<?php
$args=array(
            'post_type'		=> 'post',
            'posts_per_page'=> '5',
            'cat'           => '-1',  // カテゴリースラッグ
            'tag'           => $tagname, // タグを指定
            'meta_key'		=> 'recommend',
            'orderby'		=> 'meta_value_num',
            'meta_query'	=> array(
                'relation'		=> 'OR',
                array(
                    'relation'		=> 'AND',
                    array(
                        'key'		=> 'eventclose',
                        'compare'	=> 'NOT EXISTS',
                    ),
                    array(
                        'key'		=> 'eventopen',
                        'compare'	=> 'NOT EXISTS',
                    ),
                ),
                array(
                    'relation'		=> 'AND',
                    array(
                        'key'		=> 'eventclose',
                        'compare'	=> 'NOT EXISTS',
                    ),
                    array(
                        'key'		=> 'eventopen', //カスタムフィールドのイベント開催日欄
                        'value'		=> date_i18n( "Y/m/d" ), //イベント開催日を今日と比較
                        'compare'	=> '<=', //今日以前なら表示
                    ),
                ),
                array(
                    'relation'		=> 'AND',
                    array(
                        'key'		=> 'eventclose', //カスタムフィールドのイベント終了日欄
                        'value'		=> date_i18n( "Y/m/d" ), //イベント終了日を今日と比較
                        'compare'	=> '>=', // 今日以降なら表示
                    ),
                    array(
                        'key'		=> 'eventopen',
                        'compare'	=> 'NOT EXISTS',
                    ),
                ),
                array(
                    'reration'		=> 'AND',
                    array(
                        'key'		=> 'eventclose', //カスタムフィールドのイベント終了日欄
                        'value'		=> date_i18n( "Y/m/d" ), //イベント終了日を今日と比較
                        'compare'	=> '>=', // 今日以降なら表示
                    ),
                    array(
                        'key'		=> 'eventopen', //カスタムフィールドのイベント開催日欄
                        'value'		=> date_i18n( "Y/m/d" ), //イベント開催日を今日と比較
                        'compare'	=> '<=', //今日以前なら表示
                    ),
                ),
            ),
        ); ?>
<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

  <?php get_template_part( 'gaiyou', 'medium' ); ?>

<?php endwhile; endif; ?>

  <?php wp_reset_postdata(); ?>
</div>
