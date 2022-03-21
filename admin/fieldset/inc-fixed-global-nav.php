<fieldset <?php $this->has_modified('fixed_global_nav'); ?>>
  <div class="col-left">
    <div class="label-title">グローバルナビの固定</div>
    <div class="note">フルブラウザ表示のグローバルナビをスクロール時に上部で固定します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[fixed_global_nav]" value="false">
      <input type="checkbox" name="blankblanc_config_values[fixed_global_nav]" id="bb-config-fixed-global-nav" value="true"<?php if ($config_values['fixed_global_nav']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['fixed_global_nav']); ?></div>
  </div>
</fieldset>
