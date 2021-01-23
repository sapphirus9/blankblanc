<fieldset class="mobile-nav-position">
  <div class="col-left">
    <div class="label-title">モバイルナビを右側に変更</div>
    <div class="note">モバイル時のスライドナビの方向を右側からに変更します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[mobile_nav_position]" value="false">
      <input type="checkbox" name="blankblanc_config_values[mobile_nav_position]" id="bb-config-mobile-nav-position" value="true"<?php if ($config_values['mobile_nav_position']) echo ' checked'; ?>>
    </div>
    <?php $_default = $bb_theme_default['mobile_nav_position'] ? '右 (true)' : '左 (false)'; ?>
    <div class="default">初期値: <?php _echo($_default); ?></div>
  </div>
</fieldset>
