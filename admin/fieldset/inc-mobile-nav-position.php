<fieldset <?php $this->has_modified('mobile_nav_position'); ?>>
  <div class="col-left">
    <div class="label-title">スライドナビを右側に変更</div>
    <div class="note">モバイル時のスライドナビウィンドウの方向を右側からに変更します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[mobile_nav_position]" value="">
      <input type="checkbox" name="blankblanc_config_values[mobile_nav_position]" id="bb-config-mobile-nav-position" value="right"<?php if ($config_values['mobile_nav_position'] === 'right') echo ' checked'; ?>>
    </div>
    <?php
    $_default = '左（指定なし）';
    if ($bb_theme_default['mobile_nav_position'] === 'right') {
      $_default = '右（right）';
    }
    ?>
    <div class="default">初期値: <?php _echo($_default); ?></div>
  </div>
</fieldset>
