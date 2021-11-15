<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: テーマカスタマイザ
 */

/**
 * ロゴ画像
 */
function bb_customize_logo($wp_customize) {
  global $bb_theme_config;
  $config_values = 'blankblanc_config_values';
  $wp_customize->add_setting(
    "{$config_values}[logo_image]",
    array(
      'default'           => $bb_theme_config['logo_image'],
      'transport'         => 'postMessage',
      'type'              => 'option',
      'capability'        => 'manage_options',
      'sanitize_callback' => 'esc_url_raw',
    )
  );

  function _description() {
    $theme_option = get_admin_url() . 'themes.php?&amp;page=blankblanc_config_edit#tab-3';
    return <<< EOD
    <p>ロゴ画像を変更することができます。<br>
    その他の設定は<a href="{$theme_option}">テーマオプション</a>で変更してください。</p>
EOD;
  }
  $wp_customize->add_control(
    new WP_Customize_Media_Control(
      $wp_customize,
      'control_logo',
      array(
        'label'       => 'ロゴ画像',
        'section'     => 'title_tagline',
        'settings'    => "{$config_values}[logo_image]",
        'priority'    => 60,
        'type'        => 'image',
        'mime_type'   => 'image',
        'description' => _description(),
      )
    )
  );

  $wp_customize->selective_refresh->add_partial(
    "{$config_values}[logo_image]",
    array(
      'selector'            => '#global-header .logo a',
      'container_inclusive' => false,
      'render_callback'     => '_render',
    )
  );
  function _render($partial = null) {
    global $bb_theme_config;
    if ($partial) {
      preg_match('!^(.*?)\[(.*?)\]$!', $partial->id, $keys);
      $bb_option = get_option($keys[1]);
      $src = $bb_option[$keys[2]];
    }
    if (!$src) {
      return '<span class="site-title">' . $bb_theme_config['logo_alt'] . '</span>';
    }
    return '<img src="' . $src . '" alt="">';
  }
}
add_action('customize_register', 'bb_customize_logo');
