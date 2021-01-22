<?php
/**
 * TinyMCE の追加フォーマット設定
 */
if (!function_exists('add_mce_buttons')) {
  function add_mce_buttons($buttons) {
    array_push($buttons, 'styleselect');
    return $buttons;
  }
}
add_filter('mce_buttons', 'add_mce_buttons');

if (!function_exists('customize_tiny_mce')) {
  function customize_tiny_mce($init) {
    $init['block_formats'] = '段落=p;見出し2=h2;見出し3=h3;見出し4=h4;見出し5=h5;見出し6=h6;Div=div';
    $style_formats = array(
      array(
        'title' => '行間',
        'items' => array(
          array(
            'title'    => '行間を狭める(狭い Lv1)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,li,dl,dt,dd,img,table',
            'classes'  => 'line-height-narrow-lv1'
          ),
          array(
            'title'    => '行間を狭める(より狭い Lv2)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,li,dl,dt,dd,img,table',
            'classes'  => 'line-height-narrow-lv2'
          ),
        ),
      ),
      array(
        'title' => 'マージン',
        'items' => array(
          array(
            'title'    => 'マージン消去',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,li,dl,dt,dd,img,table,hr',
            'classes'  => 'clear-margin'
          ),
          array(
            'title'    => '上マージン追加(狭い Lv1)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,dl,img,table,hr',
            'classes'  => 'margin-top-lv1'
          ),
          array(
            'title'    => '上マージン追加(中位 Lv2)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,dl,img,table,hr',
            'classes'  => 'margin-top-lv2'
          ),
          array(
            'title'    => '上マージン追加(広い Lv3)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,dl,img,table,hr',
            'classes'  => 'margin-top-lv3'
          ),
          array(
            'title'    => '下マージン追加(狭い Lv1)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,dl,img,table,hr',
            'classes'  => 'margin-bottom-lv1'
          ),
          array(
            'title'    => '下マージン追加(中位 Lv2)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,dl,img,table,hr',
            'classes'  => 'margin-bottom-lv2'
          ),
          array(
            'title'    => '下マージン追加(広い Lv3)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,dl,img,table,hr',
            'classes'  => 'margin-bottom-lv3'
          ),
          array(
            'title'    => '左マージン追加',
            'selector' => 'div,span,em,strong,img',
            'classes'  => 'margin-left'
          ),
          array(
            'title'    => '右マージン追加',
            'selector' => 'div,span,em,strong,img',
            'classes'  => 'margin-right'
          ),
        ),
      ),
      array(
        'title' => 'その他',
        'items' => array(
          array(
            'title'    => '回り込み解除',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,dl,img,table,hr',
            'classes'  => 'clear'
          ),
          array(
            'title'    => 'ブロック内センタリング(段落)',
            'selector' => 'div,p',
            'classes'  => 'block-center'
          ),
          array(
            'title'    => 'オーバーフロー(hidden)',
            'selector' => 'div,p,h2,h3,h4,h5,h6,ul,ol,li,dl,dt,dd,img,table',
            'classes'  => 'overflow-hidden'
          ),
        ),
      ),
    );
    $init['style_formats'] = json_encode($style_formats);
    return $init;
  }
}
add_filter('tiny_mce_before_init', 'customize_tiny_mce', 1000);
add_editor_style('admin/editor-style.css');
