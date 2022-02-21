<fieldset <?php $this->has_modified('mobile_nav'); ?>>
  <?php
  global $wp_registered_sidebars, $wp_registered_widgets;
  $before_nav = has_nav_menu('global_nav') ? array('global-nav' => array('global-nav')) : array();
  $after_nav = has_nav_menu('header_nav') ? array('header-nav' => array('header-nav')) : array();
  $active_widgets = array_merge(
    $before_nav,
    wp_get_sidebars_widgets(),
    $after_nav
  );
  unset($active_widgets['wp_inactive_widgets']);
  $li_chkd = $li_none = array();
  foreach ($active_widgets as $widget_group_id => $widget_group) {
    if (empty($widget_group)) {
      continue;
    }
    foreach ($widget_group as $widget_id) {
      if ($widget_id == 'global-nav') {
        $widget_name = 'グローバルナビゲーション';
        $sidebar_name = 'メインメニュー';
      } elseif ($widget_id == 'header-nav') {
        $widget_name = 'ヘッダーナビゲーション';
        $sidebar_name = 'ヘッダー内';
      } else {
        $sidebar_name = !empty($wp_registered_sidebars[$widget_group_id]['name']) ? $wp_registered_sidebars[$widget_group_id]['name'] : '';
        $reg_widget = !empty($wp_registered_widgets[$widget_id]) ? $wp_registered_widgets[$widget_id] : '';
        $widget = get_option($reg_widget['classname']);
        $widget_name = !empty($widget[$reg_widget['params'][0]['number']]['title']) ? esc_attr($widget[$reg_widget['params'][0]['number']]['title']) : esc_attr__($reg_widget['name']);
      }
      if ($sidebar_name && $widget_name) {
        if (isset($config_values['mobile_nav']) && in_array('#' . $widget_id, $config_values['mobile_nav'])) {
          $key = array_search('#' . $widget_id, $config_values['mobile_nav']);
          $li_chkd[$key] = "<input type=\"checkbox\" name=\"blankblanc_config_values[mobile_nav][]\" id=\"bb-widget_{$widget_id}\" value=\"#{$widget_id}\" checked>\n"
          . "<label for=\"bb-widget_{$widget_id}\"><span class=\"widgets-name\">{$widget_name}</span><span class=\"sidebar-name\">（{$sidebar_name}）</span></label>\n";
        } else {
          $li_none[] = "<input type=\"checkbox\" name=\"blankblanc_config_values[mobile_nav][]\" id=\"bb-widget_{$widget_id}\" value=\"#{$widget_id}\">\n"
          . "<label for=\"bb-widget_{$widget_id}\"><span class=\"widgets-name\">{$widget_name}</span><span class=\"sidebar-name\">（{$sidebar_name}）</span></label>\n";
        }
      }
    }
  }
  ksort($li_chkd);
  $li_arr = array_merge($li_chkd, $li_none);
  ?>
  <div class="col-left">
    <div class="label-title">モバイルメニュー</div>
    <div class="note">選択されたウィジェットはモバイル時のスライドナビ内のメニューとして登録されます。<br>
    ドラッグ&amp;ドロップで表示順を並べ替えできます。</div>
  </div>
  <div class="col-right">
    <div class="input-group">
      <ol id="activate-mobile-nav">
        <?php foreach ($li_arr as $li) : ?>
          <li><?php echo $li; ?></li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</fieldset>
